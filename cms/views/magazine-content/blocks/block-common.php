<?php
if (empty($name)) {
	return '';
}
$value = !empty($content['common'][$name]['title']) ? $content['common'][$name]['title'] : '';
$color = !empty($content['common'][$name]['color']) ? $content['common'][$name]['color'] : '#000';
$position = !empty($content['common'][$name]['position']) ? $content['common'][$name]['position'] : 'center';
$tag = !empty($content['common'][$name]['tag']) ? $content['common'][$name]['tag'] : 'h2';
$size = !empty($content['common'][$name]['size']) ? $content['common'][$name]['size'] : '16';
?>
<div class="form-group field-question-title">
	<div class="block-common">
		<label style="align-self: center" class="control-label" for="<?= $name ?>-block"><?= $label ?></label>
		<div class="common-title">
			<div class="dropdown">
			  <button class="button-common dropdown-toggle status-current" role="button" id="dropdownSize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Size
			  </button>
			  <div class="dropdown-menu" aria-labelledby="dropdownSize">
				<?php for ($i = 14; $i <= 30; $i+=2): ?>
				  <a class="dropdown-item drop-size-<?= $name ?> <?= $size == $i ? 'active' : '' ?>" id="<?= $name ?>-size-<?= $i ?>" href="javascript:;" onclick="changeSize(<?= $i ?>, '<?= $name ?>')">
					<?= $i ?>px
				  </a>
				<?php endfor; ?>
			  </div>
			  <input type="hidden" id="size-<?= $name ?>" name="content[common][<?= $name ?>][size]" value="<?= $size ?>"/>
			</div>
			<div class="dropdown">
			  <button class="button-common dropdown-toggle status-current" role="button" id="dropdownTag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Tag
			  </button>
			  <div class="dropdown-menu" aria-labelledby="dropdownTag">
				<?php for ($j = 1; $j <= 6; $j++): ?>
				  <a class="dropdown-item drop-tag-<?= $name ?> <?= $tag == 'h' . $j ? 'active' : '' ?>" id="<?= $name ?>-tag-h<?= $j ?>" href="javascript:;" onclick="changeTag('h<?= $j ?>', '<?= $name ?>')">
					H<?= $j ?>
				  </a>
				<?php endfor; ?>
				<a class="dropdown-item drop-tag-<?= $name ?> <?= $tag == 'p' ? 'active' : '' ?>" id="<?= $name ?>-tag-p" href="javascript:;" onclick="changeTag('p', '<?= $name ?>')">
					P
				</a>
			  </div>
			  <input type="hidden" id="tag-<?= $name ?>" name="content[common][<?= $name ?>][tag]" value="<?= $tag ?>"/>
			</div>
			<div style="position: relative">
				<button class="button-common" type="button" onclick="setColor('<?= $name ?>')">Color</button>
				<input class="hidden-common" id="color-<?= $name ?>" type="color" value="<?= $color ?>" name="content[common][<?= $name ?>][color]">
			</div>
			<div class="dropdown">
			  <button class="button-common dropdown-toggle status-current" role="button" id="dropdownPosition" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Position
			  </button>
			  <div class="dropdown-menu" aria-labelledby="dropdownPosition">
				  <a class="dropdown-item <?= $position == 'left' ? 'active' : '' ?>" id="<?= $name ?>-position-left" href="javascript:;" onclick="changePosition('left', '<?= $name ?>')">Left</a>
				  <a class="dropdown-item <?= $position == 'right' ? 'active' : '' ?>" id="<?= $name ?>-position-right" href="javascript:;" onclick="changePosition('right', '<?= $name ?>')">Right</a>
				  <a class="dropdown-item <?= $position == 'center' ? 'active' : '' ?>" id="<?= $name ?>-position-center" href="javascript:;" onclick="changePosition('center', '<?= $name ?>')">Center</a>
			  </div>
			  <input type="hidden" id="position-<?= $name ?>" name="content[common][<?= $name ?>][position]" value="<?= $position ?>"/>
			</div>
			<button class="button-common" type="button" onclick="previewCommon('<?= $name ?>')">Preview</button>
		</div>
	</div>
	<input type="text" id="<?= $name ?>-block" class="form-control" name="content[common][<?= $name ?>][title]" maxlength="255" value="<?= $value ?>">
	<div style="display: flex">
		<label style="align-self: center;margin-bottom: 0px !important;">Preview: </label>
		<div class="preview-common" id="preview-common-<?= $name ?>">
			<<?= $tag ?> style="font-size: <?= $size ?>px;color: <?= $color ?>;text-align: <?= $position ?>"><?= $value ?></<?= $tag ?>>
		</div>
	</div>
</div>
