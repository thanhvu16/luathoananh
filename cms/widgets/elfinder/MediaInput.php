<?php

namespace cms\widgets\elfinder;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

// File input widget
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;

// popup
use yii\bootstrap\Modal;

// assets
use mihaildev\elfinder\AssetsCallBack;

class MediaInput extends InputWidget{

    public $language;
    public $filter;

    public $buttonTag = 'button';
    public $buttonName = 'Browse';
    public $buttonOptions = [];

    protected $_managerOptions;

    public $width = 'auto';
    public $height = 'auto';

    public $template = '<div class="row" style="margin-bottom: 10px">{image}</div><div style="text-align: right;">{button}</div>';

    public $controller = 'elfinder';

    public $path; // work with PathController

    public $multiple = true;
    public $imagePreviews = [];

    public function init()
    {
        parent::init();

        if(empty($this->language))
            $this->language = ElFinder::getSupportedLanguage(Yii::$app->language);

        if(empty($this->buttonOptions['id']))
            $this->buttonOptions['id'] = $this->options['id'].'_button';

        $this->buttonOptions['type'] = 'button';
        $this->buttonOptions['class'] = 'btn btn-primary';

        $managerOptions = [];
        if(!empty($this->filter))
            $managerOptions['filter'] = $this->filter;

        $managerOptions['callback'] = $this->options['id'];

        if(!empty($this->language))
            $managerOptions['lang'] = $this->language;

        if (!empty($this->multiple))
            $managerOptions['multiple'] = $this->multiple;

        if(!empty($this->path))
            $managerOptions['path'] = $this->path;

        $this->_managerOptions['url'] = ElFinder::getManagerUrl($this->controller, $managerOptions);
        $this->_managerOptions['width'] = $this->width;
        $this->_managerOptions['height'] = $this->height;
        $this->_managerOptions['id'] = $this->options['id'];
        $this->multiple = true;
    }

    /**
     * Runs the widget.
     */
    public function run()
    {

        $hidden = $this->value ? '' : 'display:none;';
        $replace['{image}'] = '<div id="wrap_image_' . $this->options['id'] . '">';
        if(!empty($this->imagePreviews)){
            foreach ($this->imagePreviews as $key => $imagePreview){
                $replace['{image}'] .='<div class="col-sm-3">';
                $replace['{image}'] .='<input type="hidden" name="'.$this->name.'['.$key.'][src]" value="'.$imagePreview['src'].'" />';
                $replace['{image}'] .='<img src="'.$imagePreview['src'].'" style="max-width: 100%;" >';
                $replace['{image}'] .='<input class="form-control" type="text" name="'.$this->name.'['.$key.'][alt]" value="'.$imagePreview['alt'].'" />';
                $replace['{image}'] .='</div>';
            }
        }
        $replace['{image}'] .='</div>';
        $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
        $replace['{button}'] = Html::tag($this->buttonTag,$this->buttonName, $this->buttonOptions);

        echo strtr($this->template, $replace);

        // Publish assets
        AssetsCallBack::register($this->getView());


        $this->getView()->registerJs("
        mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ",
            function(files, id){
                var _f = [];
                var html = '';
                for (var i in files) { 
                    html = '';
                    html += '<div class=\"col-sm-3\">';
                    html += '<input type=\"hidden\" name=\"".$this->name."['+i+'][src]\" value=\"'+files[i].url+'\" />';
                    html += '<img src=\"'+files[i].url+'\" style=\"max-width: 100%;\" >';
                    html += '<input class=\"form-control\" type=\"text\" name=\"".$this->name."['+i+'][alt]\" value=\"\" />';
                    html += '</div>';
                    _f.push(files[i].url); 
                    $('#wrap_image_" . $this->options['id']."').append(html);
                }
                
                return true;
            });
        $(document).on('click','#" . $this->buttonOptions['id'] . "',
            function(){
                mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");
            }
        );");


    }
}