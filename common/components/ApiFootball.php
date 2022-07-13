<?php

namespace common\components;


use yii\web\View;

class ApiFootball {

    private $logger = null;


    protected static $_instance = null;

    public function __construct()
    {
        $this->logger = new KLogger('logs'.DS.'get_api_data_'.date('Ymd'), KLogger::INFO);
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
            $this->logger = new KLogger('logs'.DS.'get_api_data_'.date('Ymd'), KLogger::INFO);
        }
    }

    public function getLeague($season=null){

        if(empty($season)){
            $url = 'https://api-football-v1.p.rapidapi.com/v2/leagues/current';
        }else{
            $url = 'https://api-football-v1.p.rapidapi.com/v2/leagues/season/'.$season;
        }
        $res = $this->getData($url);
        return $res;
    }


    public function getTeams($leagueId = null){
        $this->logger->LogInfo('========= get Teams ====');
        $path = '/sport/football/team';
        $data = array(
            'api_key' => $this->API_KEY,
            'leagueId' => $leagueId
        );
        if(!empty($leagueId)){
            $data['leagueId'] = $leagueId;
        }
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getMatches($date){
        $this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/schedule/basic';
        $data = array(
            'api_key' => $this->API_KEY,
            'date' => $date
        );
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getLivescore(){
        $this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/livescores';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getLiveChange(){
        $this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/livescores/changes';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getLineup($matchId = null){
        $this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/lineups';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        if(!empty($matchId)){
            $data['matchId'] = $matchId;
        }
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getEvents($date=null){
        $this->logger->LogInfo('========= get Event ====');
        $path = '/sport/football/events';
        $data = array(
            'api_key' => $this->API_KEY,
        );
        if(!empty($date)){
            $data['date'] = $date;
        }
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getMatchLive($date){
        $this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/schedule';
        $data = array(
            'api_key' => $this->API_KEY,
            'date' => $date
        );
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }

    public function getMatchLiveByLeague($leagueId){
        $this->logger->LogInfo('========= get MATCHES ====');
        $path = '/sport/football/schedule';
        $data = array(
            'api_key' => $this->API_KEY,
            'leagueId' => $leagueId
        );
        $url = $this->URL.$path;
        $this->logger->LogInfo('url: '.$url);
        $this->logger->LogInfo('data: '.json_encode($data));
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);
        $this->logger->LogInfo('res : '.json_encode($res));

        return $res;
    }


    public function getCountry(){
        $url = 'https://api-football-v1.p.rapidapi.com/v2/countries';
        $res = $this->getData($url);
        return $res;
    }

    public function getStats($date = null){
        $path = '/sport/football/stats';
        $data = array(
            'api_key' => $this->API_KEY,
            'date' => empty($date)?date('Y-m-d'):$date,
        );
        $url = $this->URL.$path;
        $res = Utility::curlSendGet($url, $data);
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
        $res = Utility::curlSendGet($url, $data);
        $res = json_decode($res, true);

        return $res;
    }

    private function getData($url){
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: api-football-v1.p.rapidapi.com",
                "x-rapidapi-key: cd26b9b5b6msh38a4ff1a98bebd3p1cddc3jsn08c9213fb3fc"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        } else {
            return json_decode($response, true);
        }
    }
}