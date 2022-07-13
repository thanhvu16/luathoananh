<?php
echo '<label for="menu-parent_id" class="control-label">Danh mục cha</label>';
echo '<select name="Menu[parent_id]" class="form-control" id="menu-parent_id">';
    echo '<option value="">'.Yii::t('cms','Chuyên mục cha').'</option>';
    if(!empty($selectBox)){
        foreach($selectBox as $key=>$item){
            echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
    }
echo '</select>';
echo '<div class="help-block"></div>';
?>