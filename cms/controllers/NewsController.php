<?php

namespace cms\controllers;

use cms\models\AdminGroup;
use cms\models\LogNews;
use cms\models\Match;
use cms\models\News;
use cms\components\BackendController;
use cms\models\NewsCategory;
use common\helpers\StringHelper;
use common\models\NewsBase;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\components\Utility;
use Yii;
use yii\web\ForbiddenHttpException;
use garyjl\simplehtmldom\SimpleHtmlDom;

class NewsController extends BackendController {

    protected $model;

    public function __construct($id, $module, $config = [])
    {
        $this->model = new News();
        parent::__construct($id, $module, $config);
    }

    public function actionStatusActive(){
        if(empty(Yii::$app->request->get('status'))) {
            Yii::$app->request->setQueryParams(['status' => News::NEWS_ACTIVE]);
        }
        return $this->actionIndex(Yii::$app->request->get());
    }

    public function actionNewsDelete(){
        if(empty($request)) {
            $request = Yii::$app->request->get();
        }
        $searchModel = $this->model;
        if(isset($request['status'])) {
            $status = $request['status'];
        } else {
            $status = -1;
        }
        $title = (string)ArrayHelper::getValue($request, 'title');
        $tag = (string)ArrayHelper::getValue($request, 'tags');
        $createdBy = (string)ArrayHelper::getValue($request, 'created_by');
        $category = (string)ArrayHelper::getValue($request, 'news_category_id');
        if($status != -1){
            $searchModel->status = $status;
        }
        if(!empty($title)){
            $searchModel->title = $title;
        }
        if(!empty($tag)){
            $searchModel->keyword = $tag;
        }
        if(!empty($createdBy)){
            $searchModel->created_by = $createdBy;
        }
        if(!empty($category)){
            $searchModel->news_category_id = $category;
        }
        $dataProvider = $searchModel->searchDelete(Yii::$app->request->queryParams);
        return $this->render('delete', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionStatusAcceptActive(){
        if(empty(Yii::$app->request->get('status'))) {
            Yii::$app->request->setQueryParams(['status' => News::NEWS_ACCEPT_ACTIVE]);
        }
        return $this->actionIndex(Yii::$app->request->get());
    }

    public function actionConfirmActive(){
        if(empty(Yii::$app->request->get('status'))) {
            Yii::$app->request->setQueryParams(['status' => News::NEWS_CONFIRM_ACTIVE]);
        }
        return $this->actionIndex(Yii::$app->request->get());
    }

    public function actionLogs($id){
        $searchModel = new LogNews();
        $dataProvider = $searchModel->search($id);
        return $this->render('logs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Lists all Collection models.
     * @return mixed
     */
    public function actionIndex($request = []) {
        if(empty($request)) {
            $request = Yii::$app->request->get();
        }
        $searchModel = $this->model;
        if(isset($request['status'])) {
            $status = $request['status'];
        } else {
            $status = -1;
        }
        $title = (string)ArrayHelper::getValue($request, 'title');
        $tag = (string)ArrayHelper::getValue($request, 'tags');
        $createdBy = (string)ArrayHelper::getValue($request, 'created_by');
        $category = (string)ArrayHelper::getValue($request, 'news_category_id');
        if($status != -1){
            $searchModel->status = $status;
        }
        if(!empty($title)){
            $searchModel->title = $title;
        }
        if(!empty($tag)){
            $searchModel->keyword = $tag;
        }
        if(!empty($createdBy)){
            $searchModel->created_by = $createdBy;
        }
        if(!empty($category)){
            $searchModel->news_category_id = $category;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Xem một Collection
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Tạo News mới
     * Nếu tạo mới thành công, trình duyệt chuyển đến trang view
     * @return mixed
     */
    public function actionCreate() {
        $model = $this->model;
        if ($model->load(Yii::$app->request->post())) {
			if(empty($model->time_active)){
				$model->time_active = date("Y-m-d H:i:s");
			}
            $model->created_time = date("Y-m-d H:i:s");
            $model->created_by = Yii::$app->user->identity->getId();
            $model->deleted = 0;

            if (!$model->checkPermissionStatus()) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'Trạng thái không hợp lệ!'));
                return $this->render('create', ['model' => $model]);
            }

            $modelCategory = new NewsCategory();
            if (!in_array($model->news_category_id, array_column($modelCategory->getListCategoryByPermission(), 'id'))) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'Tài khoản không có quyền chọn danh mục này'));
                return $this->render('create', ['model' => $model]);
            }

            if (trim($model->{"title"}) == '') {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'collection_type_title'));
                return $this->render('create', ['model' => $model]);
            }
            if ($this->model::find()->where(['title' => trim($model->{"title"})])->exists()) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'collection_type_exists'));
                return $this->render('create', ['model' => $model]);
            }
			$model->content = str_replace('</div>', '</p>', $model->content);
			$model->content = str_replace('<div', '<p', $model->content);

//			if(!empty($model->keyword)){
//				$model->tags = Utility::genTagsByKeywords($model->keyword);
//			}

            $relIds = ArrayHelper::getValue($_POST, 'rel_ids');
            $model->rel_ids = !empty($relIds) ? implode(',', $relIds) : '';

            $model->slug = rtrim(Utility::rewrite($model->title), '-');
            $this->renderMenuContent($model);

            if($model->validate() && $model->save()){
                if(Yii::$app->request->post('image-data')){
                    $imgEncode =Yii::$app->request->post('image-data');
                    Utility::uploadImgNews($imgEncode,$model);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Cập nhật News
     * Nếu như cập nhật thành công,trình duyệt chuyển về trang view
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if(!$model->checkPermissionEdit()){
            throw new ForbiddenHttpException('Tài khoản của bạn không có quyền chỉnh sửa bài viết này');
        }
        $oldCategory = $model->news_category_id;
        $slugLast = $model->slug;
        $flagChangeSlug = ($model->status == $model::NEWS_ACTIVE) ? false : true;
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_time = date("Y-m-d H:i:s");
            $model->updated_by = Yii::$app->user->identity->getId();
            if ($flagChangeSlug)
				$model->slug = rtrim(Utility::rewrite($model->title), '-');

            if (!$model->checkPermissionStatus()) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'Trạng thái không hợp lệ!'));
                return $this->render('create', ['model' => $model]);
            }

            $modelCategory = new NewsCategory();
            if ($oldCategory != $model->news_category_id && !in_array($model->news_category_id, array_column($modelCategory->getListCategoryByPermission(), 'id'))) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'Tài khoản không có quyền chọn danh mục này'));
                return $this->render('create', ['model' => $model]);
            }


			$model->content = str_replace('</div>', '</p>', $model->content);
			$model->content = str_replace('<div', '<p', $model->content);
//			$model->content_amp = Utility::convertContentAmp($model->content);

//			if(!empty($model->keyword)){
//				$model->tags = Utility::genTagsByKeywords($model->keyword);
//			}
            $this->renderMenuContent($model);

            $relIds = ArrayHelper::getValue($_POST, 'rel_ids');
            $model->rel_ids = !empty($relIds) ? implode(',', $relIds) : '';
            if(empty($model->time_active)){
				$model->time_active = date("Y-m-d H:i:s");
			}
            if ($model->validate() && $model->save()) {
                if (Yii::$app->request->post('image-data') && strlen(Yii::$app->request->post('image-data')) > 100) {
                    $imgEncode = Yii::$app->request->post('image-data');
                    Utility::uploadImgNews($imgEncode, $model);
                } elseif ($slugLast !== $model->slug) {
                    Utility::uploadImgNews(null, $model, $slugLast);
                }
                return $this->redirect(['index']);
            }
            foreach ($model->getErrors() as $errors) {
                foreach ($errors as $err){
                    Yii::$app->session->setFlash('error', $err);
                }
            }
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }
	
	/**
     * @param $model News
     */
    protected function renderMenuContent($model)
    {
        if (empty($model->content)) {
            $model->menu_content = null;
            return;
        }
        $dom = SimpleHtmlDom::str_get_html($model->content);
        $menuContent = [];
        $indexParent = 0;
        foreach ($dom->find('h2, h3') as $k => $value) {
            $tag = $value->tag;
            if ($tag == 'h2') {
                $indexParent++;
                $menuContent[$indexParent]['parent'] = $value->plaintext;
                $value->id = 'tree-menu-' . $indexParent;
                continue;
            }
            if ($tag == 'h3' && !isset($menuContent[$indexParent])) {
                continue;
            } elseif ($tag == 'h3') {
                $menuContent[$indexParent]['children'][] = $value->plaintext;
                $indexChildren = count($menuContent[$indexParent]['children']) - 1;
                $value->id = 'tree-menu-' . $indexParent . '-' . $indexChildren;
            }
        }
        $model->menu_content = json_encode($menuContent);
        $model->content = $dom->outertext;
        return;
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        if (!$model->checkPermissionDelete()) {
            throw new ForbiddenHttpException('Tài khoản của bạn không có quyền xóa bài viết này');
        }
        $model->deleted = 1;
        $model->updated_time = date("Y-m-d H:i:s");
        $model->updated_by =Yii::$app->user->identity->getId();
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionDeleteMulti(){
        $this->layout = false;
        $ids = ArrayHelper::getValue($_REQUEST, 'ids');
        $ids = explode(',', $ids);
        foreach ($ids as $id){
            $model = $this->findModel($id);
            if(empty($model)) continue;
            $model->deleted = 1;
            $model->updated_time = date("Y-m-d H:i:s");
            $model->updated_by =Yii::$app->user->identity->getId();
            $model->save();
        }
        echo Json::encode(['status' => 1, 'message' => 'Success']);
        Yii::$app->end();
    }

    /**
     * Finds the News model based on its primary key value.
     * If the modelllection model based on its primary key value.
     * Tìm thấy các Collection dựa trên giá trị khóa chính của nó.
     * Nếu News không được tìm thấy 404 HTTP exception  .
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = $this->model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @params: NULL
     * @function: Hàm này thay đổi trang thái của News (1: Active, 0: Inactive)
     */
    public function actionChangeStatus() {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        if(!$this->model->checkPermissionActive()){
            echo Json::encode(array('status' => -1, 'message' => 'Bạn không có quyền active tin tức'));
            Yii::$app->end();
        }

        if(!$this->model->checkPermissionStatus()){
            echo Json::encode(array('status' => -1, 'message' => 'Bạn không có quyền sửa bài viết này'));
            Yii::$app->end();
        }

        $model = $this->findModel($id);
        $updateStatus = $model->updateAttributes(array('status' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }


    public function actionChangeStatusHot() {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        if(!$this->model->checkPermissionChangeHot()){
            echo Json::encode(array('status' => -1, 'message' => 'Bạn không có quyền thay đổi tin tức HOT'));
            Yii::$app->end();
        }

        $model = $this->findModel($id);
        $updateStatus = $model->updateAttributes(array('is_hot' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }

    public function actionPopup()
    {
        $this->layout=false;
        $searchModel = $this->model;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('popup', [
            'data' => $dataProvider,
        ]);
    }

    public function actionGetListNewsSelect(){
        $ids = ArrayHelper::getValue($_REQUEST, 'ids');
        if(empty($ids)) return;
        $data = $this->model::find()
            ->select('id, title')
            ->where(['IN', 'id', explode(',', $ids)])
            ->andWhere(['!=', 'deleted', $this->model::DELETED])
            ->asArray()->all();
        echo Json::encode($data);
    }

    public function actionPopupPagination()
    {
        $this->layout=false;
        $searchModel = $this->model;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('popup-pag', [
            'data' => $dataProvider,
        ]);
    }

    public function actionPlsFix($id){
        $group = Yii::$app->user->identity->admin_group_id;
        $model = $this->findModel($id);
        if($model->status == $model::NEWS_ACTIVE || $model->status == $model::NEWS_ACCEPT_ACTIVE) {
            Yii::$app->session->setFlash('error', Yii::t('cms', 'Bài viết đã được duyệt. Không thể sửa!'));
            return $this->redirect(['index']);
        }
        if($model->created_by != Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('cms', 'Tài khoản không thể xin sửa bài viết này!'));
            return $this->redirect(['index']);
        }

        $model->status = Yii::$app->user->identity->admin_group_id == AdminGroup::GROUP_BTV ? $model::NEWS_BTV_CONFIRM_EDIT : $model::NEWS_PV_CONFIRM_EDIT;
        $model->save(false);

        Yii::$app->session->setFlash('success', Yii::t('cms', 'Xin sửa bài thành công!'));
        return $this->redirect(['index']);
    }
}
