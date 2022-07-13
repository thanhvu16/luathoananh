<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5/4/2017
 * Time: 4:41 PM
 */

namespace wap\components;
use Yii;


class Configuration
{
    /**
     * Lấy dữ liệu từ file config/params.php
     * @param $alias String - chuỗi định danh cho biến số trong params.php, phân tách bằng dấu "."
     * @return mixed giá trị biến config hoặc null nếu không tồn tại
     * @author ungnv
     */
    public static function read($alias)
    {
        $returnValue = null;
        if (!empty($alias)){
            $extract = explode('.', $alias);

            if (!empty($extract)){
                $level = 1;
                foreach ($extract as $aliasItem){
                    if ($level == 1){
                        if (isset(Yii::$app->params[$aliasItem])){
                            $returnValue = Yii::$app->params[$aliasItem];
                        }

                    }else{
                        if (isset($returnValue[$aliasItem])){
                            $returnValue = $returnValue[$aliasItem];
                        }else{
                            $returnValue = null;
                        }
                    }

                    $level++;
                }
            }
        }

        return $returnValue;
    }
}