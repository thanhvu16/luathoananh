<?php

namespace common\components;

use Yii;

class Theme extends \yii\base\Theme
{
	public $active;

	/**
	 * @see yii\base\Theme::init()
	 */
	public function init()
	{
		parent::init();

		if ($this->baseUrl === null) {
			$this->baseUrl = '@web/themes/'.$this->active;
		}
		$this->baseUrl = rtrim(Yii::getAlias($this->baseUrl), '/');

		if ($this->basePath === null) {
			$this->basePath = '@webroot/themes/'.$this->active;
		}
		$this->basePath = Yii::getAlias($this->basePath);

		if (empty($this->pathMap)) {
			$this->pathMap = [Yii::$app->getBasePath() => [$this->basePath]];
		}
	}
}