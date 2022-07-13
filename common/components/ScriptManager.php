<?php

namespace common\components;

use common\components\script\Telco;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Contains all scripts that is called from config
 * Class ScriptManager
 * @package common\components
 */
class ScriptManager extends Component {
    /**
     * Script container
     * @var array
     */
    protected $scripts = [];

    /**
     * @see common configs of yii2 to set script
     * @param array $config
     * @throws InvalidConfigException
     */
    public function setScripts($config)
    {
        foreach ($config as $name=>$params) {
            $this->scripts[$name] = \Yii::createObject($params);
            if(!($this->scripts[$name] instanceof Telco))
                throw new InvalidConfigException('Invalid config script');
        }
    }

    /**
     * Get script by its name
     * @param $name
     * @return null
     */
    public function getScripts($name) {
        return isset($this->scripts[$name]) ? $this->scripts[$name] : null;
    }
}