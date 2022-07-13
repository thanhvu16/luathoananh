<?php
/**
 * @Author: trinh.kethanh@gmail.com
 * @Date: 27/03/2015
 * @Function: Hàm xử lý log của hệ thống
 * @System: Video 2.0
 */
namespace common\components;

use Yii;
use yii\log\FileTarget;
use yii\log\Logger;

class CLogSystem extends FileTarget {
    /**
     * @property array $logFormat Format log
     */
    public $logFormat = '';
    /**
     * Initialize component
     */
    public function init() {
        $this->logFile = $this->logFile.'.'.date('Ymd');
        parent::init();
        Yii::getLogger()->flushInterval = 1;
    }
    /**
     * Processes the given log messages.
     * This method will filter the given messages with [[levels]] and [[categories]].
     * And if requested, it will also export the filtering result to specific medium (e.g. email).
     * @param array $messages log messages to be processed. See [[Logger::messages]] for the structure
     * of each message.
     * @param boolean $final whether this method is called at the end of the current application
     */
    public function collect($messages, $final = false) {
        parent::collect($messages, true);
    }
    /**
     * Formats a log message for display as a string.
     * @param array $message the log message to be formatted.
     * The message structure follows that in [[Logger::messages]].
     * @return string the formatted message
     */
    public function formatMessage($message) {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            $text = implode('|', $text);
        }
        $formatArray = ['time', 'level', 'text'];
        $messageArray = [date('Y-m-d\TH:i:sO', $timestamp), "[$level]", "$text"];
        $messageAffterFormat = str_replace($formatArray, $messageArray, $this->logFormat);
        return $messageAffterFormat;
    }
}