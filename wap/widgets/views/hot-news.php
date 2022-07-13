<?php
use wap\components\CFunction;
?>

<div class="body-white text-center pb-100 owl-theme">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h2 class="body-title">TƯ VẤN PHÁP LUẬT</h2>
            </div>
        </div>

        
		<div class="owl-carousel-hotNews">
            <?php
			$countAll = count($hotNews);
			$count = 0;
            foreach ($hotNews as $news):
                ?>
				<?php if($count%8 == 0){ ?>
				<div class="item-slide-home w-100">
					<div class="row">
				<?php } ?>
						<div class="col-12 col-md-6 col-lg-3 mt-5">
							<div class="box-news2">
								<a class="box-news2-thum"
								   href="<?= \wap\components\CFunction::renderUrlNews($news) ?>"
								   style="background-image: url('<?= Yii::$app->request->baseUrl . '' . $news['image'] ?>');"></a>
								<div class="box-news2-time"><i class="fas fa-clock"></i><span
										class="time-title"><?= substr($news['updated_time'], 0, 10) ?></span></div>
								<div class="box-news2-des" style="text-align: left;">
									<a style="text-decoration: none; color: inherit;"
									   href="<?= \wap\components\CFunction::renderUrlNews($news) ?>"><?= $news['title'] ?></a>
								</div>
							</div>
						</div>
				<?php if($count%8 == 7 || $countAll==$count){ ?>
					</div>
				</div>
				<?php } ?>
            <?php 
			$count++;
			endforeach; 
			$this->registerJs('
				$(".owl-carousel-hotNews").owlCarousel({
					margin:10,
					loop:false,
					autoWidth:false,
					items:1
				});
			');
			?>
			
        </div>
    </div>
</div>
<style>
.owl-stage{
	display: flex;
}
.owl-stage-outer{
	overflow: hidden;
}
.owl-nav{
	display: none;
}
button.owl-dot{
	background: 0 0;
    color: inherit;
    border: none;
    padding: 0!important;
    font: inherit;
}
</style>