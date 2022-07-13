<?php

namespace console\controllers;



use common\models\FbOddOtherBase;
use common\models\FbOddsBase;
use common\models\MatchBase;
use common\models\PredictExpertBase;
use common\models\PredictLogBase;
use yii\console\Controller;

class PredictController extends Controller
{

    public $resultPredicts = ['1-0','2-0','2-0','2-0','2-1','2-1','3-0','3-0','3-1','3-2','4-0','4-1','1-0','2-0','2-1','4-2','2-0','2-1','4-3','1-2','1-3','5-0','5-1','0-0','0-0','1-1','1-1','1-1','2-2','2-2','3-3','0-1','0-2','0-2','0-2','0-1','0-2','0-3','0-4','1-2','1-2','1-3','1-3','1-2','1-3','1-3','1-4','2-3','2-4','2-5','3-4'];

    public function actionPredict(){
        $time = time();
        $endTime = strtotime('+3 day');
        /*$sql = '
            SELECT DISTINCT matchId, changeTime FROM fb_odds WHERE type=:type AND inPlay = 0 AND changeTime >= :changeTime ORDER BY changeTime DESC
        ';*/
        $sql = '
            SELECT * FROM fb_matches WHERE `status` = :status AND matchTime >= :startTime AND matchTime <= :endTime
        ';
        $type = FbOddsBase::TYPE_HANDICAP;
        $status = MatchBase::STATUS_NOT_STARTED;
        /*$datas = \Yii::$app->db->createCommand($sql)
            ->bindParam(':type', $type)
            ->bindParam(':changeTime', $time)
            ->queryAll();*/
        $query = \Yii::$app->db->createCommand($sql)
            ->bindParam(':status', $status)
            ->bindParam(':startTime', $time)
            ->bindParam(':endTime', $endTime);
        $this->echoLog('sql: '.$query->getRawSql());
         $datas = $query->queryAll();

        if(!empty($datas) ){
            foreach ($datas as $data){
                $match = MatchBase::findOne($data['matchId']);
                if(empty($match)){
                    $this->echoLog('Match Not Found.');
                    continue;
                }
                $handicap = FbOddsBase::findOne([
                    'matchId' => $match->matchId,
                    'type' => FbOddsBase::TYPE_HANDICAP,
                    'inPlay' => 0,
                ]);
                if(empty($handicap)){
                    $this->echoLog('chua co keo');
                    continue;
                }
                $oddScore  = FbOddOtherBase::findOne([
                    'matchId' => $match->matchId,
                    'type' => FbOddOtherBase::TYPE_CORRECT_SOCRE,
                    'inPlay' => 0
                ]);
                $odds = null;
                if(!empty($oddScore->odds)){
                    $odds = $oddScore->odds;
                }
                $limitRand = rand(21, 30);
                $experts = PredictExpertBase::find()->orderBy('RAND()')->limit($limitRand)->all();
                if(!empty($experts)) {
                    foreach ($experts as $expert) {
                        $this->runPredict($match, $expert, $handicap, $odds);
                    }
                }
            }
        }
    }

    public function actionResult(){
        $loop = true;
        $limit = 1000;
        while ($loop) {
            $sql = '
                SELECT p.* FROM predict_logs p 
                LEFT JOIN fb_matches m ON m.matchId=p.matchId 
                WHERE p.`status` = 0 AND m.`status` = -1 
                LIMIT :limit
            ';
            $datas = \Yii::$app->db->createCommand($sql)
                ->bindParam(':limit', $limit)
                ->queryAll();

            if (!empty($datas)) {
                foreach ($datas as $data) {
                    $predict = PredictLogBase::findOne($data['id']);
                    if(empty($predict)) continue;
                    $this->runResult($predict);
                }
            }else{
                $loop = false;
            }
        }
    }

    /**
     * @param MatchBase $match
     * @param PredictExpertBase $expert
     * @return bool
     */
    private function runPredict($match, $expert, $handicap, $odds = null){
        $this->echoLog('-----> matchId: '.$match->matchId.' | expertId: '.$expert->id);
        $resultPredicts = PredictExpertBase::getResultPredicts();
        if(!empty($odds)){
            $resultPredicts = PredictExpertBase::getResultPredictByOdd($odds);
        }
        $result = PredictExpertBase::getRandPredict($resultPredicts);
        if(empty($result)) {
            $rand = rand(0, 50);
            $result = $this->resultPredicts[$rand];
        }

        $this->echoLog('Predict: '.$result);
        $explode = explode('-', $result);
        $home = (int) $explode[0];
        $away = (int) $explode[1];
        $timePredict = rand(strtotime('-7 day', $match->matchTime), $match->matchTime);

        $log = PredictLogBase::findOne([
            'matchId' => $match->matchId,
            'predict_expert_id' => $expert->id
        ]);
        if(empty($log)){
            $log = new PredictLogBase();
            $log->matchId = $match->matchId;
            $log->predict_expert_id = $expert->id;
        }else{
            $this->echoLog('Du doan da duoc tao');
            return false;
        }
        $log->odd_handicap = $handicap->instantHandicap;

        $log->match_time = $match->matchTime;
        $log->home_predict = $home;
        $log->away_predict = $away;
        $log->predict_time = $timePredict;
        $over = FbOddsBase::findOne([
            'matchId' => $match->matchId,
            'type' => FbOddsBase::TYPE_OVER_UNDER,
            'inPlay' => 1,
        ]);
        if(!empty($over)){
            $log->odd_over_under = $over->instantHandicap;
        }
        $log->updated_time = date('Y-m-d H:i:s');
        return $log->save(false);
    }


    /**
     * @param PredictLogBase $predict
     * @return bool
     */
    private function runResult($predict){
        $this->echoLog('-----> result: '.$predict->id);
        $match = MatchBase::findOne($predict->matchId);
        if($match->status != MatchBase::STATUS_FINISHED){
            $this->echoLog('match not FINISHED');
            return false;
        }
        $predict->home_goal = $match->homeScore;
        $predict->away_goal = $match->awayScore;
        $win = 0;
        $draw = 0;
        $lose = 0;
        $expert = PredictExpertBase::findOne($predict->predict_expert_id);

        $x = $match->homeScore - $match->awayScore;
        $y = $predict->home_predict - $predict->away_predict;
        $z = empty($predict->odd_handicap)?0:$predict->odd_handicap;

        if($x > $z && $y > $z){
            $win = 1;
        }else if($x < $z && $y < $z){
            $win = 1;
        }elseif($x == $z){
            $draw = 1;
        }else{
            $lose = 1;
        }


        $predict->updated_time = date('Y-m-d H:i:s');
        $expert->updated_time = date('Y-m-d H:i:s');

        $predict->status = 1;
        $predict->result_win = $win;
        $predict->result_draw = $draw;
        $predict->result_lose = $lose;

        $expert->count_match += 1;
        $expert->count_win += $win;
        $expert->count_draw += $draw;
        $expert->count_lose += $lose;

        $expert->save(false);
        return $predict->save(false);
    }

    private function echoLog($message)
    {
        echo PHP_EOL . date('Y-m-d H:i:s') . ' ---> ' . $message;
    }

}