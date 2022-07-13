<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \common\models\MagazineBase[] $magazines
 */
$this->registerCssFile('/themes/magazine/default/css/cate.css');
$this->registerCssFile('/themes/magazine/default/css/topic.css');
?>
<div class="k14-sp-wrapper">
    <div class="sp-sticky-header clearfix collapsed" style="display: block;">
        <a href="/" class="sp-back-to-k14"></a>
        <a href="javascript:;" class="sp-mag-logo">
            <img src="/img/logo_emagazie-white.png" />
        </a>
        <div class="sp-user-profile clearfix"></div>
    </div>
    <div class="kbw-content">
        <div class="w1040">
            <div class="kbwc-body clearfix">
                <div class="gkbd_emag">
                    <div class="big-thumb">
                        <div class="info">
                            <div class="sub-heading">
                                Nhóm chủ đề
                            </div>
                            <div class="name">
                                <span>e</span>Magazine
                            </div>
                            <div class="sapo">
                                Phóng sự, phỏng vấn chân dung phản ánh nhịp sống bóng đá và thể thao Việt Nam
                            </div>
                        </div>
                        <div class="thumb">
                            <?php if(!empty($magazines[0])){
                                $magazine = $magazines[0];
                                $title = Html::encode($magazine->title);
                                $url = Url::toRoute(['magazine/detail', 'slug' => \common\components\CFunction::unsignString($magazine->title), 'id' => $magazine->id]);
                                ?>
                            <a href="<?= $url ?>"
                               class="cover"
                               title="<?= $title ?>"
                               style="background-image: url('<?= $magazine->image ?>')"></a>
                            <div class="total">
                                <a class="title"
                                   href="<?= $url ?>"
                                   title="<?= $title ?>"><?= $title ?></a>
                                <div class="funct" ><span class="date" ><?= \common\components\CFunction::humanTiming($magazine->public_time) ?></span>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <ul class="list-emag" id="tbwnw-list-news">
                        <?php
                        $countMaga = count($magazines);
                        if($countMaga > 1){
                            for ($i=1; $i<$countMaga; $i++){
                                $magazine = $magazines[$i];
                                $title = Html::encode($magazine->title);
                                $url = Url::toRoute(['magazine/detail', 'slug' => \common\components\CFunction::unsignString($magazine->title), 'id' => $magazine->id]);
                            ?>
                        <li>
                            <div class="emag-post">
                                <a href="<?= $url ?>"
                                   title="<?= $title ?>"
                                   class="thumb">
                                    <i style="background-image: url('<?= $magazine->image ?>')"></i>
                                </a>
                                <div class="total">
                                    <a href="<?= $url ?>"
                                       title="<?= $title ?>"
                                       class="title"><?= $title ?></a>
                                    <div class="funct">
                                        <div class="left">
                                            <div class="date" title="2021-01-03T20:51:00"><?= \common\components\CFunction::humanTiming($magazine->public_time) ?></div>
                                        </div>
                                    </div>
                                    <div class="sapo">
                                        <?= Html::encode($magazine->sapo) ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php }} ?>
                    </ul>
                </div>
                <?php if($countMaga==10){ ?>
                <div class="wrapperbtn view-more-detail"><a href="javascript:void(0);" data-page="1" class="btnviewmore" id="viewmore-magazine">Xem thêm</a></div>
                <script>
					var page_load = 1;
				</script>
                <?php
                $this->registerJs('
                $(\'#viewmore-magazine\').click(function(){
                    page_load++;
                    $(this).text(\'Loading....\');
                    $.get(\''.Url::toRoute(['magazine/index']).'?page=\'+page_load,function(res){
                        if($.trim(res).length > 0){
                            $(\'#tbwnw-list-news\').append(res); 
                            $(\'#viewmore-magazine\').text(\'Xem thêm\');
                        }else{
                            $(\'#viewmore-magazine\').remove();
                        }                               
                    });
                });
                ');
            } ?>
                <div id="footerLoadListDetail"></div>
            </div>
        </div>
    </div>
</div>