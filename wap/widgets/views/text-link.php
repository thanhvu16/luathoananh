<div class="block-text-link mt-3">
    <p class="title-text-link position-relative">
        <span class="bg-icon icon-text-link"></span>
        Tìm kiếm nhiều
    </p>
    <div class="d-flex flex-wrap mt-3">
        <?php foreach ($textLink as $v): ?>
        <a class="text-link" href="<?= $v->link ?>">
            <?= $v->title ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>