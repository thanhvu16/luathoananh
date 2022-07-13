<?php

namespace common\models;

use Yii;


class MagazineBase extends \common\models\db\MagazineDB{

    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 2;
    const STATUS_PUBLISH = 1;

    static function getListStatus(){
        return [
            self::STATUS_DRAFT => 'Nháp',
            self::STATUS_PENDING => 'Chờ công khai',
            self::STATUS_PUBLISH => 'Công khai',
        ];
    }


}
