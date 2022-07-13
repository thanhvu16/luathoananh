<?php
namespace wap\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class TopPostWidget extends Widget{
    public $message;

    public function init(){
        parent::init();
        if($this->message===null){
            $this->message= 'Welcome User';
        }else{
            $this->message= 'Welcome '.$this->message;
        }
    }

    public function run(){
        return Html::encode($this->message);
    }
}
?>