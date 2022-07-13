<?php

namespace cms\models;

use Codeception\Lib\Interfaces\ActiveRecord;
use common\components\CategoryTree;
//use frontend\models\Category;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\caching\FileDependency;
use yii\helpers\Html;
use common\models\MenuBase;
use common\components\Language;
use common\components\CFunction;
use cms\components\AdminPermission;
use yii\db\Expression;


class Menu extends MenuBase
{

    /**
     * @return array
     */
    public static function getMenuStatus()
    {
        return [
            0 => MenuBase::MENU_INACTIVE,
            1 => MenuBase::MENU_ACTIVE
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function getMenuStatusText($status)
    {
        if ($status == 1) {
            $textStatus = MenuBase::MENU_ACTIVE;
        } else {
            $textStatus = MenuBase::MENU_INACTIVE;
        }

        return $textStatus;
    }

    /**
     * @return array
     */
    public static function getMenuStatusFilter()
    {
        return [
            ['value' => 1, 'type' => CFunction::getParamsArray('menu_type', '1')],
            ['value' => 2, 'type' => CFunction::getParamsArray('menu_type', '2')],
            ['value' => 3, 'type' => CFunction::getParamsArray('menu_type', '3')]
        ];
    }

    /**
     * @param string $prefix
     * @return array
     */
    public static function getCategory($prefix = '')
    {
        $language = Language::language();
        $root = Menu::find()
            ->where('level = :level', [':level' => MenuBase::MIN_LEVEL])
            ->one();
        if ($root === NULL) {
            $root = new Menu();
            $root->{"title_$language"} = 'Root';
            $root->{"desc_$language"} = Yii::t('cms', 'root');
            $root->route = '#';
            $root->created_time = date('Y-m-d H:i:s', time());
            $root->makeRoot();
        }
        $level = NULL;
        $categories = [];
        $data = self::find()->addOrderBy('lft')->all();
        $categories[$root->id] = Yii::t('cms', 'root');
        foreach ($data as $category) {
            if ($level == NULL)
                $level = $category->level - 1;
            $categories[$category->id] = str_repeat($prefix, ($category->level - $level)) . ' ' . $category->{"title_$language"};
        };

        return $categories;
    }

    /**
     * @param $parentId
     * @return string
     */
    public static function getNameParent($parentId)
    {
        $result = Menu::find()
            ->where('id = :parentId', [':parentId' => $parentId])
            ->one();

        if (!empty($result))
            return Html::encode($result->{'title_'.Language::language()});
        else
            return null;
    }

    /**
     * @return mixed
     */
    public static function getCategoryMenu()
    {
        $language = Language::language();


        $dependency = new FileDependency(['fileName' => Yii::getAlias('@runtime') . '/cache/menu.txt']);
        $categories = Yii::$app->cache->get('cache_cate_gory_menu');
        $categories = false;
        if ($categories === false) {
            $categories = Menu::find()->select(['id','title_'.Language::language(),'route','icon','parent_id','order'])
                //->where('level = :level', [':level' => 1])
                ->where('type = :type', [':type' => 1])
                ->andWhere('active = :active', [':active' => 1])
                ->addOrderBy('order')->asArray()
                ->all();
            $sys = new CategoryTree($categories);
            $categories = $sys->builMenuArray();
            Yii::$app->cache->set('cache_cate_gory_menu', $categories, CFunction::getParams('cache_refresh'), $dependency);
        }

        if (!empty($categories)) {
            foreach ($categories as $n => $category) {

                $visible = self::explodeUrl($category['route']);
                $category_r = array(
                    'label' => $category["title_$language"],
                    'url' => ($category['route'] != null) ? [$category['route']] : ['#'],
                    'icon' => $category['icon'],
                    'level' => $category['level']+1,
                    'visible' => $visible ? AdminPermission::checkUserPermission(str_replace('-', '', $visible[0]), CFunction::formatAction($visible[1])) : 0
                );
                $category_tree[$n] = $category_r;
                if (!empty($category['items']))
                    $category_tree[$n]['items'] = self::getChildren($category['items']);

            }
        }
        return $category_tree;
    }

    /**
     * @param $children
     * @return array
     */
    public static function getChildren($children)
    {
        $language = Language::language();
        $result = array();

        foreach($children as $i => $child) {
            $category_r = array(
                'label' => $child['title_'.$language],
                'url' => ($child['route'] != null) ? [$child['route']] : '',
                'icon' => $child['icon'],
                'level' => $child['level']+1,
                'visible' => self::explodeUrl($child['route']) ? AdminPermission::checkUserPermission(str_replace('-', '', self::explodeUrl($child['route'])[0]), CFunction::formatAction(self::explodeUrl($child['route'])[1])) : 1
            );
            $result[$i] = $category_r;
            if (!empty($child['items'])){
                $result[$i]['items'] = static::getChildren($child['items']);
            }

        }

        return $result;
    }

    /**
     * @param $url
     * @return array|bool
     */
    public static function explodeUrl($url)
    {
        $explode = explode("/", $url);
        $i = 0;
        foreach($explode as $value) {
            if ($explode[$i] == '') {
                unset($explode[$i]);
            }
            $i++;
        }
        $arr = array_values($explode);
        if (array_key_exists(1, $arr))
            return $arr;
        else
            return false;
    }
}