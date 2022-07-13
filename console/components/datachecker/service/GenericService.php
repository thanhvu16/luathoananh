<?php

namespace console\components\datachecker\service;

abstract class GenericService
{
    /**
     * @var
     */
    public $logger;

    /**
     * Constructs an object of GenericService
     */
    public function __construct()
    {

    }

    /**
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $payload
     * @return mixed
     */
    public abstract function process($payload);
}