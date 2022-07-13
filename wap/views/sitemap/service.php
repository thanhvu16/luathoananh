<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc><?= \yii\helpers\Url::base(true) . \wap\components\CFunction::renderUrlCategory(326) ?></loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
        <lastmod><?= date('Y-m-d\TH:i:s') ?>+07:00</lastmod>
    </url>
    <?php foreach ($this->params['dichvu'] as $v): ?>
    <url>
        <loc><?= \yii\helpers\Url::base(true) . \wap\components\CFunction::renderUrlCategory($v) ?></loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
        <lastmod><?= date('Y-m-d\TH:i:s') ?>+07:00</lastmod>
    </url>
    <?php endforeach; ?>
    <url>
        <loc><?= \yii\helpers\Url::base(true) . \wap\components\CFunction::renderUrlCategory(327) ?></loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
        <lastmod><?= date('Y-m-d\TH:i:s') ?>+07:00</lastmod>
    </url>
    <?php foreach ($this->params['question'] as $v): ?>
        <url>
            <loc><?= \yii\helpers\Url::base(true) . \wap\components\CFunction::renderUrlCategory($v) ?></loc>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
            <lastmod><?= date('Y-m-d\TH:i:s') ?>+07:00</lastmod>
        </url>
    <?php endforeach; ?>
</urlset>