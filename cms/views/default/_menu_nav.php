<?php
    use cms\models\Menu;
    use cms\widgets\SideNav;
    use yii\caching\FileDependency;
    use common\components\CFunction;

    echo SideNav::widget([
        'items' => Menu::getCategoryMenu()
    ]);