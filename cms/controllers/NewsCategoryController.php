<?php
/**
 * @Function: Lớp xử lý danh mục tin tức của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 20/01/2015
 * @System: Video 2.0
 */

namespace cms\controllers;

use cms\components\BackendController;
use cms\models\NewsCategory;
use cms\models\search\NewsCategorySearch;
use common\components\CategoryTree;
use common\components\Language;
use common\components\Utility;
use common\models\NewsCategoryBase;
use Yii;
use cms\models\Menu;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

class NewsCategoryController extends BackendController {

    protected $model;

    public function __construct($id, $module, $config = [])
    {
        $this->model = new NewsCategory();
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all NewsCategory models.
     * @return mixed
     */
    public function actionAdmin() {
        $category = $this->model::find()
            ->orderBy('order ASC, league_id ASC')
            ->asArray()
            ->all();
        $sys = new CategoryTree($category);
        $result = $sys->builArray();
        $dataProvider = new ArrayDataProvider([
            'key'=>'id',
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        return $this->render('index', [
            'category' => $category,
            'dataProvider' => $dataProvider,
            'result' => $result,
        ]);
    }

    /**
     * Displays a single NewsCategory model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    /**
     * Creates a new NewsCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = $this->model;
        $category = NewsCategory::getCategoryParent();
        $sys = new CategoryTree($category);
        $category = $sys->builArray(0);
        $category = $sys->selectboxArray($category, 'title');
        if ($model->load(Yii::$app->request->post())) {
            $faqs = Yii::$app->request->post('faqs', []);
            $model->faqs = json_encode($faqs);
            if (!isset($model->parent_id) || $model->parent_id == '')
                $model->parent_id = 0;
            $model->code = Utility::rewrite($model->title);
            if (empty($model->route)){
                $model->route = Utility::rewrite($model->title);
            }
            if (empty($model->order)){
                $model->order = 100;
            }
            if ($model->validate() && $model->save()) {
                if(Yii::$app->request->post('image-data')){
                    $imgEncode =Yii::$app->request->post('image-data');
                    Utility::uploadImgNews($imgEncode,$model);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'category' => $category
        ]);
    }

    /**
     * @params: NULL
     * @function: Hàm này thay đổi trang thái của NewsCategory (1: Active, 0: Inactive)
     */
    public function actionChangeStatus() {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        $model = NewsCategoryBase::findOne($id);
        $updateStatus = $model->updateAttributes(array('id' => $id, 'active' => $statusChange));
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
    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $category = NewsCategory::getCategoryParent($id);
        $sys = new CategoryTree($category);
        $category = $sys->builArray(0);
        $category = $sys->selectboxArray($category, 'title');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $faqs = Yii::$app->request->post('faqs', []);
            $model->faqs = json_encode($faqs);
            $model->updated_time = date("Y-m-d H:i:s");
            $model->updated_by = Yii::$app->user->identity->getId();
            if ($model->{"title"} == '') {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'category_title_required'));
                return $this->render('create', ['model' => $model]);
            }
            if ($model->validate()) {
                if(empty($model->route))
                    $model->route = Utility::rewrite($model->title);
                if ($model->save(false)) {
                    if(Yii::$app->request->post('image-data')){
                        $imgEncode =Yii::$app->request->post('image-data');
                        Utility::uploadImgNews($imgEncode,$model);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'category' => $category,
        ]);
    }
    /**
     * Deletes an existing NewsCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['admin']);
    }
    /**
     * @params: NULL
     * @function: Xóa 1 hoặc nhiều danh mục menu của hệ thống
     */
    public function actionDeleteAll() {
        $ids = ArrayHelper::getValue($_POST, 'ids', '');
        if (!empty($ids)) {
            if (sizeof($ids) > 0) {
                $this->model::deleteAll("id IN ($ids)");
                Yii::$app->session->setFlash('success', Yii::t('cms', 'app_delete_all_success'));
                echo Json::encode(array('status' => 1, 'message' => Yii::t('cms', 'app_delete_all_success')));
                exit();
            }
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_delete_all_fail')));
            exit();

    }}



    /**
     * Category move up, down.
     * @Function: Sawps xeeps thanhf coong sex reload lai
     */
    public function actionSort() {
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $sort = ArrayHelper::getValue($_POST, 'sort',null);
        $orderParam = ArrayHelper::getValue($_POST, 'order',null);
        if($id){
            $cat = $this->findModel($id);
            $parentId = (int)$cat->parent_id;
            if(!isset($parentId) || $parentId=='')
                $parentId = 0;

            if($orderParam != null) {
                $this->updateOrder($cat->id,$orderParam);

                echo json_encode(['status' => 'success']);
                Yii::$app->end();
            }

            // Sort Top || END
            if($sort=='top' || $sort=='end'){
                $category = $this->model::find()->where(['parent_id'=>$parentId])->orderBy('order')->all();
                $s =0;
                foreach($category as $key=> $item){
                    if($sort == 'top' && $key==0){
                        $s =$key+1;
                        $this->updateOrder($id,$s);
                        $s = $s + 1;
                        $this->updateOrder($item->id,$s);
                    }elseif($id!=$item->id){
                        $s = $s + 1;
                        $this->updateOrder($item->id,$s);
                    }

                }
                if($sort == 'end'){
                    $s =$s+1;
                    $this->updateOrder($id,$s);
                }
                echo json_encode(['status' => 'success']);
                Yii::$app->end();
            }


            // Sort UP || DOWN
            $category = $this->model::find()->where(['parent_id'=>$parentId])->orderBy('order')->all();
            foreach($category as $key=> $item){
                $category = $this->model::find()->where(['parent_id'=>$parentId])->orderBy('order')->all();
                if($item->order != ($key+1)){
                    $this->updateOrder($item->id,$key+1);
                }
            }
            $cat = $this->findModel($id);
            $order = $cat->order;
            if($sort=='up'){
                //$sql = 'SELECT * FROM `category` where content_type=1 and parent_id = '.$parentId.' and id!='.$id.' and `order` <= '.$order.' order by `order` desc  limit 1 ';
                $categoryMove = $this->model::find()
                    ->where('parent_id = :parentId', [':parentId' => $parentId])
                    ->andWhere('id != :id', [':id' => $id])
                    ->andWhere('`order` <= :order', [':order' => $order])
                    ->orderBy('`order` DESC')
                    ->limit(1)
                    ->one();

            }else{
                //$sql = 'SELECT * FROM `category` where content_type=1 and parent_id = '.$parentId.' and id!='.$id.' and `order` >= '.$order.' order by `order` asc  limit 1 ';
                $categoryMove = $this->model::find()
                    ->where('parent_id = :parentId', [':parentId' => $parentId])
                    ->andWhere('id != :id', [':id' => $id])
                    ->andWhere('`order` >= :order', [':order' => $order])
                    ->orderBy('`order` ASC')
                    ->limit(1)
                    ->one();

            }

            if($categoryMove){
                $cat->order = $categoryMove->order;
                $cat->updated_time = date("Y-m-d H:i:s");
                $this->updateOrder($cat->id,$categoryMove->order);

                $categoryMove->order = $order;
                $categoryMove->updated_time = date("Y-m-d H:i:s");
                $this->updateOrder($categoryMove->id,$order);
                //$cat->save();
                //$categoryMove->save();
                echo json_encode(['status' => 'success']);
                Yii::$app->end();
            }
            echo json_encode(['status' => 'success']);
            Yii::$app->end();
        }
        Yii::$app->end();
    }
    protected function updateOrder($id,$order) {
        $cate = $this->findModel($id);
        $cate->order = $order;
        $cate->updated_time = date("Y-m-d H:i:s");
        $cate->updated_by =Yii::$app->user->identity->getId();
        return $cate->save(false);
    }


    /**
     * Finds the NewsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return NewsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = $this->model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
