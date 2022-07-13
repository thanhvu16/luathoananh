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

class InputFile extends InputWidget{

    public $language;
    public $filter;

    public $buttonTag = 'button';
    public $buttonName = 'Browse';
    public $buttonOptions = [];

    protected $_managerOptions;

    public $width = 'auto';
    public $height = 'auto';

    public $template = '<div class="" style="margin-bottom: 10px">{image}</div><div class="input-group">{input}<span class="input-group-btn">{button}</span></div>';

    public $controller = 'elfinder';

    public $path; // work with PathController

    public $multiple;

    public function init()
    {
        parent::init();

        if(empty($this->language))
            $this->language = ElFinder::getSupportedLanguage(Yii::$app->language);

        if(empty($this->buttonOptions['id']))
            $this->buttonOptions['id'] = $this->options['id'].'_button';

        $this->buttonOptions['type'] = 'button';

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
    }

    /**
     * Runs the widget.
     */
    public function run()
    {

        // Print elfinder widget in modal
        Modal::begin([
            //'header' => '<h2>Hello world</h2>',
            'toggleButton' => false,
            'id' => $this->options['id'] . '-dialog',
            'size' => Modal::SIZE_LARGE,
        ]);

        echo ElFinder::widget([
            'language'         => $this->language,
            'controller'       => $this->controller, // вставляем название контроллера, по умолчанию равен elfinder
            'path' => $this->path, // будет открыта папка из настроек контроллера с добавлением указанной под деритории
            'filter'           => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
            'callbackFunction' => new JsExpression('function(file, id){
                console.log( file.url, "'.$this->buttonOptions['id'].'" );
                $("#' . $this->options['id'] . '").val( file.url );
                $("#' . $this->options['id'] . '-thumb").attr("src", file.url ).show();
                $("#' . $this->options['id'] . '-dialog").modal("hide");
            }'), // id - id виджета
            'frameOptions' => ['style' => 'width: 100%; height: 500px; border: 0px;']
        ]);

        Modal::end();

        // Render input and upload button
        if ($this->hasModel()) {
            $attr = $this->attribute;
            $hidden = $this->model->$attr ? '' : 'display:none;';
            $replace['{image}'] = '<img id="' . $this->options['id'] . '-thumb" class="thumbnail" src="'.$this->model->$attr.'" style="max-width: 150px; max-height: 150px; '.$hidden.'" />';
            $replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $hidden = $this->value ? '' : 'display:none;';
            $replace['{image}'] = '<div id="wrap_image_' . $this->options['id'] . '"><img id="' . $this->options['id'] . '-thumb" src="'.$this->value.'"  style="max-width: 100%; '.$hidden.'" /></div>';
            $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
        }

        $replace['{button}'] = Html::tag($this->buttonTag,$this->buttonName, $this->buttonOptions);

        echo strtr($this->template, $replace);

        // Publish assets
        AssetsCallBack::register($this->getView());

        if (!empty($this->multiple)){

            $this->getView()->registerJs("
            mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ",
                function(files, id){
                    var _f = [];
                    for (var i in files) { 
                        _f.push(files[i].url); 
                        $('#wrap_image_" . $this->options['id']."').append('<div class=\"col-sm-3\"><img src=\"'+files[i].url+'\" style=\"max-width: 100%;\" ></div>');
                    }
                    \$('#' + id).val(_f.join(', ')).trigger('change');
                    return true;
                });
            $(document).on('click','#" . $this->buttonOptions['id'] . "',
                function(){
                    mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");
                }
            );");

        } else {
            $this->getView()->registerJs("
                mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ", function(file, id){
                    \$('#' + id).val(file.url).trigger('change');
                    $('#".$this->options['id'] ."-thumb').attr('src', file.url ).show();
                    $('#".$this->options['id'] ."-dialog').modal('hide');
                    return true;
                });
                $(document).on('click',
                    '#" . $this->buttonOptions['id'] . "',
                    function(){
                        mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");
                        //$('#" . $this->options['id'] . "-dialog').modal('show');
                    }
                );");
        }
    }
}