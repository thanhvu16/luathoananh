<section class="breadcrumb-section text-center breadcrumb-section-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="breadcrumb-title">Kết quả tìm kiếm: <span class="key-word"><?= $tags ?></span></h1>
            </div>
        </div>
    </div>
</section>

<section class="body-content">
    <div class="body-white pb-100">
        <div class="container">

            <div class="row box-news-border">
                <?php foreach ($list as $news): ?>
                <div class="col-12 col-md-6 col-lg-4 mt-4">
                    <div class="box-news2">
                        <a href="<?= \wap\components\CFunction::renderUrlNews($news) ?>" ><div class="box-news2-thum h235" style="background-image: url('<?= Yii::$app->request->baseUrl ?>/themes/default/ctyluat/img/thum3.png');"></div></a>
                        <a href="<?= \wap\components\CFunction::renderUrlNews($news) ?>" style="color: inherit; text-decoration: none;"><h2 class="box-news2-title"><?= $news['title'] ?></h2></a>
                        <div class="box-news2-des"><?= $news['brief'] ?></div>
                        <div class="text-center">
                            <a href="<?= \wap\components\CFunction::renderUrlNews($news) ?>" class="box-new2-link-detail">Chi tiết <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="col-12 mt-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center pagination-custom">
                            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>