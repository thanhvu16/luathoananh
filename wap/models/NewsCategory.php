<?php

/**
 * Created by PhpStorm.
 * User: ungnv
 * Date: 4/24/2017
 * Time: 3:49 PM
 */

namespace wap\models;

use common\components\CategoryTree;
use common\models\NewsCategoryBase;
use Yii;

class NewsCategory extends \common\models\NewsCategoryBase
{
    protected static $nameConfigCategory = 'categoryExport';

    public static function sortOrder($categories)
    {
        for ($i = 0; $i < count($categories); $i++) {
            for ($j = $i + 1; $j < count($categories); $j++) {
                if ($categories[$i]['order'] < $categories[$j]['order']) {
                    $tmp = $categories[$i];
                    $categories[$i] = $categories[$j];
                    $categories[$j] = $tmp;
                }
                if (!empty($categories[$i]['children'])) {
                    $categories[$i]['children'] = self::sortOrder($categories[$i]['children']);
                }
            }
        }
        return $categories;
    }

    public static function genTree($categories)
    {

        $output = array();
        $all = array();
        $dangling = array();

        // Initialize arrays
        foreach ($categories as $entry) {
            $entry['children'] = array();
            $id = $entry['id'];

            // If this is a top-level node, add it to the output immediately
            if ($entry['parent_id'] == 0) {
                $all[$id] = $entry;
                $output[] = &$all[$id];

                // If this isn't a top-level node, we have to process it later
            } else {
                $dangling[$id] = $entry;
            }
        }

        // Process all 'dangling' nodes
        while (count($dangling) > 0) {
            foreach ($dangling as $entry) {
                $id = $entry['id'];
                $pid = $entry['parent_id'];

                // If the parent has already been added to the output, it's
                // safe to add this node too
                if (isset($all[$pid])) {
                    $all[$id] = $entry;
                    $all[$pid]['children'][] = &$all[$id];
                    unset($dangling[$entry['id']]);
                }
            }
        }
        return $output;
    }

    public static function getListCategory()
    {
        $menu = false; //\Yii::$app->cache->get(self::NAME_CACHE_MENU);
        if (empty($menu)) {
            $category = self::getAllCategory();
            $sys = new CategoryTree($category);
            $category = $sys->builArray(0);
            $menu = self::genTree($category);
            $menu = self::sortOrder($menu);
            //\Yii::$app->cache->set(self::NAME_CACHE_MENU, $menu);
        }
        return $menu;
    }

    public static function getAllCategory()
    {
        $category = false; //\Yii::$app->cache->get(self::NAME_CACHE_NEWS_CATEGORY);
        if (empty($category)) {
            $category = \Yii::$app->params[self::$nameConfigCategory];
            //\Yii::$app->cache->set(self::NAME_CACHE_NEWS_CATEGORY, $category, 300);
        }
        return $category;
    }

    public static function getCategory($id)
    {
        $cate = [];
        if (is_array($id)) {
            foreach ($id as $v) {
                $category = \Yii::$app->params[self::$nameConfigCategory][$v];
                if (!empty($category))
                    $cate[] = $category;
            }
        }
        if (is_numeric($id)) {
            $cate = \Yii::$app->params['categoryExport'][$id];
        }
        return $cate;
    }

    public static function getCategoryChildren($parentId)
    {
        $allCate = self::getAllCategory();
        $listChildren = [];
        foreach ($allCate as $k => $cate) {
            if ($cate['parent_id'] == $parentId)
                $listChildren[] = $cate;
        }
        return $listChildren;
    }

    public static function getAllChildren($id)
    {
        $allCate = self::getAllCategory();
        $child = [];
        foreach ($allCate as $k => $cate) {
            if ($cate['parent_id'] == $id) {
                $child[] = $cate['id'];
            }
        }
        return $child;
    }

    public static function getCategoryParent($parentId)
    {
        $allCate = self::getAllCategory();
        if (!empty($allCate[$parentId])) {
            if ($allCate[$parentId]['parent_id'] != 0) {
                return self::getCategoryParent($allCate[$parentId]['parent_id']);
            } else {
                return  $allCate[$parentId];
            }
        }
        return false;
    }

    public static function getCategoryByNews($news)
    {
        $allCategory = self::getAllCategory();
        foreach ($news as $k => $v) {
            if (empty($allCategory[$v['news_category_id']])) {
                continue;
            }
            $news[$k]['cid'] = $v['news_category_id'];
            $news[$k]['cname'] = $allCategory[$v['news_category_id']]['title'];
            $news[$k]['croute'] = $allCategory[$v['news_category_id']]['route'];
        }
        return $news;
    }

    public static function getCategoryBySport($sportId)
    {
        $allCate = self::getAllCategory();
        $arrCate = [];
        foreach ($allCate as $cate) {
            if ($cate['sport_id'] == $sportId) {
                $arrCate[] = $cate;
            }
        }
        return $arrCate;
    }

    public static function getCategoryMenuHome()
    {
        $allCate = self::getAllCategory();
        $list = [];
        $listChildren = [];
        foreach ($allCate as $cate) {
            if ($cate['parent_id'] == 0 && count($list) <= 9) {
                $list[] = $cate;
            }
            if ($cate['parent_id'] != 0) {
                $listChildren[] = $cate;
            }
        }

        //        $listParent = array_column($list, 'id');
        //        foreach ($listChildren as $k => $v){
        //            if(!in_array($v, $listParent)){
        //                unset($listChildren[$k]);
        //            }
        //        }

        $sys = new CategoryTree(array_merge($listChildren, $list));
        $category = $sys->builArray(0);
        $menu = self::genTree($category);
        $menus = self::sortOrder($menu);

        return $menus;
    }

    public static function getListNewsShowHome($limit = 15)
    {
        $result = self::find()
            ->where(['active' => self::MENU_ACTIVE])
            ->andWhere(['parent_id' => 0])
            ->orderBy('is_hot DESC, order ASC')
            ->limit($limit)
            ->asArray()
            ->all();
        return $result;
    }

    public static function getListCategoryIsLeagueId($cateId)
    {
        $result = self::find()
            ->where(['active' => self::MENU_ACTIVE])
            ->andWhere(['parent_id' => $cateId])
            ->andWhere(['<>', 'league_id', 0])
            ->orderBy('is_hot DESC, order ASC')
            ->asArray()
            ->all();
        return $result;
    }

    public static function getParentCategories($type, $isHot = [0, 1])
    {
        $keyCache = 'getParentCategories_' . $type . '_' . json_encode($isHot);
        $result = Yii::$app->cache->get($keyCache);
        if ($result === false) {
            $result = self::find()
                ->where(['parent_id' => $type])
                ->andWhere(['is_hot' => $isHot])
                ->orderBy('order ASC')
                ->asArray()
                ->all();
            Yii::$app->cache->set($keyCache, $result, 86400);
        }
        return $result;
    }
    public static function getCateById($id)
    {
        $result = self::find()
            ->where(['id' => $id])
            ->orderBy('order ASC')
            ->asArray()
            ->all();
        return $result;
    }

    public static function genBreadcrum($categoryId)
    {
        $category = self::getCategory($categoryId);
        $categories = [
            $category
        ];
        $parentId = $category['parent_id'];
        while ($parentId != 0) {
            $category = self::getCategory($parentId);
            $categories[] = $category;
            $parentId = $category['parent_id'];
        }
        return array_reverse($categories);
    }
}
