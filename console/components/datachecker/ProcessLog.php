<?php

/**
 * Logs process messages.
 *
 * @since September 03, 2010
 * @edited April 02, 2015 by thanhtk@vega.com.vn
 */

namespace console\components\datachecker;

class ProcessLog
{
    /**
     * Set of files that are used to log message.
     *
     * @var array
     */
    public $file;

    /**
     * Constructs an object <code>ProcessLog</code>.
     *
     * @param array $file: error, info, exception
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Logs exception.
     * 
     * @param \Exception $e
     */
    public function exception($e)
    {
        error_log(sprintf('%s [%s] [%s] %s', date('Y-m-d\TH:i:s'), getmypid(), 'EXCEPTION', get_class($e) . ':"' . $e->getMessage() . "\"\n"), 3, $this->file['exception']);
        error_log(sprintf('%s [%s] [%s] %s', date('Y-m-d\TH:i:s'), getmypid(), 'EXCEPTION_TRACE', get_class($e) . ':"' . $e->getTraceAsString() . "\"\n"), 3, $this->file['exception']);
    }

    /**
     * Logs warning message.
     *
     * @param string $message
     */
    public function warn($message)
    {
        error_log(sprintf('%s [%s] [%s] %s', date('Y-m-d\TH:i:s'), getmypid(), 'WARN', $message . "\n"), 3, $this->file['warn']);
    }

    /**
     * Logs information message.
     *
     * @param string $message
     */
    public function info($message)
    {
        error_log(sprintf('%s [%s] [%s] %s', date('Y-m-d\TH:i:s'), getmypid(), 'INFO', $message . "\n"), 3, $this->file['info']);
    }
}