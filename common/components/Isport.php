<?php

namespace common\components;


use common\models\LeagueBase;
use yii\web\View;

class Isport {

    //protected $API_KEY = 'MSMgktAzpzA9m8vS';
    protected $API_KEY = 'NAWb91RYV5wCCqO3';
    protected $URL = 'http://api.isportsapi.com';

    private $logger = null;


    protected static $_instance = null;

    public function __construct()
    {
        //$this->logger = new KLogger('logs'.DS.'get_isport_data_'.date('Ymd'), KLogger::INFO);
    }

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function init(){
        if(empty($this->logger)){
            //$this->logger = new KLogger('logs'.DS.'get_isport_data_'.date('Ymd'), KLogger::INFO);
        }
    }

    public function getLeague($leagueId = null){
        //$this->logger->LogInfo('========= get League ====');
        $path = '/sport/football/league';
        $data = array(
            'api_key' => $this->API_KEY
        );
        if(!empty($leagueId)){
            $data['leagueId'] = $leagueId;
        }
        $url = $this->URL.$path;
        ////$this->logger->LogInfo('url: '.$url);
        ////$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getTeams($leagueId = null){
        ////$this->logger->LogInfo('========= get Teams ====');
        $path = '/sport/football/team';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        if(!empty($leagueId)){
            $data['leagueId'] = $leagueId;
        }
        $url = $this->URL.$path;
        ////$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getMatches($date){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/schedule/basic';
        $data = array(
            'api_key' => $this->API_KEY,
            'date' => $date
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getLivescore(){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/livescores';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getLiveChange(){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/livescores/changes';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getLineup($matchId = null){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/lineups';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        if(!empty($matchId)){
            $data['matchId'] = $matchId;
        }
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getEvents($date=null){
        //$this->logger->LogInfo('========= get Event ====');
        $path = '/sport/football/events';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        if(!empty($date)){
            $data['date'] = $date;
        }
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getMatchLive($date){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/schedule';
        $data = array(
            'api_key' => $this->API_KEY,
            'date' => $date
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getMatchLiveByLeague($leagueId){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/schedule';
        $data = array(
            'api_key' => $this->API_KEY,
            'leagueId' => $leagueId
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getCountry(){
        $path = '/sport/football/country';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getStats($date = null){
        $path = '/sport/football/stats';
        $data = array(
            'api_key' => $this->API_KEY,
            'date' => empty($date)?date('Y-m-d'):$date,
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }


    public function getOdds(){
        $path = '/sport/football/odds/main';
        $data = array(
            'api_key' => $this->API_KEY,
            'companyId' => 24 //nha cai 12bet
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getOddsTotalGoal($inPlay=false){
        $path = '/sport/football/odds/totalgoals/prematch';
        if($inPlay) {
            $path = '/sport/football/odds/totalgoals/inplay';
        }

        $data = array(
            'api_key' => $this->API_KEY,
            //'companyId' => 24 //nha cai 12bet
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getOddsCornersTotal($inPlay=false){
        $path = '/sport/football/odds/cornerstotal/prematch';
        if($inPlay) {
            $path = '/sport/football/odds/cornerstotal/inplay';
        }

        $data = array(
            'api_key' => $this->API_KEY,
           // 'companyId' => 24 //nha cai 12bet
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getOddsCornersHandicap($inPlay=false){
        $path = '/sport/football/odds/cornershandicap/prematch';
        if($inPlay) {
            $path = '/sport/football/odds/cornershandicap/inplay';
        }
        $data = array(
            'api_key' => $this->API_KEY,
           // 'companyId' => 24 //nha cai 12bet
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getOddsHalfFull($inPlay=false){
        $path = '/sport/football/odds/halffull/prematch';
        if($inPlay){
            $path = '/sport/football/odds/halffull/inplay';
        }
        $data = array(
            'api_key' => $this->API_KEY,
           // 'companyId' => 24 //nha cai 12bet
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getOddsOddEven($inPlay=false){
        $path = '/sport/football/odds/oddeven/prematch';
        if($inPlay){
            $path = '/sport/football/odds/oddeven/inplay';
        }
        $data = array(
            'api_key' => $this->API_KEY,
           // 'companyId' => 24 //nha cai 12bet
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getOddScore($inPlay=false){
        //$this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/odds/score/prematch';
        if($inPlay) {
            $path = '/sport/football/odds/score/inplay';
        }

        $data = array(
            'api_key' => $this->API_KEY,
           // 'companyId' => 24 //nha cai 12bet
        );

        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getStandings($leagueId, $type){
        if($type == LeagueBase::TYPE_LEAGUE){
            $path = '/sport/football/standing/league';
        }else{
            $path = '/sport/football/standing/cup';
        }

        $data = array(
            'api_key' => $this->API_KEY,
            'leagueId' => $leagueId
        );

        $url = $this->URL.$path;
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    public function getTopsscore($leagueId, $season=''){
        //$this->logger->LogInfo('========= get Event ====');
        $path = '/sport/football/topscorer';
        $data = array(
            'api_key' => $this->API_KEY,
            'leagueId' => $leagueId,
        );
        if(!empty($season)){
            $data['season'] = $season;
        }
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data, [], true);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getFifaRanking(){
        //$this->logger->LogInfo('========= get Event ====');
        $path = '/sport/football/fifaranking';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getPlayerstats($leagueId){
        //$this->logger->LogInfo('========= get Event ====');
        $path = '/sport/football/playerstats/league';
        $data = array(
            'api_key' => $this->API_KEY,
            'leagueId' => $leagueId,
        );

        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data, [], true);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getPlayers($teamId){
        //$this->logger->LogInfo('========= get Event ====');
        $path = '/sport/football/player';
        $data = array(
            'api_key' => $this->API_KEY,
            'teamId' => $teamId,
        );
        $url = $this->URL.$path;
        //$this->logger->LogInfo('url: '.$url);
        //$this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGetIsport($url, $data);
        $res = json_decode($res, true);
        //$this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }
}