<?php

namespace common\models;

use common\models\db\AdminActionDB;
use Yii;


class AdminActionBase extends AdminActionDB {
    const ACTION_ACTIVE = 1;
    const ACTION_INACTIVE = 0;
}