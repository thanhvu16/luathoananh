<?php
/**
 * @Function: Class Synchronize data from CMC to Vclip
 * @Author: trinh.kethanh@gmail.com
 * @Date: 29/07/2015
 * @System: Video 2.0
 */

namespace console\components\datachecker\service;

use api\models\Actor;
use api\models\Director;
use api\models\FilmActor;
use api\models\FilmDirector;
use common\components\KLogger;
use common\models\FilmClipBase;
use Yii;
use yii\db\Query;
use yii\console\Exception;
use common\models\ClipBase;
use common\models\FilmBase;

class MclipServiceCMC extends GenericService
{
    public $clipId;
    public $filmId;
    public $logger;

    public function __construct()
    {
    }

    /**
     * @param array $payload
     * @return bool|int
     */
    public function process($payload)
    {
        $logger = new KLogger('logs'.DS.'MclipServiceCMC_Clip'.DS.'Clip_'.date('Ymd'), KLogger::INFO);
        $logger->LogInfo(date('Y-m-d H:i:s').'BEGIN PAYLOAD '.json_encode($payload));
        $clip = new ClipBase();
        if (!empty($payload['TITLE'])) {
            $clip->title_1 = $payload['TITLE'];
        } else
            $clip->title_1 = 'Untitled';

        if (!empty($payload['TITLE_EN'])) {
            $clip->title_2 = $payload['TITLE_EN'];
        } else
            $clip->title_2 = 'Untitled';

        if (!empty($payload['TITLE_OTHER'])) {
            $clip->title_3 = $payload['TITLE_OTHER'];
        } else
            $clip->title_3 = 'Untitled';
        $cp_id = isset($payload['NAME'])? $this->findTargetMemberId($payload['NAME']):0; // $payload['CP_ID'];
        $clip->brief_1 = $payload['DESCRIPTION'];
        $clip->brief_2 = $payload['DESCRIPTION_EN'];
        $clip->brief_3 = $payload['DESCRIPTION_OTHER'];
        $clip->description_1 = $payload['DESCRIPTION'];
        $clip->description_2 = $payload['DESCRIPTION_EN'];
        $clip->description_3 = $payload['DESCRIPTION_OTHER'];
        $clip->tag = $payload['TAG'];
        $clip->duration = $payload['DURATION'];
        $clip->deleted = $payload['DELETED'];
        $clip->active = $payload['STATUS'];
        //$clip->streaming_price = $payload['STREAM_PRICE'];
        //$clip->download_price = $payload['DOWNLOAD_PRICE'];
        $clip->streaming_price = 0;
        $clip->download_price = 0;
        $clip->created_time = $payload['CREATED_TIME'];
        $clip->updated_time = date('Y-m-d H:i:s');
        $clip->sync_time = date('Y-m-d H:i:s');
        $clip->cms_updated_time = date('Y-m-d H:i:s');
        $clip->created_by = $this->findTargetMemberId($payload['NAME']);
        $clip->updated_by = $payload['UPDATED_BY']; 
        $clip->approved_time = null;
        $clip->approved_by = null;
        $clip->approved = $payload['APPROVED'];
        $clip->upload_id = $payload['UPLOAD_ID'];
        $clip->category_id = $this->findTargetCategory($payload['CATEGORY_ID']);
        $clip->source_id = $payload['SOURCE_ID'];
        $clip->converted = $payload['CONVERTED'];
        $clip->cp_id = $cp_id;
        $clip->user_id = $payload['MEMBER_ID'];
        $clip->ms_id = $payload['MS_ID'];
        $clip->copyright = $payload['UPDATED_BY'];
        $clip->source = $payload['SOURCE_URL'];
        $clip->page_id = null;
        $clip->cmc_id = $payload['ID'];
        $logger->LogInfo('Clip object  cp_id '.$cp_id);
        $exists = ClipBase::find()->where(['cmc_id' => $payload['ID'],'deleted'=>0])->one();
       // $titleCheck = ClipBase::find()->where(['title_1' => $payload['TITLE']])->one();
		 $logger->LogInfo('check clip existed CMCID:'.$payload['ID']);
        //if (strtotime($payload['UPDATED_TIME']) > strtotime('-2 day') && $exists === false && empty($titleCheck)) {
        if (!$exists) {
			 $logger->LogInfo('clip not already existed CMC_ID: '. $payload['ID']);
            if (!$clip->save(false)) {
                $logger->LogInfo('Save Error'.json_encode($clip));
                $logger->LogInfo(date('Y-m-d H:i:s').' Save error'.json_encode($clip->getErrors()));
                return false;
            } else {
                $logger->LogInfo(date('Y-m-d H:i:s').' Save success CLIPID:'.$clip->id);
                $this->clipId = $clip->id;
                return $this->clipId;
            }
        } else {
            $exists->cp_id = $cp_id;
            $exists->save(false);
			$logger->LogInfo('clip already existed CMCID: '.$exists->cmc_id);
			return  $exists->id;
            //return false;
        }
    }

    /**
     * @param $payload
     * @return bool
     * Dong bo phim
     */
    public function processFilm($payload)
    {
        //var_dump($payload);
        $logger = new KLogger('logs'.DS.'MclipServiceCMC_Film'.DS.'Film_'.date('Ymd'), KLogger::INFO);
        $logger->LogInfo(date('Y-m-d H:i:s').''.json_encode($payload));
        $film = new FilmBase();

        if (!empty($payload['title'])) {
            $film->title_1 = $payload['title'];
        } else
            $film->title_1 = 'Untitled';


        $cp_id = $this->findTargetMemberId($payload['NAME']);
        $film->brief_1 = $payload['title'];
        $film->description_1 = $payload['description'];
        $film->tag = $payload['tags'];
        $film->duration = $payload['duration'];
        $film->deleted = $payload['deleted'];
        $film->active = 0;
        $film->created_time = $payload['created_time'];
        $film->updated_time = date('Y-m-d H:i:s');//$payload['updated_time'];
        $film->cms_updated_time = date('Y-m-d H:i:s');//$payload['updated_time'];
        $film->streaming_price = 1000;
        $film->download_price = 2000;
        $film->created_by = $this->findTargetMemberAdmin($payload['NAME'],$cp_id);
        $film->approved_time = null;
        $film->approved_by = null;
        $film->year = $payload['year'];
        $film->approved = (isset($payload['approved']) && !empty($payload['approved']))?$payload['approved']:0;
        $film->category_id = $this->findTargetCategoryFilm($payload['category_id']);
        $film->copyright = $payload['copyright'];
        $film->cp_id = $cp_id;
        $film->page_id = null;
        $film->cmc_id = $payload['id'];
        $film->upload_id = $payload['upload_id'];
        $film->country_id = ($payload['country'])?$this->findTargetCountryId($payload['country']):19; // 19 = khác


        $titleCheck = FilmBase::find()->where(['cmc_id' => $payload['id'], 'deleted'=>0])
            ->one();
		 $logger->LogInfo('check film existed');
        //if (strtotime($payload['updated_time']) > strtotime('-2 day') && empty($titleCheck)) {
        if (empty($titleCheck)) {
			$logger->LogInfo('film not existed');
            if (!$film->save(false)) {
                $logger->LogInfo(date('Y-m-d H:i:s').' Save Error'.json_encode($film));
                $logger->LogInfo(date('Y-m-d H:i:s').' Save error'.json_encode($film->getErrors()));
                return false;
            } else {
                // Clip film
				$logger->LogInfo('addFilmClip '.$film->id);
                self::addFilmClip($film->id,$payload['VIDEO_LIST']);
                // Đạo diễn
                self::addDirector($film->id,$payload['director']);
                // Diễn viên
                self::addActor($film->id,$payload['actor']);

                $logger->LogInfo(date('Y-m-d H:i:s').' Save success :'.json_encode($film));
                $logger->LogInfo(date('Y-m-d H:i:s').' Save success FILMID:'.$film->id);
                $this->filmId = $film->id;

                $filmId =  $this->filmId;
            }
        } else {
			$logger->LogInfo('film existed'.$film->id);
			$titleCheck->cmc_id = $payload['id'];
			$titleCheck->cp_id = $cp_id;
			$titleCheck->save(false);
            $logger->LogInfo(date('Y-m-d H:i:s').'titleCheck:'.json_encode($titleCheck));
            $filmId =  $titleCheck->id;
        }

        //xu ly set status film đã đồng bộ
        $sid = \common\components\CFunction::getParams('site_id');
        $url = 'http://cmc.vega.com.vn/api/setFilmStatus?sid='.$sid.'&film_id='.$payload['id'].'&status=1';
        self::sendApi($url);

        return $filmId;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function downloadFailed()
    {
        try {
            if ($this->clipId) {
                Yii::$app->db->createCommand()
                    ->update('clip', ['downloaded' => -1], 'id = '.$this->clipId)
                    ->execute();
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @param $approved
     * @throws \yii\db\Exception
     */
    public function approve($approved)
    {
        try {
            if ($this->clipId) {
                Yii::$app->db->createCommand()
                    ->update('clip', ['approved' => $approved, 'converted' => 1, 'deleted' => 0], 'id = '.$this->clipId)
                    ->execute();
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    public function rollback()
    {
        $connection = Yii::$app->db;
        $connection ->createCommand()->delete('clip', 'id = '.$this->clipId)->execute();
        $connection ->createCommand()->delete('clip_file_src', 'clip_id = '.$this->clipId)->execute();
    }


    public function addDirector($filmId,$directors)
    {
        // addDirector
        $logger = new KLogger('logs'.DS.'MclipServiceCMC_Film'.DS.'Film_'.date('Ymd'), KLogger::INFO);
        $logger->LogInfo(date('Y-m-d H:i:s').'BEGIN addDirector: Film ID'.$filmId);
        try{
            if($directors){
                    $logger->LogInfo(date('Y-m-d H:i:s').' Save director:'.$directors);

                    $director = \common\models\DirectorBase::find()->where(['name'=>$directors])->one();
                    if(!$director){
                        $logger->LogInfo(date('Y-m-d H:i:s').' director not exist:'.$directors);
                        $modelDirector = new \common\models\DirectorBase();
                        $modelDirector->name            = $directors;
                        $modelDirector->description     = $directors;
                        $modelDirector->created_time    = date('Y-m-d H:i:s');
                        $modelDirector->updated_time    = date('Y-m-d H:i:s');
                        if($modelDirector->save(false)){
                            $logger->LogInfo(date('Y-m-d H:i:s').' Add FilmDirector:'.$directors);
                            $modelFilmDirector = new \common\models\FilmDirectorBase();
                            $modelFilmDirector->film_id = $filmId;
                            $modelFilmDirector->director_id = $modelDirector->id;
                            $modelFilmDirector->created_time = date('Y-m-d H:i:s');
                            $modelFilmDirector->updated_time = date('Y-m-d H:i:s');
                            $modelFilmDirector->save(false);
                        }
                    }else{
                        $logger->LogInfo(date('Y-m-d H:i:s').' Save director:'.$directors.' ID:'.$director->id);
                        $modelFilmDirector = new \common\models\FilmDirectorBase();
                        $modelFilmDirector->film_id = $filmId;
                        $modelFilmDirector->director_id = $director->id;
                        $modelFilmDirector->created_time = date('Y-m-d H:i:s');
                        $modelFilmDirector->updated_time = date('Y-m-d H:i:s');
                        $modelFilmDirector->save(false);
                    }
                $logger->LogInfo(date('Y-m-d H:i:s').' SUCCESS save director');
            }
        }catch (\Exception $e){
            $logger->LogInfo(date('Y-m-d H:i:s').' ERROR save director');
        }

        return;

    }
    public function addActor($filmId,$actorIds=array())
    {
        // addActor
        $logger = new KLogger('logs'.DS.'MclipServiceCMC_Film'.DS.'Film_'.date('Ymd'), KLogger::INFO);
        $logger->LogInfo(date('Y-m-d H:i:s').' BEGIN addActor: Film ID'.$filmId.' Actor:'.json_encode($actorIds));
        try{
            if($actorIds){
                foreach($actorIds as $key =>$item){
                    $actor = \common\models\ActorBase::find()->where(['name'=>$item['name']])->one();
                    $logger->LogInfo(date('Y-m-d H:i:s').' Save actor:'.$item['name']);
                    if(!$actor){
                        $logger->LogInfo(date('Y-m-d H:i:s').' Save new actor:'.$item['name']);
                        $modelActor = new \common\models\ActorBase();
                        $modelActor->name = $item['name'];
                        $modelActor->created_time = date('Y-m-d H:i:s');
                        $modelActor->updated_time = date('Y-m-d H:i:s');
                        if($modelActor->save(false)){
                            $modelFilmActor = new \common\models\FilmActorBase();
                            $modelFilmActor->film_id = $filmId;
                            $modelFilmActor->actor_id = $modelActor->id;
                            $modelFilmActor->created_time = date('Y-m-d H:i:s');
                            $modelFilmActor->updated_time = date('Y-m-d H:i:s');
                            $modelFilmActor->save(false);
                        }
                    }else{
                        $logger->LogInfo(date('Y-m-d H:i:s').' Save actor:'.$actor->id);
                        $modelFilmActor = new \common\models\FilmActorBase();
                        $modelFilmActor->film_id        = $filmId;
                        $modelFilmActor->actor_id       = $actor->id;
                        $modelFilmActor->created_time   = date('Y-m-d H:i:s');
                        $modelFilmActor->updated_time   = date('Y-m-d H:i:s');
                        $modelFilmActor->save(false);
                    }
                }
                $logger->LogInfo(date('Y-m-d H:i:s').' SUCCESS addActor: Film ID'.$filmId);
            }
        }catch (\Exception $e){
            $logger->LogInfo(date('Y-m-d H:i:s').' ERROR save actor');
        }

        return;

    }


    public function addFilmClip($filmId,$uploadIds = array())
    {
        // Clip film
        $logger = new KLogger('logs'.DS.'MclipServiceCMC_Film'.DS.'Film_'.date('Ymd'), KLogger::INFO);
        $logger->LogInfo(date('Y-m-d H:i:s').' BEGIN save Clip film'.json_encode($uploadIds));
        try{
            if($uploadIds){
                foreach($uploadIds as $key =>$item){
                    $uploadId       =($item['UPLOAD_ID'])?$item['UPLOAD_ID']:0;
                    $order          =($item['order'])?$item['order']:0;
                    $clip = \common\models\ClipBase::find()->where(['upload_id'=>$uploadId])->one();
                    if($clip){
                        $logger->LogInfo(date('Y-m-d H:i:s').'Save film clip'.$filmId. '- '.$clip->id);
                        $filmClip = FilmClipBase::find()->where(['film_id'=>$filmId	,'clip_id'=>$clip->id])->one();
                        if(!$filmClip){
                            $modelFilmClip = new \common\models\FilmClipBase();
                            $modelFilmClip->clip_id = $clip->id;
                            $modelFilmClip->film_id = $filmId;
                            $modelFilmClip->order = $order;
                            $modelFilmClip->created_time = date('Y-m-d H:i:s');
                            $modelFilmClip->updated_time = date('Y-m-d H:i:s');
                            $modelFilmClip->save(false);
                        }else{
                            $logger->LogInfo(date('Y-m-d H:i:s').' Save to clip '.json_encode($clip));
                            $filmClip->order = $order;
                            $filmClip->updated_time = date('Y-m-d H:i:s');
                            $filmClip->save(false);
                        }
                    }else{
                        $logger->LogInfo(date('Y-m-d H:i:s').' Emty clip upload_id'.$uploadId);
                    }

                }
                $logger->LogInfo(date('Y-m-d H:i:s').' SUCCESS save Clip film');
            }
        }catch (\Exception $e){
            $logger->LogInfo(date('Y-m-d H:i:s').' ERROR save Clip film');
        }
        return;

    }
    /**
     * @param $name
     * @return int
     */
    public function findTargetMemberId($name)
    {
        $memberId = \common\models\CpBase::findOne(['name'=>$name]);
        if(!$memberId){
            $memberId = new \common\models\CpBase();
            $memberId->name = $name;
            $memberId->description = 'Created account from CMC';
            $memberId->status = 1;
            $memberId->created_time = date('Y-m-d H:i:s');
            $memberId->created_by = 1;
            $memberId->save(false);
        }
        return (!empty($memberId)) ? $memberId->id : 0;
    }
    /**
     * @param $name
     * @return int
     */
    public function findTargetMemberAdmin($name,$cp_id =0)
    {
        $memberId = \common\models\AdminBase::findOne(['username'=>$name]);
        if(!$memberId){
            $memberId = new \common\models\AdminBase();
            $memberId->username = $name;
            $memberId->cp_id = $cp_id;
            $memberId->status = 1;
            $memberId->created_time = date('Y-m-d H:i:s');
            $memberId->created_by = 1;
            $memberId->save(false);
        }
        return (!empty($memberId)) ? $memberId->id : 0;
    }

    /**
     * @param $originalId
     * @return int
     */
    public function findTargetSupportedSite($originalId)
    {
        $catMap = array(
            // Clip.vn
            1  => 1,
            // Youtube            
            2  => 2,
            // Nhaccuatui
            4  => 17,
            // Dailymotion
            5  => 4,
            // Metacafe
            6  => 3,
            // Blip
            7  => 15,            
            // Videojug
            8  => 8,
            // 24h
            9  => 9,
            // flickr
            10 => 10,
            // Veoh
            11 => 11,
            // Vimeo
            12 => 12,
            // Stupid videos
            13 => 13,
            // Myspace
            14 => 14,
            // Ngoisao
            15 => 6,
            // 5min
            16 => 7,
            // An ninh thu do
            17 => 16,
            // Vnexpress
            18 => 18,
			// Music Video sang am nhac
			69  => 4,
			74  => 4,
			75  => 4,
			78  => 71,
        );
        return isset($catMap[$originalId]) ? $catMap[$originalId] : 0;
    }

    /**
     * @param $originalId
     * @return int
     */
    public function findTargetCategory($originalId)
    {
        $catMap = array(
            // Phim anh
            16 => 48,
            // An thuc
            115 => 54,
            // Meo vat
            73 => 54,
            // Phong cach
            77 => 54,
            // Sao
            38 => 3,
            // TV show
            72 => 3,
            // Am nhac
            4 => 3,
            //AE
            5 => 11,
            // Tin nong
            1 => 69,
            // HAi
            6 => 4,
            // THieu nhi
            13 => 10,
            // the thao
            2 => 2,
            //Phim
            87 => 453,

        );
        return isset($catMap[$originalId]) ? $catMap[$originalId] : 0;
    }
    public function findTargetCategoryFilm($originalId)
    {
        $catMap = array(
            // Phim hành động
            2 => 75,
            //Phim Tâm Lý	1	Phim Tâm Lý	78
            1 => 81,
            //Phim Hài	3	Phim Hài	81
            3 => 72,
            //Phim Viễn Tưởng	5	Phim Viễn Tưởng	79
            5 => 78,
            //Phim Hoạt Hình	6	Phim Hoạt Hình	85
            6 => 84,
            //Phim Kinh Điển	7	Phim Kinh Điển	139
            7 => 87,
            //Phim Ma - Kinh Dị	8	Phim Ma - Kinh Dị	141
            8 => 213,
            //Phim cổ trang	9	Phim Cổ trang 	143
            9 => 240,

        );
        return isset($catMap[$originalId]) ? $catMap[$originalId] : 0;
    }
    public function findTargetCpid($originalId)
    {
        $catMap = array(
            31  =>2,
            161 =>271,
            41  =>37,
            9   =>52,
            10  =>55,
            145 =>56,
            14  =>57,
            15  =>59,
            68  =>64,
            66  =>69,
            57  =>71,
            7   =>74,
            67  =>75,
            210 =>337,
            216 =>359,
            79  =>114,
            80  =>115,
            224 =>373,
            226 =>379,
            227 =>381,
            228 =>383,
            137 =>385,
            230 =>387,
            157 =>389,
            104 =>135,
            163 =>391,
            209 =>393,
            207 =>395,
            211 =>397,
            116 =>147,
            117 =>148,
            120 =>149,
            131 =>168,
            134 =>173,
            135 =>175,
            139 =>191,
            151 =>233,
            149 =>239,
            148 =>241,
            153 =>247,
            155 =>255,
           

        );
        return isset($catMap[$originalId]) ? $catMap[$originalId] : 0;
    }

    public function findTargetCountryId($originalId)
    {
        $catMap = array(
            45  =>9,
            74	=>37,
            81	=>35,
            97	=>29,
            100	=>21,
            106	=>33,
            108	=>5,
            114	=>11,
            209	=>31,
            212	=>13,
            226	=>25,
            227	=>23,
            234	=>1,


        );
        return isset($catMap[$originalId]) ? $catMap[$originalId] : 0;
    }

    /**
     * @param $originalId
     * @return int
     */
    public function findTargetSpecId($originalId)
    {
        $catMap = array(
            1 => 1,
            2 => 2,
			3 => 3
        );
        return isset($catMap[$originalId]) ? $catMap[$originalId] : 0;
    }
    private static function sendApi($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_set));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}