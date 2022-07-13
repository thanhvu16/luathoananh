<?php

namespace common\components;

use Yii;

class Image {
    public static function image($label, $options = []) {
        $languages = CFunction::getParams('languages');
        $width = $options['width'];
        $height = $options['height'];
        $content = '<div class="form-group col-md-12">
            <div class="col-md-3">
                <label for="collection-order" class="control-label">'.$label.'</label>
                <div class="image-crop">
                    <img name="old-image" src="#">
                </div>
            </div>
            <div class="col-md-5">
                <!-- <div class="img-preview img-preview-sm"></div> -->
                <label for="collection-order" class="control-label">'.Yii::t('cms','action').'</label>
                <div class="btn-group">
                    <label title="'.Yii::t('cms', 'select_image').'" for="inputImage" class="btn btn-primary">
                        <input type="file" accept="image/*" name="file" id="inputImage" class="hide">
                        '.Yii::t('cms', 'select_image').'
                    </label>
                </div>
                <div class="btn-group">
                    <button class="btn btn-primary" id="zoomIn" type="button"><span class="glyphicon  glyphicon-zoom-in"></span></button>
                    <button class="btn btn-primary" id="zoomOut" type="button"><span class="glyphicon  glyphicon-zoom-out"></span></button>
                    <button class="btn btn-primary" id="rotateLeft" type="button"><span class="fa fa-rotate-left"></span></button>
                    <button class="btn btn-primary" id="rotateRight" type="button"><span class="fa fa-rotate-right"></span></button>
                    <input type="hidden" name="images-width" id="images-width" value="'.$width.'" />
                    <input type="hidden" name="images-height" id="images-height" value="'.$height.'" />
                    <textarea name="image-data" id="image-data" class="hidden-lg"></textarea>
                </div>
            </div>
        </div>';
        return $content;
    }
}