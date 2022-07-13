<?php
use wap\models\News;
use wap\components\CFunction;

$trendNews = News::getNewsHot2(4);
$cateId = $cateId ?? null;
?>

<div class="box-right">
	<div class="form-group has-search">
		<input type="text" class="form-control" placeholder="Bạn muốn tìm hiểu về vấn đề gì?">
		<span class="fa fa-search form-control-feedback"></span>
	</div>
	<div class="box-categories">
		<h4 class="category-title">Dịch vụ luật sư</h4>
		<div class="custom-dropdown-menu">
			<ul>
				<?php foreach ($this->params['dichvu'] as $dichvu): ?>
					<li class="<?= ($cateId == $dichvu['id']) ? 'active' : ''; ?>">
						<a href="<?= CFunction::renderUrlCategory($dichvu) ?>"><?= $dichvu['title'] ?></a><i class="fas fa-angle-right color-blue"></i>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="box-categories">
		<h4 class="category-title">Tin quan tâm</h4>
		<div class="box-news-concern">
			<?php
			foreach ($trendNews as $t):
				?>
				<div class="row">
					<div class="col-6 mt-4">
						<div class="box-news2-thum" style="background-image: url('<?= Yii::$app->request->baseUrl . '' . $t['image']?>');"></div>
					</div>
					<div class="col-6 mt-4">
						<div class="box-news2-des" style="text-align: left;">
							<a style="text-decoration: none; color: inherit;" href="<?= CFunction::renderUrlNews($t) ?>"><?= $t['title'] ?></a>
						</div>
						<div class="box-news2-time"><i class="fas fa-clock"></i><span class="time-title"><?= CFunction::diffTime($t['updated_time']) ?></span></div>
					</div>
				</div>
			<?php
			endforeach;
			?>
		</div>
	</div>
	<?= \wap\widgets\TextLinkWidget::widget() ?>
	<div class="box-advisory text-center">
	<a href="tel:0908308123"><amp-img src="https://luathoanganh.vn/media/uploads/2021/03/20/hoanganh.jpg" style="width:100%" width="350" height="728"></amp-img></a>
		 <!--<div>
			<a class="navbar-brand" href="#"><img src="/themes/default/ctyluat/img/logo3.png" alt=""></a>
		</div>
		<div>
			<a href="tel:19001234" class="btn btn-info btn-call-now">Gọi ngay</a>
		</div>
		<p class="phone-call-now"><a href="tel:19001234">1900 1234</a></p>
		<p class="bottom-title">Tổng đài luật sư trực tuyến</p>-->
	</div>
</div>