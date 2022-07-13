<?php

/**
 * Reads the remote URL and retrieve the content that is returned.
 *
 * @since September 01, 2010
 * @edited April 02, 2015 by thanhtk@vega.com.vn
 * @version $Id: RemoteFileReader.php 8578 2010-10-01 03:59:50Z pcdinh $
 */
namespace console\components\datachecker;

use RuntimeException;
use InvalidArgumentException;

class RemoteFileReader
{
    /**
     * Curl handle.
     *
     * @var resource
     */
    public $handle;

    /**
     * File pointer used by this class to save the remote content into a local file.
     *
     * @var resource
     */
    public $fp;

    /**
     * Constructs an object of <code>RemoteFileReader</code>
     */
    public function __construct()
    {

    }

    /**
     * Saves the remote file into the local file system.
     *
     * @throws RuntimeException
     * @throws RemoteFileReaderException
     * @param string $url
     * @param string $savePath
     * @param array $opts Keys: ua (user agent), binary, referer
     * @return RemoteFileRetrievalSummary
     */
    public function readAndSave($url, $savePath, $opts = array())
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_USERAGENT, isset($opts['ua']) ? $opts['ua'] : "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT, 10);

        if (isset($opts['timeout']))
        {
            curl_setopt($this->handle, CURLOPT_TIMEOUT, $opts['timeout']);
        }

        // Available since PHP 5.3.0
        // curl_setopt ($ch, CURLOPT_FAILONERROR, true);

        if (isset($opts['binary']))
        {
            curl_setopt($this->handle, CURLOPT_BINARYTRANSFER, $opts['binary']);
        }

        if (!isset($opts['referer']))
        {
            curl_setopt($this->handle, CURLOPT_REFERER, "http://internal.clip.vn");
        }

        $fp = true;

        // Checks base directories
        if (false === file_exists(dirname($savePath)))
        {
            mkdir(dirname($savePath), 0777, true);
        }

        $this->fp = fopen($savePath, "w+");

        if (is_resource($this->fp))
        {
            curl_setopt($this->handle, CURLOPT_FILE, $this->fp);
        }
        else
        {
            throw new RuntimeException(sprintf('Unable to create the local file to save the remote content "%s".', $url));
        }

        $success = curl_exec($this->handle);
        $error = curl_error($this->handle);

        fclose($this->fp);

        if (false === $success)
        {
            unlink($savePath);
            $e = new RemoteFileReaderException(sprintf('Unable to download file "%s" due to the following Curl error "%s"', $url, curl_error($this->handle)));
            $e->setHandle($this->handle);
            $e->setUrl($url);
            throw $e;
        }

        $httpCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);

        if ($httpCode != 200)
        {
            $success = false;
        }

        if (false === $success)
        {
            unlink($savePath);
            $e = new UnexpectedHttpCodeException(sprintf('Unable to download file "%s" due to an unexpected HTTP code "%s"', $url, curl_getinfo($this->handle, CURLINFO_HTTP_CODE)));
            $e->setHandle($this->handle);
            $e->setUrl($url);
            throw $e;
        }

        return new RemoteFileRetrievalSummary($this->handle);
    }

    /**
     * Retrieve the content of the remote URL.
     *
     * @throws RemoteFileReaderException
     * @throws InvalidArgumentException
     * @param string $url
     * @param array $opts Keys: ua (user agent), binary, referer
     * @return string
     */
    public function read($url, $opts = array())
    {
        if (empty($url))
        {
            throw new InvalidArgumentException('The $url is empty');
        }

        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_USERAGENT, isset($opts['ua']) ? $opts['ua'] : "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_CONNECTTIMEOUT, 10);

        if (isset($opts['timeout']))
        {
            curl_setopt($this->handle, CURLOPT_TIMEOUT, $opts['timeout']);
        }

        // Available since PHP 5.3.0
        // curl_setopt ($ch, CURLOPT_FAILONERROR, true);

        if (isset($opts['binary']))
        {
            curl_setopt($this->handle, CURLOPT_BINARYTRANSFER, $opts['binary']);
        }

        if (!isset($opts['referer']))
        {
            curl_setopt($this->handle, CURLOPT_REFERER, "http://internal.clip.vn");
        }

        $rs = curl_exec($this->handle);

        if (false === $rs)
        {
            $e = new RemoteFileReaderException(sprintf('Unable to download file "%s" due to the following Curl error "%s"', $url, curl_error($this->handle)));
            $e->setHandle($this->handle);
            $e->setUrl($url);
            throw $e;
        }

        $httpCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);

        if ($httpCode != 200)
        {
            $e = new UnexpectedHttpCodeException(sprintf('Unable to download file "%s" due to the an unexpected HTTP code "%s"', $url, curl_getinfo($this->handle, CURLINFO_HTTP_CODE)));
            $e->setHandle($this->handle);
            $e->setUrl($url);
            throw $e;
        }

        return $rs;
    }

    /**
     * Closes open resources.
     */
    public function close()
    {
        if (is_resource($this->fp))
        {
            fclose($this->fp);
        }

        if (is_resource($this->handle))
        {
            curl_close($this->handle);
        }
    }
}

/**
 * Class to provide summary information after downloading files.
 *
 * @since Oct 01, 2010
 */
class RemoteFileRetrievalSummary
{
    /**
     * Curl handle.
     *
     * @var resource
     */
    public $handle;

    /**
     * Contructs an object of <code>RemoteFileRetrievalSummary</code>
     *
     * @param <type> $handle +
     */
    public function __construct($handle)
    {
        $this->handle = $handle;
    }

    /**
     * Gets total bytes of the file that was downloaded.
     *
     * @return int
     */
    public function getFileSize()
    {
        return curl_getinfo($this->handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    }

    /**
     * Gets total time (in seconds) that was taken to download the file.
     *
     * @return int
     */
    public function getTotalDownloadTime()
    {
        return curl_getinfo($this->handle, CURLINFO_TOTAL_TIME);
    }

    /**
     * Gets average download speed.
     *
     * @return int
     */
    public function getAverageDownloadSpeed()
    {
        return curl_getinfo($this->handle, CURLINFO_SPEED_DOWNLOAD);
    }
}
