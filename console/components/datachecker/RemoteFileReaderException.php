<?php

/**
 * Throws when RemoteFileReader encounters an error.
 * 
 * @since September 01, 2010
 * @edited Apirl 02, 2015 by thanhtk@vega.com.vn
 * @version $Id: RemoteFileReaderException.php 8212 2010-09-13 04:52:20Z pcdinh $
 */
namespace console\components\datachecker;


use yii\console\Exception;

class RemoteFileReaderException extends Exception
{
    /**
     * Curl handle.
     *
     * @var resource
     */
    public $handle;
    
    /**
     * URL to fetch.
     *
     * @var string
     */
    public $url;     

    /**
     * Sets Curl handle.
     *
     * @param resource $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }    

    /**
     * Sets Curl handle.
     *
     * @param resource $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }
    
    /**
     * Gets error message from Curl handle.
     * 
     * @return string
     */
    public function getHandleError()
    {
        return curl_error($this->handle);
    }   
}