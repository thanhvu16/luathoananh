<?php
/**
 * @Function: Lớp xử lý phần thống kê chuyên mục, đẩy dữ liệu vào bảng category_stats_daily
 * @Author: trinh.kethanh@gmail.com
 * @Date: 17/03/2015
 * @System: Video 2.0
 */

namespace console\controllers;

use wap\models\CaseBaseVn;
use wap\models\CaseVn;
use wap\models\Provinces;
use Yii;
use yii\console\Exception;
use yii\console\Controller;
use PHPExcel_IOFactory;

class ImportController extends Controller
{
    /**
     * Thong ke chuyen muc
     */
    public function actionIndex(){
        $this->actionImportReportCaseVN();
    }

    public function actionImportReportDay(){

        set_time_limit(20000);
        $inputFileName = 'C:/Users/long/Desktop/1.xlsx';

        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $province = Provinces::find()
            ->asArray()
            ->all();
        $arrProvinces = array_combine(array_column($province, 'name'), array_column($province, 'id'));

        for ($row = 2; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE,FALSE);
//            echo '<pre/>';
//            var_dump($rowData);die;
            $model = new \wap\models\ReportProvine();
            $model->provine_id = $arrProvinces[trim($rowData[0][0])];
            $model->number_case = $rowData[0][1];
            $model->foreign = $rowData[0][2];
            $model->vn = $rowData[0][3];
            $model->f0 = $rowData[0][4];
            $model->f1 = $rowData[0][5];
            $model->f2 = $rowData[0][6];
            $model->cured = $rowData[0][7];
            $model->intrustion_contagious = $rowData[0][8];
            $model->positive_after_quarantine = $rowData[0][9];
            $model->community_contagious = $rowData[0][10];
            $model->intrusion_after_entry = $rowData[0][11];
            $model->number_treatment = $rowData[0][12];
            if(!$model->save()){
                var_dump($model->getErrors());die;
            }
            echo 'done '. $row;
        }
        echo 'done all';
    }

    public function actionImportReportCaseVN(){

        set_time_limit(20000);
        $inputFileName = 'C:/Users/long/Desktop/1.xlsx';

        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $province = Provinces::find()
            ->asArray()
            ->all();
        $arrProvinces = array_combine(array_column($province, 'name'), array_column($province, 'id'));

        for ($row = 1; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE,FALSE);

            if(empty($rowData[0][0]))
                continue;
            $model = CaseBaseVn::findOne($row);
            $caseId = CaseBaseVn::find()->select('id')->where(['code' => $rowData[0][0]])->asArray()->one();

            if(empty($caseId))
                continue;

            $model->case_infection = $caseId['id'];

//            echo '<pre/>';
//            var_dump($rowData);die;
//            $model->code = trim($rowData[0][0]);
//            $model->age = trim($rowData[0][1]);
//            $model->gender = $model::$arrGender[trim($rowData[0][2])];
//            $model->nationality = $rowData[0][3];
//            $model->hospitalized_day = $this->converDateExcel($rowData[0][4]);
//            $model->test_date = $this->converDateExcel($rowData[0][5]);
//            $model->status = $model::$arrStatus[trim($rowData[0][6])];
//            $model->place_of_treatment = $this->converProvince($rowData[0][7], $arrProvinces);
//            $model->person_contagious = $rowData[0][0];
//            $model->starting_gate = $rowData[0][9];
//            $model->type_injection = $rowData[0][10];
//            $model->date_to_vn = $this->converDateExcel($rowData[0][11]);
//            $model->location_injection_vn = $rowData[0][12];
//            $model->noi = trim($rowData[0][0]);
//            $model->aircraft_number = trim($rowData[0][1]);
//            $model->type = $model::$arrType[trim($rowData[0][2])] ?? null;
//            $model->f = trim($rowData[0][3]);
//            $model->date_reported = $this->converDateExcel($rowData[0][4]);
//            $model->sot = $this->convertStatusToInt($rowData[0][5]);
//            $model->ho = $this->convertStatusToInt($rowData[0][6]);
//            $model->rat_hong = $this->convertStatusToInt($rowData[0][7]);
//            $model->so_mui = $this->convertStatusToInt($rowData[0][8]);
//            $model->kho_tho = $this->convertStatusToInt($rowData[0][9]);
//            $model->met_moi = $this->convertStatusToInt($rowData[0][10]);
//            $model->trieu_chung = $this->convertStatusToInt($rowData[0][11]);
//            $model->latest_date_contact = $this->converDateExcel($rowData[0][12]);
            if(!$model->save()){
                var_dump($model->getErrors());die;
            }
            echo 'done '. $row;
        }
        echo 'done all';
    }

    public function actionImportCaseVN(){

        set_time_limit(20000);
        $inputFileName = 'C:/Users/long/Desktop/3.csv';

        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $time = date('Y-m-d H:i:s');

        for ($row = 2; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE,FALSE);
            $model = new CaseVn();
//            var_dump(($rowData[0][13] == 'Kh™ng c— b‡o c‡o ') ? 'Không có báo cáo' : $rowData[0][13]);die;
            $model->number_cases = $this->convertInt($rowData[0][1]);
            $model->new_cases = $this->convertInt($rowData[0][2]);
            $model->being_treated = $this->convertInt($rowData[0][3]);
            $model->cured = $this->convertInt($rowData[0][4]);
            $model->total_cumulative_test = $this->convertInt($rowData[0][5]);
            $model->test_of_day = $this->convertInt($rowData[0][6]);
            $model->new_suspected_infection = $this->convertInt($rowData[0][7]);
            $model->suspected_infection = $this->convertInt($rowData[0][8]);
            $model->quarantine_medical = $this->convertInt($rowData[0][9]);
            $model->quarantine_concentrate = $this->convertInt($rowData[0][10]);
            $model->quarantine_accommodation = $this->convertInt($rowData[0][11]);
            $model->quarantine_area = $this->convertInt($rowData[0][12]);
            $model->note = ($rowData[0][13] == 'Kh™ng c— b‡o c‡o ') ? 'Không có báo cáo' : (string) $rowData[0][13];
            $model->death = $this->convertInt($rowData[0][14]);
            $model->date_reported = $this->converDateExcel($rowData[0][0]);
            $model->created_time = $time;
            if(!$model->save()){
                var_dump($model->getErrors());die;
            }
            echo 'done '. $row;
        }
        echo 'done all';
    }

    public function convertInt($data){
        if(is_null($data))
            return null;
        return (int) str_replace(',', '', $data);
    }

    public function converProvince($province, $arrProvinces){
        if($province === 'Hoà Bình')
            return $arrProvinces['Hòa Bình'];
        return $arrProvinces[trim($province)] ?? '';
    }

    public function convertStatusToInt($status){
        return trim($status) === 'Có' ? CaseBaseVn::ACTIVE : CaseBaseVn::INACTIVE;
    }

    public function converDateExcel($floatDate){
        if(empty(trim($floatDate)))
            return '';
        if(!is_float($floatDate))
            return $this->converDate($floatDate);
        $unixDate = ($floatDate - 25569) * 86400;
        return gmdate("Y-m-d", $unixDate);
    }

    public function converDate($date){
        $arr = explode('/', $date);
        return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
    }

    public function actionImportGlobalCase()
    {
        set_time_limit(20000);
        $inputFileName = 'C:/Users/long/Desktop/WHO-COVID-19-global-data.csv';

        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $time = date('Y-m-d H:i:s');

        for ($row = 29213; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE,FALSE);
//            echo '<pre/>';
//            var_dump($rowData);die;
            $model = new \wap\models\CaseGlobal();
            $model->date_reported = $rowData[0][0];
            $model->country_code = !empty(trim($rowData[0][1])) ? $rowData[0][1] : 'Other';
            $model->new_cases = $rowData[0][4];
            $model->cumulative_case = $rowData[0][5];
            $model->new_deaths = $rowData[0][6];
            $model->cumulative_deaths = $rowData[0][7];
            $model->created_time = $time;
            $model->created_by = 1;
            if(!$model->save()){
                var_dump($model->getErrors());die;
            }
            echo 'done '. $row;
        }
        echo 'done all';
    }
}