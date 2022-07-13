<?php
$bgLinearLeft = !empty($content['bg_linear_left']) ? $content['bg_linear_left'] : '#e60023';
$bgLinearRight = !empty($content['bg_linear_right']) ? $content['bg_linear_right'] : '#ff8f00';
$bgLinearRange = !empty($content['bg_linear_range']) ? $content['bg_linear_range'] : '50%';
?>
<div class="form-group form-group-check">
	<input class="position-image-text" id="background-blank" type="radio" name="content[backgroundType]"
		   value="blank" <?= (!empty($content['backgroundType']) && $content['backgroundType'] == 'blank') || empty($content['backgroundType']) ? 'checked' : '' ?> />
	<label for="background-blank" style="margin-right: 10px"> Trống </label>

	<?php if (empty($disableBgImage)): ?>
	<input class="position-image-text" id="background-image" type="radio" name="content[backgroundType]"
		   value="image" <?= !empty($content['backgroundType']) && $content['backgroundType'] == 'image' ? 'checked' : '' ?> />
	<label for="background-image"  style="margin-right: 10px"> Ảnh nền </label>
	<?php endif; ?>
	
	<input class="position-image-text" id="background-linear" type="radio" name="content[backgroundType]"
		   value="background_linear" <?= !empty($content['backgroundType']) && $content['backgroundType'] == 'background_linear' ? 'checked' : '' ?> />
	<label for="background-linear"> Màu nền </label>
</div>
<div class="block-bg-linear">
	<div class="l-gradeint"></div>
	<input type="color" class="color-select side1" value="<?= $bgLinearLeft ?>"/>
	<input type="color" class="color-select side2" value="<?= $bgLinearRight ?>"/>
	<input type="range" value="<?= str_replace('%', '', $bgLinearRange) ?>" min="0" max="100" class="gra-range">
	<input type="hidden" class="bg_linear_left" name="content[bg_linear_left]" value="<?= $bgLinearLeft ?>">
	<input type="hidden" class="bg_linear_right" name="content[bg_linear_right]" value="<?= $bgLinearRight ?>">
	<input type="hidden" class="bg_linear_range" name="content[bg_linear_range]" value="<?= $bgLinearRange ?>">
</div>

<?php if (empty($disableBgImage)): ?>
	<?= \cms\widgets\elfinder\InputFile::widget([
		'name' => 'content[backgroud_block]',
		'value' => !empty($content['backgroud_block']) ? $content['backgroud_block'] : '',
		'language' => 'vi',
		'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
		'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
		'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
		//'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
		'options' => ['class' => 'form-control'],
		'buttonOptions' => ['class' => 'btn btn-default'],
		'multiple' => false       // возможность выбора нескольких файлов
	]); ?>
<?php endif; ?>


<style>

:root {
  --firstside: <?= $bgLinearLeft ?>;
  --grad-hint: <?= $bgLinearRange ?>;
  --secondside: <?= $bgLinearRight ?>;
}
.block-bg-linear {
  max-width: 600px;
  margin: 2rem auto;
  position: relative;
  padding: 0 1.7rem;
}
.block-bg-linear .l-gradeint {
  display: block;
  width: 100%;
  height: 4rem;
  background: linear-gradient(to right, var(--firstside), var(--grad-hint), var(--secondside));
}
block-bg-linear. .gra-range {
  width: 100%;
  margin-top: 15px;
}

.block-bg-linear .color-select {
  appearance: none;
  position: absolute;
  top: 0;
  border: 1px solid #212121;
  outline: none;
  height: 3.9rem;
  padding: 0;
  overflow: hidden;
  background: none;
  cursor: pointer;
  transform: scale(1.2);;
}
.block-bg-linear input[type="color"]::-webkit-color-swatch-wrapper {
  padding: 0;
}
.block-bg-linear input[type="color"]::-webkit-color-swatch {
  border: none;
}
.block-bg-linear .side1 {
  left: 0;
  background: var(--firstside);
}
.block-bg-linear .side2 {
  right: 0;
  background: var(--secondside);
}
/* range slider style */
.block-bg-linear input[type=range] {
  height: 39px;
  -webkit-appearance: none;
  margin: 10px 0;
  width: 100%;
}
.block-bg-linear input[type=range]:focus {
  outline: none;
}
.block-bg-linear input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 12px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 1px 1px 2px #A6A6A6;
  background: #212121;
  border-radius: 4px;
  border: 0px solid #F27B7F;
}
.block-bg-linear input[type=range]::-webkit-slider-thumb {
  box-shadow: 1px 1px 2px #A6A6A6;
  border: 2px solid #212121;
  height: 30px;
  width: 30px;
  border-radius: 0px;
  background: #00dd71;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -10px;
}
.block-bg-linear input[type=range]:focus::-webkit-slider-runnable-track {
  background: #212121;
}
.block-bg-linear input[type=range]::-moz-range-track {
  width: 100%;
  height: 12px;
  cursor: pointer;
  animate: 0.2s;
  box-shadow: 1px 1px 2px #A6A6A6;
  background: #212121;
  border-radius: 4px;
  border: 0px solid #F27B7F;
}
.block-bg-linear input[type=range]::-moz-range-thumb {
  box-shadow: 1px 1px 2px #A6A6A6;
  border: 2px solid #212121;
  height: 30px;
  width: 30px;
  border-radius: 0px;
  background: #00dd71;
  cursor: pointer;
}
.block-bg-linear input[type=range]::-ms-track {
  width: 100%;
  height: 12px;
  cursor: pointer;
  animate: 0.2s;
  background: transparent;
  border-color: transparent;
  color: transparent;
}
.block-bg-linear input[type=range]::-ms-fill-lower {
  background: #212121;
  border: 0px solid #F27B7F;
  border-radius: 8px;
  box-shadow: 1px 1px 2px #A6A6A6;
}
.block-bg-linear input[type=range]::-ms-fill-upper {
  background: #212121;
  border: 0px solid #F27B7F;
  border-radius: 8px;
  box-shadow: 1px 1px 2px #A6A6A6;
}
.block-bg-linear input[type=range]::-ms-thumb {
  margin-top: 1px;
  box-shadow: 1px 1px 2px #A6A6A6;
  border: 2px solid #212121;
  height: 30px;
  width: 30px;
  border-radius: 0px;
  background: #00dd71;
  cursor: pointer;
}
.block-bg-linear input[type=range]:focus::-ms-fill-lower {
  background: #212121;
}
.block-bg-linear input[type=range]:focus::-ms-fill-upper {
  background: #212121;
}
</style>
 
<script>
document.querySelector('.gra-range').addEventListener('input', (e) => {
  document.documentElement.style.setProperty('--grad-hint', e.target.value + '%')
  updateCode()
})

document.querySelector('.side1').addEventListener('input', (e) => {
  document.documentElement.style.setProperty('--firstside', e.target.value)
  updateCode()
})

document.querySelector('.side2').addEventListener('input', (e) => {
  document.documentElement.style.setProperty('--secondside', e.target.value)
  updateCode()
})

updateCode = () => {
  const body = window.getComputedStyle(document.body)
  const codeBlock = document.querySelector('.bg-linear')
  const bgLinearLeft = document.querySelector('.bg_linear_left');
  bgLinearLeft.value = body.getPropertyValue('--firstside');
  const bgLinearRight = document.querySelector('.bg_linear_right');
  bgLinearRight.value = body.getPropertyValue('--secondside');
  const bgLinearRange = document.querySelector('.bg_linear_range');
  bgLinearRange.value = body.getPropertyValue('--grad-hint');
}

updateCode()
</script>