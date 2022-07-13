<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use cms\models\Category;
use cms\models\Collection;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\Utility;
use common\components\Language;
use common\components\CFunction;
use cms\models\CollectionContent;
use common\components\CategoryTree;

$this->params['breadcrumbs'] = [
    [
        'label' => 'Dashboard',
        'url' => ['index']
    ]
];

$this->title = Yii::$app->name.' - '.'Dashboard';
$this->params['title'] = Html::encode('Welcome CMS!');
?>
    
<!--    <section class="content-header">
      <h1>
        ChartJS
        <small>Preview sample</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Charts</a></li>
        <li class="active">ChartJS</li>
      </ol>
    </section>-->

    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-lg-3">
          <div class="panel theme">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-user fa-5x" id="loginaccount_logo"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge ng-binding">0</div>
                  <div class="titles_in_dashboard">Login Accounts</div>
                </div>
              </div>
            </div>
            <a ui-sref="list({entity:'LoginData'})" href="#/LoginData/list">
              <div class="panel-footer" id="footer1">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="panel theme">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-desktop fa-5x" id="channels_logo"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge ng-binding">0</div>
                  <div class="titles_in_dashboard">Channels</div>
                </div>
              </div>
            </div>
            <a ui-sref="list({entity:'Channels'})" href="#/Channels/list">
              <div class="panel-footer" id="footer2">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="panel theme">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-film fa-5x" id="vod_logo"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge ng-binding">0</div>
                  <div class="titles_in_dashboard">VOD Movies</div>
                </div>
              </div>
            </div>
            <a ui-sref="list({entity:'Vods'})" href="#/Vods/list">
              <div class="panel-footer" id="footer3">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="panel theme">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-outdent fa-5x" id="device_logo"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge ng-binding">0</div>
                  <div class="titles_in_dashboard">Devices</div>
                </div>
              </div>
            </div>
            <a ui-sref="list({entity:'Devices'})" href="#/Devices/list">
              <div class="panel-footer" id="footer4">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="">
            <div class="box-header with-border">
              <h3 class="box-title">Area Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="areaChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="">
            <div class="box-header with-border">
              <h3 class="box-title">Donut Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChart" style="height:250px"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="">
            <div class="box-header with-border">
              <h3 class="box-title">Line Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- BAR CHART -->
          <div class="">
            <div class="box-header with-border">
              <h3 class="box-title">Bar Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
