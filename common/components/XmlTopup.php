<?php
namespace common\components;

class XmlTopup
{
//    const BASE_URL = 'http://125.212.227.196:7373/api';
//    const BASE_URL = 'http://192.168.158.46:8181/api';
    const BASE_URL = 'http://123.30.233.187:7373/api';

    const USERNAME = 'ivrdata';

    const PASSWORD = 'xkP410CNyPtTc3d7cIx7';

    /**
     * user name
     * @var string
     */
    private $timeout = 30;
    /**
     * user name
     * @var string
     */
    private $telco = 0;
    /**
     * user name
     * @var string
     */
    private $services_name = "RINGRING";


    /**
     * @param $services_name
     * @throws Exception
     */
    protected function setServicesName($services_name){
        if(empty($services_name)) throw new \Exception('Services_id can not be empty', E_USER_WARNING);
        $this->services_name = $services_name;
    }

    /**
     * @return string
     */
    protected function getServicesName(){
        return $this->services_name;
    }

    /**
     * @param $telco
     * @throws Exception
     */
    protected function setTelco($telco){
        if(empty($telco)) {
            $this->telco = 0;
        }else $this->telco = $telco;
    }

    /**
     * @return string
     */
    protected function getTelco(){
        return $this->telco;
    }
    public function setTimeOut($time_out){
        if(empty($time_out)) throw new \Exception('Timeout can not be empty', E_USER_WARNING);
        $this->timeout = $time_out;
    }

    public function __construct($services_name, $telco)
    {
        $this->setServicesName($services_name);
        $this->setTelco((int) $telco);
    }

    /**
     * @param $name
     * @param $arguments
     * @return SimpleXMLElement
     * @throws Exception
     */


    public function callRequest($name, $arguments)
    {
        $data = $this->request($this->buildUrl($name, $arguments));
        $xml = json_decode($data);
        if($xml === false) throw new \Exception('Invalid XML', E_USER_WARNING);
        return $xml;
    }

    protected function buildUrl($name, $params){
        $url = self::BASE_URL . "/" . $name  . '?limit=2000&username=' . self::USERNAME . '&password=' . self::PASSWORD . '&serviceName=' .$this->getServicesName() . '&telco=' . $this->getTelco();
        foreach ($params as $key => $value){
            $url .= "&" . $key . '=' . $value;
        }
        var_dump($url);
        return $url;
    }

    /**
     * @param $url
     * @return mixed
     * @throws XMLSoccerException
     */
    protected function request($url){
        try {
            $curl = curl_init();

            // Check if initialization had gone wrong*
            if ($curl === false) {
                throw new \Exception('failed to initialize curl');
            }

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

            $data = curl_exec($curl);

            // Check the return value of curl_exec(), too
            if ($data === false) {
                throw new \Exception(curl_error($curl), curl_errno($curl));
            }

            $cerror = curl_error($curl);
            $cerrno = curl_errno($curl);
            var_dump($data);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            var_dump($http_code);

            curl_close($curl);

            if( $cerrno != 0 ) throw new \Exception($cerror,E_USER_WARNING);
            if($http_code == 200 ){

                return $data;
            }
            throw new \Exception($http_code .' - '. $data . "\nURL: " . $url);


        } catch(\Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        }





        /*$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);*/
        /*if(!empty($this->requestIp)){
            curl_setopt($curl, CURLOPT_INTERFACE,$this->requestIp);
        }*/
        /*$data = curl_exec($curl);
        $cerror=curl_error($curl);
        $cerrno=curl_errno($curl);
        var_dump($data);
        $http_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
        var_dump($http_code);
        curl_close($curl);

        if($cerrno!=0) throw new \Exception($cerror,E_USER_WARNING);
        if($http_code == 200 ){

            return $data;
        }
        throw new \Exception($http_code .' - '. $data . "\nURL: " . $url);*/
    }
}