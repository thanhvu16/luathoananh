<?php
/**
 * @Function: Lớp xử lý phần Menu của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 20/01/2015
 * @System: Video 2.0
 */

namespace cms\controllers;

use cms\components\BackendController;
use common\components\CategoryTree;
use common\components\Language;
use common\components\Utility;
use common\models\MenuBase;
use Yii;
use cms\models\Menu;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class MenuController extends BackendController {

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Menu::find()->addOrderBy('created_time DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionAdmin() {
//        $this->layout='stats';
        $type = Yii::$app->request->get('type',1);
        $menu = Menu::find()
            ->where(['type' =>$type])
            ->orderBy('order')
            ->asArray()
            ->all();
        $sys = new CategoryTree($menu);
        $result = $sys->builArray();
        $dataProvider = new ArrayDataProvider([
            'key'=>'id',
            'allModels' => $result,
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        return $this->render('index', [
            'menu' => $menu,
            'dataProvider' => $dataProvider,
            'result' => $result,
        ]);

        //$searchModel = new MenuBase();
        //$dataProvider = $searchModel->search(Yii::$app->request->get());
        //return $this->render('admin', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionGetSelectBox() {
        $this->layout=false;
        $type = Yii::$app->request->get('type',null);
        $category = Menu::find()->select(['id', 'title_'.Language::language().'  name_'.Language::language(),'parent_id', 'order'])->where(['type'=>$type])->orderBy('order')->orderBy('id')->asArray()->all();
        $sys = new CategoryTree($category);
        $category = $sys->builArray(0);
        $selectBox = $sys->selectboxArray($category);
        return $this->render('selectbox', [
            'selectBox' => $selectBox,
        ]);

    }

    /**
     * Displays a single Menu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $language = Language::language();
        $model = new Menu();
        if ($model->load(Yii::$app->request->post())) {
            $model->created_time = date("Y-m-d H:i:s");
            $model->created_by = Yii::$app->user->identity->getId();
            if ($model->{"title_$language"} == '') {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'category_title_required'));
                return $this->render('create', ['model' => $model]);
            }

                if(empty($model->parent_id) || $model->parent_id=='')
                    $model->parent_id=0;
                    if($model->save()){
                            if (isset($_FILES) && $_FILES) {
                                $option = [
                                    'width' => Yii::$app->params['img_url']['menu_icon']['width'],
                                    'height' => Yii::$app->params['img_url']['menu_icon']['height'],
                                ];
                                $target = Yii::$app->params['img_url']['data_path'] . '/' . Yii::$app->params['img_url']['menu_icon']['source'];
                                if (isset($_FILES['icon_image']) && $_FILES['icon_image']) {
                                    Utility::uploadThumbnail($_FILES['icon_image'], $target, $model->id, $option, false);
                                }
                            }
                        return $this->redirect(['view', 'id' => $model->id]);
                    }

            return $this->redirect(['index']);
        } else {

            $category = Menu::find()->select(['id', 'title_'.Language::language().'  name_'.$language,'parent_id', 'order'])->orderBy('order')->orderBy('id')->asArray()->all();
            $sys = new CategoryTree($category);
            $category = $sys->builArray(0);
            $selectBox = $sys->selectboxArray($category, "name_".Language::language());

            return $this->render('create', [
                'model' => $model,
                'category' => $category,
                'selectBox' => $selectBox
            ]);
        }
    }

    /**
     * @throws \yii\base\ExitException
     */
    public function actionSort() {
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $sort = ArrayHelper::getValue($_POST, 'sort',null);
        $orderParam = ArrayHelper::getValue($_POST, 'order',null);
                
        if($id){
            $cat = Menu::findOne($id);
            $parentId = (int)$cat->parent_id;
            if(!isset($parentId))
                $parentId = 0;
            $type = $cat->type;
            
            if($orderParam != null) {
                $this->updateOrder($cat->id,$orderParam);

                echo json_encode(['status' => 'success']);
                Yii::$app->end();
            }
            
            // Sort Top || END
            if($sort=='top' || $sort=='end'){
                $category = Menu::find()->where(['parent_id'=>$parentId,'type' =>$type])->orderBy('order')->all();
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
            $category = Menu::find()->where(['parent_id'=>$parentId,'type' =>$type])->orderBy('order')->all();
            foreach($category as $key=> $item){
                $category = Menu::find()->where(['parent_id'=>$parentId,'type' =>$type])->orderBy('order')->all();
                if($item->order != ($key+1)){
                    $this->updateOrder($item->id,$key+1);
                }
            }
            $cat = Menu::findOne($id);
            $order = $cat->order;
            if($sort=='up'){
                //$sql = 'SELECT * FROM `category` where content_type=1 and parent_id = '.$parentId.' and id!='.$id.' and `order` <= '.$order.' order by `order` desc  limit 1 ';
                $categoryMove = Menu::find()
                    ->where('type = :TYPE',[':TYPE'=>$type])
                    ->andWhere('parent_id = :parentId', [':parentId' => $parentId])
                    ->andWhere('id != :id', [':id' => $id])
                    ->andWhere('`order` <= :order', [':order' => $order])
                    ->orderBy('`order` DESC')
                    ->limit(1)
                    ->one();

            }else{
                //$sql = 'SELECT * FROM `category` where content_type=1 and parent_id = '.$parentId.' and id!='.$id.' and `order` >= '.$order.' order by `order` asc  limit 1 ';
                $categoryMove = Menu::find()
                    ->where('type = :TYPE',[':TYPE'=>$type])
                    ->andWhere('parent_id = :parentId', [':parentId' => $parentId])
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
        $cate = Menu::findOne($id);
        $cate->order = $order;
        $cate->updated_time = date("Y-m-d H:i:s");
        $cate->updated_by =Yii::$app->user->identity->getId();
        return $cate->save(false);
    }
//    Old
//    public function actionSort() {
//        if (!Yii::$app->getRequest()->isAjax)
//            Yii::$app->end();
//        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
//        $sort = ArrayHelper::getValue($_POST, 'sort', '');
//
//        $query = new Query();
//        $data = $query->select('prev.id prevId, curr.id currId, next.id nextId, curr.parent_id')
//            ->from('menu curr')
//            ->leftJoin('menu prev', 'prev.rgt = curr.lft-1')
//            ->leftJoin('menu next', 'next.lft = curr.rgt+1')
//            ->where('curr.id != curr.root AND curr.id = :id', [':id' => $id])
//            ->addOrderBy('curr.lft')
//            ->one();
//
//        $modelCurr = Menu::find()
//            ->where('id = :ID', [':ID' => (int) $data['currId']])
//            ->one();
//        $modelCurr->updated_time = date('Y-m-d H:i:s', time());
//        if ($sort == 'up' AND $data['prevId']) {
//            $modelPrev = Menu::find()
//                ->where('id = :ID', [':ID' => (int) $data['prevId']])
//                ->one();
//            $modelCurr->insertBefore($modelPrev);
//        }
//        if ($sort == 'down' AND $data['nextId']) {
//            $modelNext = Menu::find()
//                ->where('id = :ID', [':ID' => (int) $data['nextId']])
//                ->one();
//            $modelCurr->insertAfter($modelNext);
//        }
//        echo json_encode(['status' => 'success']);
//        Yii::$app->end();
//    }

    /**
     * @throws \yii\base\ExitException
     */
    public function actionChangeStatus() {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        $model = MenuBase::findOne($id);
        $updateStatus = $model->updateAttributes(array(
            'id' => $id,
            'active' => $statusChange,
            'updated_time' => date('Y-m-d H:i:s'))
        );
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

        $language = Language::language();
        $model = Menu::find()
            ->where('id = :ID', [':ID' => $id])
            ->one();

        $category = Menu::find()->select(['id', 'title_'.Language::language().'  name_'.$language,'parent_id', 'order'])->orderBy('order')->orderBy('id')->asArray()->all();
        $sys = new CategoryTree($category);
        $category = $sys->builArray(0);
        $selectBox = $sys->selectboxArray($category, "name_".Language::language());
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_time = date("Y-m-d H:i:s");
            $model->updated_by = Yii::$app->user->identity->getId();
            if ($model->{"title_$language"} == '') {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'category_title_required'));
                return $this->render('create', ['model' => $model]);
            }
            if ($model->validate()) {
                if($model->parent_id=='' || empty($model->parent_id))
                    $model->parent_id=0;

                if ($model->save(false)) {
                    $model->refresh();
//                    if (isset($_FILES) && $_FILES) {
//                        $option = [
//                            'width' => Yii::$app->params['img_url']['menu_icon']['width'],
//                            'height' => Yii::$app->params['img_url']['menu_icon']['height'],
//                        ];
//                        $target = Yii::$app->params['img_url']['data_path'] . '/' . Yii::$app->params['img_url']['menu_icon']['source'];
//                        if (isset($_FILES['icon_image']) && $_FILES['icon_image']) {
//                            Utility::uploadThumbnail($_FILES['icon_image'], $target, $model->id, $option, false);
//                        }
//                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'selectBox' => $selectBox,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['admin']);
    }

    /**
     * Xóa 1 hoặc nhiều items
     */
    public function actionDeleteAll() {
        $ids = ArrayHelper::getValue($_POST, 'ids', '');
        if (!empty($ids)) {
            if (sizeof($ids) > 0) {
                MenuBase::deleteAll("id IN ($ids)");
                Yii::$app->session->setFlash('success', Yii::t('cms', 'app_delete_all_success'));
                echo Json::encode(array('status' => 1, 'message' => Yii::t('cms', 'app_delete_all_success')));
                exit();
            }
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_delete_all_fail')));
            exit();
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}