<?php

/**
 * @var \cms\models\Magazine $model
 */

$typeContents = \cms\models\MagazineContent::getAllTypes();
?>
<ul class="list-group">
    <?php
    foreach ($typeContents as $typeContent){?>
        <li style="cursor: pointer;" class="list-group-item" onclick="addMagazinBlock(<?= $model->id ?>, '<?= $typeContent['type'] ?>')">
            <h4 class="list-group-item-heading"><?= $typeContent['image'] ?> <?= $typeContent['name'] ?></h4>
            <p class="list-group-item-text"><?= $typeContent['desc'] ?></p>
        </li>
    <?php } ?>
</ul>
