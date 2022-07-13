<?php
/**
 * @Author: trinh.kethanh@gmail.com
 * @Date: 03/02/2015
 * @Function: Class xử lý phần ngôn ngữ của hệ thống
 * @System: Video 2.0
 */
namespace common\components;

use yii\bootstrap\Tabs;
use zxbodya\yii2\tinymce\TinyMce;
use zxbodya\yii2\elfinder\TinyMceElFinder;

class Language {
    /*
     * @params: $model -> model của form
     * @params: $form -> $form = ActiveForm::begin()
     * @params: $field -> Trường cần tạo tabs
     * @params: $type -> Kiểu cần tạo, mặc định là input
     * @params: $options -> Hiệu chỉnh form
     * @function: Hàm này tạo multi tabs cho những trường nhiều ngôn ngữ
     */
    public static function languageTabs($model, $form, $label, $field, $type = 'input', $options = []) {
        $languages = CFunction::getParams('languages');
        $items = [];
        foreach($languages as $language) {
            $arrayTabs = [
                'label' => $language['name'],
                'content' => $form->field($model, $field.'_'.$language['id'])->input($field)->label(false),
                'active' => ($language['id'] == 1) ? true : false
            ];
            switch ($type) {
                case 'textarea':
                    $arrayTabs['content'] = $form->field($model, $field.'_'.$language['id'])->textarea($options)->label(false);
                    break;
                case 'textInput':
                    $arrayTabs['content'] = $form->field($model, $field.'_'.$language['id'])->textInput()->label(false);
                    break;
                case 'tinymce':
                    $arrayTabs['content'] = $form->field($model, $field.'_'.$language['id'])->label(false)->widget(
                        TinyMce::className(),
                        [
                            'options' => $options,
                            'fileManager' => [
                                'class' => TinyMceElFinder::className(),
                                'connectorRoute' => 'el-finder/connector'
                            ]
                        ]
                    );
                    break;
                default:
                    $arrayTabs['content'] = $form->field($model, $field.'_'.$language['id'])->input($field)->label(false);

            }
            $items['items'][] = $arrayTabs;
        }
        return '<label class="control-label">' . $label . '</label>' . Tabs::widget($items);
    }
    /*
     * @function: Hàm này trả về ngôn ngữ đang sử dụng của hệ thống
     */
    public static function language() {
       return 1;
    }
}