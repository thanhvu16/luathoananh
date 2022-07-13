<?php

namespace wap\models;

use common\components\CategoryTree;
use Yii;
use common\models\MenuBase;


class Menu extends MenuBase
{

    public static function getMenuFrontend(){
        $result = self::find()
            ->where(['active' => self::MENU_ACTIVE])
            ->andWhere(['type' => self::MENU_TYPE_WEB])
            ->asArray()
            ->all();

        $sys = new CategoryTree($result);
        $result = $sys->builArray(0);
        $result = self::genTree($result);
        $result = self::sortOrder($result);
        return $result;
    }

    public static function sortOrder($categories){
        for($i = 0; $i < count($categories); $i++){
            for($j = $i+1; $j < count($categories); $j++){
                if($categories[$i]['order'] < $categories[$j]['order']){
                    $tmp = $categories[$i];
                    $categories[$i] = $categories[$j];
                    $categories[$j] = $tmp;
                }
                if(!empty($categories[$i]['children'])){
                    $categories[$i]['children'] = self::sortOrder($categories[$i]['children']);
                }
            }
        }
        return $categories;
    }

    public static function genTree($categories){

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
                $output[] =& $all[$id];

                // If this isn't a top-level node, we have to process it later
            } else {
                $dangling[$id] = $entry;
            }
        }

        // Process all 'dangling' nodes
        while (count($dangling) > 0) {
            foreach($dangling as $entry) {
                $id = $entry['id'];
                $pid = $entry['parent_id'];

                // If the parent has already been added to the output, it's
                // safe to add this node too
                if (isset($all[$pid])) {
                    $all[$id] = $entry;
                    $all[$pid]['children'][] =& $all[$id];
                    unset($dangling[$entry['id']]);
                }
            }
        }
        return $output;
    }
}