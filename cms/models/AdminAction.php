<?php
/**
 * @Function: Lớp xử lý các chức controller & action của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 15/01/2015
 * @System: Video 2.0
 */
namespace cms\models;

use Yii;
use yii\helpers\Html;
use common\models\AdminActionBase;
use common\models\AdminControllerBase;

class AdminAction extends AdminActionBase
{

    /**
     * @param $controllerName
     * @return string
     */
    public static function getControllerID($controllerName)
    {
        $result = AdminControllerBase::find()->select('id')->where(['controller' => $controllerName])->one();

        if (!empty($result)) {
            return $result->id;
        } else {
            return null;
        }
    }

    /**
     * @param $controllerName
     * @return array
     */
    public static function rawDesc($controllerName)
    {
        $result = [];

        $model = AdminActionBase::find()->select(['action', 'desc'])->where('admin_controller_id = :ID', [':ID' => self::getControllerID($controllerName)])->all();
        if (!empty($model)) {
            foreach ($model as $data) {
                $result[$controllerName.$data->action] = $data->desc;
            }
        }

        return $result;
    }

    /**
     * @param $actionName
     * @param $controllerName
     * @return mixed
     */
    public static function parseDesc($actionName, $controllerName)
    {
        $key = $controllerName.$actionName;
        $data = self::rawDesc($controllerName);
        if (isset($data[$key])) {
            return $data[$key];
        }
    }

    /**
     * @param $controllerName
     * @return array
     */
    public static function rawID($controllerName)
    {
        $result = [];

        $model = AdminActionBase::find()->select(['id', 'action'])->where('admin_controller_id = :ID', [':ID' => self::getControllerID($controllerName)])->all();
        if (!empty($model)) {
            foreach ($model as $data) {
                $result[$controllerName.$data->action] = $data->id;
            }
        }

        return $result;
    }

    /**
     * @param $actionName
     * @param $controllerName
     * @return mixed
     */
    public static function parseID($actionName, $controllerName)
    {
        $key = $controllerName.$actionName;
        $data = self::rawID($controllerName);
        if (isset($data[$key])) {
            return $data[$key];
        }
    }

    /**
     * @param $name
     * @param null $selection
     * @param array $items
     * @param array $options
     * @param array $params
     * @return string
     */
    public static function checkboxListCustomized($name, $selection = null, $items = [], $options = [], $params = [])
    {
        if (substr($name, -2) !== '[]') {
            $name .= '[]';
        }

        $formatter = isset($options['item']) ? $options['item'] : null;
        $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : [];
        $encode = !isset($options['encode']) || $options['encode'];
        $lines = [];
        $index = 0;
        foreach ($items as $value => $label) {
            $checked = $selection !== null &&
                (!is_array($selection) && !strcmp($value, $selection)
                    || is_array($selection) && in_array($value, $selection));
            if ($formatter !== null) {
                //$lines[] = call_user_func($formatter, $index, $label, $name, $checked, $value, $params);
                $lines[] = call_user_func($formatter, $index, $label, $name, $checked, $value, $params);
            } else {
                $lines[] = Html::checkbox($name, $checked, array_merge($itemOptions, [
                    'value' => $value,
                    'label' => $encode ? Html::encode($label) : $label,
                    //'label' => $encode ? Html::encode($label) : $label,
                ]));
            }
            $index++;
        }

        if (isset($options['unselect'])) {
            // add a hidden field so that if the list box has no option being selected, it still submits a value
            $name2 = substr($name, -2) === '[]' ? substr($name, 0, -2) : $name;
            $hidden = Html::hiddenInput($name2, $options['unselect']);
        } else {
            $hidden = '';
        }
        $separator = isset($options['separator']) ? $options['separator'] : "\n";

        $tag = isset($options['tag']) ? $options['tag'] : 'div';
        unset($options['tag'], $options['unselect'], $options['encode'], $options['separator'], $options['item'], $options['itemOptions']);

        return $hidden . Html::tag($tag, implode($separator, $lines), $options);
    }
}