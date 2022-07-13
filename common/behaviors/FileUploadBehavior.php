<?php

namespace common\behaviors;

use common\components\Utility;
use Yii;
use yii\web\UploadedFile;
use yii\db\BaseActiveRecord;
use yii\validators\Validator;
use common\helpers\FileHelper;
use yii\behaviors\AttributeBehavior;

class FileUploadBehavior extends AttributeBehavior
{
    public $attribute = 'file';
    public $uploadAttribute = 'video_upload';
    public $uploadAttributeRules = ['extensions' => ['mp4']];
    public $uploadPath;
    public $autoSave = true;
    public $autoDelete = true;

    /**
     * Init upload behavior
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * Before validate event.
     */
    public function beforeValidate()
    {
        /**
         * @var \yii\base\Behavior::$owner $owner
         */
        $owner = $this->owner;

        if (is_array($this->uploadAttributeRules) && !empty($this->uploadAttributeRules)) {
            $validators = $owner->validators;
            $validator = Validator::createValidator('file', $owner, (array) $this->uploadAttribute, $this->uploadAttributeRules);
            $validators->append($validator);
        }
        if ($owner->{$this->uploadAttribute} instanceof UploadedFile) {
            return;
        }

        $this->owner->{$this->uploadAttribute} = UploadedFile::getInstance($owner, $this->uploadAttribute);
    }

    /**
     * Before save event
     */
    public function beforeSave()
    {
        $owner = $this->owner;
        if ($owner->{$this->uploadAttribute} instanceof UploadedFile) {
        }
    }

    /**
     * Event handler for beforeDelete
     * @param \yii\base\ModelEvent $event
     */
    public function beforeDelete($event)
    {
        $owner = $this->owner;
        if ($owner->{$this->attribute} != null) {
            FileHelper::removeUploaded($owner->{$this->attribute});
        }
    }

    public function afterSave()
    {
        $owner = $this->owner;
        if ($owner->{$this->uploadAttribute} instanceof UploadedFile) {
            Yii::$app->session['upload_path']=Yii::$app->session['file_upload_path'].Utility::storageSolutionEncode($owner->id);
            $filePath = FileHelper::getUploadPath($owner->{$this->uploadAttribute}->name,$owner->id.'.mp4');
            if (!$owner->{$this->uploadAttribute}->saveAs($filePath)){
                Yii::$app->getSession()->setFlash('error', Yii::t('cms', 'Upload Video fail'));
            }
        }

    }
}