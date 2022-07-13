<?php
/**
 * Throws when RemoteFileReader encounters an unexpected HTTP code.
 *
 * @since September 13, 2010
 * @edited April 02, 2015 by thanhtk@vega.com.vn
 */

namespace console\components\datachecker;

class UnexpectedHttpCodeException extends RemoteFileReaderException
{
    /**
     * Gets HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (int) curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
    }

    /**
     * Gets content type.
     *
     * @return string "text/html", "text/javascript"
     */
    public function getContentType()
    {
        return curl_getinfo($this->handle, CURLINFO_CONTENT_TYPE);
    }
}