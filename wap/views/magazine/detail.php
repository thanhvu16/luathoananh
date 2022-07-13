<?php

use yii\helpers\Html;
use yii\helpers\Url;
use Yii;
use wap\components\CFunction;

/**
 * @var \yii\web\View $this
 * @var \common\models\MagazineBase $model
 * @var \common\models\MagazineContentBase[] $contents
 */
if (!empty($model->background)) {
    $strCss = '
        .k14-sp-wrapper, .k14-sp-wrapper .sp-body-content .sp-detail {
            background-color: ' . $model->background . ';
        }
    ';
    $this->registerCss($strCss);
}
?>
<div id="fb-root"></div>
<div class="k14-sp-wrapper">
    <?php if (!empty($model->image_cover_web)) { ?>
        <div class="sp-cover" style="position: relative;">
            <img src="<?= Yii::$app->mobiledetect->isMobile() ? Html::encode($model->image_cover_wap) : Html::encode($model->image_cover_web) ?>"/>
			<?php if (!empty($model->content_cover)): ?>
				<div class="content-cover">
					<?= $model->content_cover ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($model->text_cover)): ?>
				<a class="btn-cover" href="<?= $model->link_cover ?>" target="_blank">
					<?= $model->text_cover ?>
				</a>
			<?php endif; ?>
        </div>
    <?php } ?>
    <div class="sp-body-content" id="contentMgz">
		<div class="responsive">
            <div class="sp-detail">
                <div class="sp-sapo"><?= Html::encode($model->sapo); ?></div>
			</div>
		</div>
		<?php if (!empty($contents)) {
			foreach ($contents as $content) {
				$contentBlock = !empty($content->content) ? unserialize($content->content) : [];
				if (!empty($contentBlock['backgroundType']) && $contentBlock['backgroundType'] != 'blank') {
					if ($contentBlock['backgroundType'] == 'background_linear') {
						echo '<div style="padding: 10px;background: linear-gradient(to right, '.$contentBlock['bg_linear_left'].', '.$contentBlock['bg_linear_range'].', '.$contentBlock['bg_linear_right'].')">';
					} else if ($contentBlock['backgroundType'] == 'image' && !empty($contentBlock['backgroud_block'])) {
						echo '<div style="background-image: url('.$contentBlock['backgroud_block'].');background-size: cover;background-position: center center;background-repeat: no-repeat">';
					}
				}
				?>
				<div class="responsive">
					<div class="sp-detail">
						<div class="sp-detail-maincontent">
							<div class="sp-detail-content">
								<?php
								echo $this->render('//magazine/blocks/' . $content->block_type, [
									'model' => $content,
									'magazine' => $model
								]);
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
				if (!empty($contentBlock['backgroundType']) && $contentBlock['backgroundType'] != 'blank') {
					echo '</div>';
				}
			}
		} ?>
						
		<div class="responsive">
            <div class="sp-detail">
                <div class="sp-detail-maincontent">
                    <div class="sp-detail-content">
                        <div class="clearfix"></div>
                        <div class="VCSortableInPreviewMode footerEmagaZine clearfix" type="credit"
                             data-border="rgb(255, 153, 0)">
                            <div class="content-wrapper" style="border-left-color: rgb(255, 153, 0); min-height: auto;background: #F1F1F1;">
                                <?php if (!empty($model->author)) { ?>
                                    <div class="credit-item"><label>Bài viết: </label>
                                        <div class="credit-text"><?= Html::encode($model->author) ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($model->author_image)) { ?>
                                    <div class="credit-item"><label>Ảnh: </label>
                                        <div class="credit-text"><?= Html::encode($model->author_image) ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($model->clip)) { ?>
                                    <div class="credit-item"><label>Clip: </label>
                                        <div class="credit-text"><?= Html::encode($model->clip) ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($model->designer)) { ?>
                                    <div class="credit-item"><label>Thiết kế: </label>
                                        <div class="credit-text"><?= Html::encode($model->designer) ?></div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($model->source)) { ?>
                                    <a class="ttvn-link"
                                       href="<?= !empty($model->source_link) ? Html::encode($model->source_link) : '' ?>"
                                       rel="nofollow"
                                       target="_blank">Theo <?= !empty($model->source) ? Html::encode($model->source) : '' ?></a>
                                <?php } ?>
                                <p>
                                    <span class="publish-date" style="right: 5px;"><?= date('d.m.Y', strtotime(!empty($model->public_time) ? $model->public_time : $model->created_time)) ?></span>
                                </p>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                    <?php if(!empty($newsRelated)){ ?>
                    <div class="clearfix block-news-diff-home">
                        <div class="menu-content menu-content-left">
                            <a href="" class="title-block position-relative">
                                Tin liên quan
                            </a>
                        </div>
                        <div>
                            <?php foreach ($newsRelated as $newsRelate) { ?>
                                <div class="mt-10">
                                    <h3>
                                        <a href="<?php echo CFunction::renderUrlNews($newsRelate) ?>" class="title-news mb-0">
                                            <?php echo $newsRelate['title'] ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="list-news news-diff-home">
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php echo CFunction::renderUrlNews($newsRelate) ?>" class="justify-content-start">
                                            <img src="<?php echo CFunction::genUrlImageNews($newsRelate) ?>" width="240">
                                        </a>
                                        <div class="justify-content-end info-news">
                                            <p class="description-first-home mt-10 text-58">
                                                <?php echo $newsRelate['brief'] ?>
                                            </p>
                                            <?php if(!empty($newsRelate['sname'])) { ?>
                                                <a href="<?php echo Url::toRoute(['sport/index', 'alias' => $newsRelate['sroute']]) ?>" class="title-red"><?php echo $newsRelate['sname'] ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="fb-comments" style="background: #fff;border-radius: 10px; margin-top: 20px;"
                         data-href="<?php echo Yii::$app->controller->canonical; ?>" data-order-by="reverse_time"
                         data-width="100%" data-numposts="8"></div>
                    <?php if(!empty($listNews)){ ?>
                    <div class="list-news magazine-list-news" style="margin-top: 20px; background: #fff;">
                        <div class="menu-content menu-content-gray d-flex justify-content-between mb-0">
                            <h2>
                                <a href="/" class="title-block">
                                    Tin tức mới nhất
                                </a>
                            </h2>
                        </div>
                        <div style="padding: 10px;" id="wrap-rels-magazin">
                            <?php echo \wap\widgets\ListWidget::widget(['list' => $listNews, 'isLazy' => false]) ?>
                        </div>
                        <div id="loadmore-rel-magazin" class="text-center"></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
		</div>
    </div>

</div>
<?php
    $strJs = '
    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
           if(!$(\'#loadmore-rel-magazin\').hasClass(\'loading\')){
                $(\'#loadmore-rel-magazin\').addClass(\'loading\');
                $(\'#loadmore-rel-magazin\').html(\'<img src="/img/loading.svg" style="width: 60px;" />\');
                $.get(\''.Url::toRoute(['magazine/load-more', 'page' => '']).'\'+pageLoadmore, function(res){
                    $(\'#wrap-rels-magazin\').append(res);
                    pageLoadmore++;
                    $(\'#loadmore-rel-magazin\').removeClass(\'loading\');
                    $(\'#loadmore-rel-magazin\').html(\'\');
                });
           }
        }
    });
	$(document).ready(function() {
		var maxW = $("body").width();
		var wDetail = $(".sp-detail").innerWidth();
		var w = (maxW - wDetail) / 2;
		$(".btn-cover").attr({style: "left: " + w + "px"})
		$(".content-cover").attr({style: "left: " + w + "px;width: " + (wDetail - 50) + "px", })
	})
    ';
    $this->registerJs($strJs);
?>
<script>
    function copyLink() {
        $('#link-copy').show();
        $('#link-copy').focus().select();
        var copyText = document.querySelector("#link-copy");
        copyText.select();
        document.execCommand("copy");
        $('#link-copy').hide();

    }
    function share_fb(url) {
        window.open('https://www.facebook.com/sharer/sharer.php?u='+url,'facebook-share-dialog',"width=626, height=436")
    }

    var pageLoadmore = 2;
</script>
<style>
    .magazine-list-news div p{
        margin-bottom: 0 !important;
    }
	.btn-cover {
		padding: 10px 30px;
		border-radius: 5px;
		background: #FFAB40;
		font-weight: 600;
		font-size: 14px;
		display: inline-block;
		position: absolute;
		bottom: 50px;
		left: 30px;
		transition: all .3s ease;
		color: #000;
	}
	.btn-cover:hover {
		background: #f5961d;
		color: #000;
	}
	.content-cover {
		max-width: 500px;
		position: absolute;
		bottom: 100px;
		left: 30px;
	}
</style>