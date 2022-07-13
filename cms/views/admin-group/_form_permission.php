<?php
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    use cms\models\AdminGroup;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use cms\models\AdminAction;
?>
<?php $form = ActiveForm::begin([
    'method' => 'post',
    'action' => ['admin-group/permission-add'],
]); ?>
<div>
    <?php $controller = Yii::$app->controller->id; ?>
    <?php echo Html::button(Yii::t('cms', 'app_update'), ['class'=>'btn btn-outline-primary', 'onclick' => "updatePermission($id, '$controller')"]); ?>
    <?php echo Html::submitButton(Yii::t('cms', 'app_save'), ['class'=>'btn btn-outline-primary', 'style' => 'margin-left: 2px;']); ?>
</div>
    <?php Pjax::begin(); ?>
    <?php echo GridView::widget([
        'dataProvider' => $arrayDataProvider,
        'columns' => [
            [
                'attribute' => 'controller',
                'value' => function ($data) {
                    return substr($data['controller'], 0, -10);
                },
                'format' => 'raw',
                'headerOptions' => ['style'=>'text-align: center;']
            ],
            [
                'attribute' => Yii::t('cms', 'app_desc'),
                'value' => function ($data) {
                    return AdminGroup::getDescriptionController($data['controller']);
                },
                'format' => 'raw',
                'headerOptions' => ['style'=>'text-align: center;']
            ],
            /*[
                'attribute' => Yii::t('cms', 'permission'),
                'value' => function ($data) {
                    return AdminAction::checkboxListCustomized($data['controller'], isset($data['permission']) ? $data['permission'] : [], isset($data['attributes']) ? $data['attributes'] : [], ['item'=>function ($index, $label, $name, $checked, $value, $params) {
                            $id = AdminAction::parseID($value, $params['controller']);
                            $desc = AdminAction::parseDesc($value, $params['controller']);
                            return '<div id="admin-action-delete-'.$id.'">'.Html::checkbox($name, $checked, [
                                'value' => $value,
                                'label' => '<span class="admin-action-label">'.$label.'</span>
                                            <a class="admin-action-operations" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="'.Yii::t('cms', 'delete_action').'"><i class="glyphicon glyphicon-remove" onclick="deleteAction('.$id.')"></i></a>
                                            <a class="admin-action-operations" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="'.Yii::t('cms', 'edit_desc').'"><i class="glyphicon glyphicon-pencil" onclick="updateDescAction('.$id.', \''.$desc.'\')"></i></a>
                                            <span class="admin-action-desc" id="admin-action-desc-'.$id.'">'.$desc.'</span>',
                                'class' => 'checkbox_action_'.substr($name, 0, -2)
                            ]).'</div>';
                        }, 'class' => 'checkbox_action'], $data);
                },
                'format' => 'raw',
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: left;']
            ],*/
            [
                'attribute' => Yii::t('cms', 'permission'),
                'value' => function ($data) {
                    return AdminAction::checkboxListCustomized($data['controller'], isset($data['permission']) ? $data['permission'] : [], isset($data['attributes']) ? $data['attributes'] : [] , ['item'=>function ($index, $label, $name, $checked, $value, $params) {
                        return '<div id="admin-action-delete-'.$params['id'].'">'.Html::checkbox($name, $checked, [
                            'value' => $value,
                            'label' => '<span class="admin-action-label">'.$label.'</span>
                                        <a class="admin-action-operations" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="'.Yii::t('cms', 'delete_action').'"><i class="glyphicon glyphicon-remove" onclick="deleteAction('.$params['id'].')"></i></a>
                                        <a class="admin-action-operations" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="'.Yii::t('cms', 'edit_desc').'"><i class="glyphicon glyphicon-pencil" onclick="updateDescAction('.$params['id'].', \''.$value.'\')"></i></a>
                                        <span class="admin-action-desc" id="admin-action-desc-'.$params['id'].'">'.$value.'</span>',
                            'class' => 'checkbox_action_'.substr($name, 0, -2)
                        ]).'</div>';
                    }, 'class' => 'checkbox_action'], $data);
                },
                'format' => 'raw',
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: left;']
            ],
            [
                'attribute' => Yii::t('cms', 'all'),
                'value' => function ($data) {
                    return Html::checkbox("all_id[]", null, ["value" => $data["controller"], "id"=>"all_id_".$data["controller"], "onchange" => "selectAllPermission(this);"]);
                },
                'format' => 'raw',
                'options' => ['width' => '60px'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
        ]
    ]); ?>
    <?php Pjax::end(); ?>
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
<?php ActiveForm::end(); ?>