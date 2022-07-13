<?php

namespace common\components;

use Yii;

class CategoryTree {
    protected $_sourceArr;

    public function __construct($sourceArr = null) {
        $this->_sourceArr = $sourceArr;
    }

    public function builArray($parents = 0) {
        //var_dump($this->_sourceArr); die;
        $this->recursive($this->_sourceArr, $parents, 0, $resultArr);
        return $resultArr;
    }
    public function builMenuArray($parents = 0) {
        $this->recursiveMenuArray($this->_sourceArr, $parents, 0, $resultArr);
        return $resultArr;
    }
    public function builMenuHtml($parents = 0) {
        $this->recursiveMenu($this->_sourceArr, $parents, 0, $resultArr);
        return str_replace('<ul></ul>', '', $resultArr);
        return $resultArr;
    }

    public function recursive($sourceArr, $parents = 0, $level =0, &$resultArr) {
        if (count($sourceArr)>0) {
            foreach ($sourceArr as $key => $value) {
                if ($value['parent_id'] == $parents) {
                    $value['level'] = $level;
                    $resultArr[] = $value;
                    $newParents = $value['id'];
                    unset($sourceArr[$key]);
                    $this->recursive($sourceArr, $newParents, $level + 1, $resultArr);
                }
            }
        }
    }

    public function recursiveMenu($sourceArr, $parents = 0, $level =0, &$resultArr) {
        if (count($sourceArr)>0) {
            $i=0;
            foreach ($sourceArr as $key => $value) {
                if ($value['parent_id'] == $parents) {
                    $i++;
                    if($i==1){
                        if($level==0){
                            $resultArr .= '<ul>';
                        }else{
                            $resultArr .= '<ul class="nav nav-second-level collapse in" aria-expanded="true" style="">';
                        }
                    }
                    $resultArr .= '<li><a href="'.$value['route'].'" class="'.$value['icon'].' ">'.$value['title_'.Language::language()].'</a>';
                    $value['level'] = $level;
                    $newParents = $value['id'];
                    unset($sourceArr[$key]);
                    $this->recursiveMenu($sourceArr, $newParents, $level + 1, $resultArr);
                    $resultArr .='</li>';
                }
            }
            if($i!=0)
            $resultArr .= '</ul>';
        }
    }
    public function recursiveMenuArray($sourceArr, $parents = 0, $level =0, &$resultArr) {
        if (count($sourceArr)>0) {
            foreach ($sourceArr as $key => $value) {
                if ($value['parent_id'] == $parents) {
                    $value['level'] = $level;
                    $value['items'] = array();
                    $newParents = $value['id'];
                    unset($sourceArr[$key]);
                    $this->recursiveMenuArray($sourceArr, $newParents, $level + 1,$value['items']);
                    $resultArr[] = $value;
                }
            }
        }
    }

    public  function compare_parent_id($a, $b){
        return strnatcmp($a['parent_id'], $b['parent_id']);
    }

    public function builArray2() {
        $this->recursive2($this->_sourceArr, $resultArr);
        return $resultArr;
    }

    public function recursive2($sourceArr, &$resultArr) {
        usort($sourceArr,  array("common\components\CategoryTree",'compare_parent_id'));
        if (count($sourceArr)>0) {
            foreach ($sourceArr as $key => $value) {
                if($value['parent_id'] == 0) $level = 0;
                else $level = 1;
                $value['level'] = $level;
                $resultArr[] = $value;
                unset($sourceArr[$key]);
            }
        }
    }

    public function selectboxArray($sourceArr,$fieldName=false){
        if (empty($fieldName))
            $fieldName='name';
        $resultArr = null;
        if($sourceArr) {
            foreach ($sourceArr as $key => $item) {
                $resultArr[$key]['id'] = $item['id'];
                $resultArr[$key][$fieldName] = $item[$fieldName];
                $resultArr[$key]['name'] = '';

                switch ($item['level']) {
                    case 1:
                        $resultArr[$key]['name'] = '---' . $item[$fieldName];
                        break;
                    case 2:
                        $resultArr[$key]['name'] = '------' . $item[$fieldName];
                        break;
                    case 3:
                        $resultArr[$key]['name'] = '---------' . $item[$fieldName];
                        break;
                    case 4:
                        $resultArr[$key]['name'] = '------------' . $item[$fieldName];
                        break;
                    case 5:
                        $resultArr[$key]['name'] = '---------------' . $item[$fieldName];
                        break;
                    default:
                        $resultArr[$key]['name'] = $item[$fieldName];
                }
            }
        }
        return $resultArr;
    }
}