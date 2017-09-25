<?php
/**
 * Switch language element
 *   - $languages: Languages data
 *   - $prefix: It is id attribute prefix
 *   - $activeLangCode: active language
 *   - $position: 配置場所
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('L10n', 'I18n');
$L10n = new L10n();

if (! isset($position)) {
	$position = ' pull-right';
}
?>

<div ng-init="activeLangId = '<?php echo h($activeLangId); ?>'" class="clearfix m17n-language-switch">
	<ul class="nav nav-pills<?php echo $position; ?> small" role="tablist">
		<?php foreach ($languages as $langId => $langCode) : ?>
			<li class="<?php echo ($activeLangId === (string)$langId ? 'active' : ''); ?>">
				<a class="nc-switch-language" href="#<?php echo $prefix . $langId ?>" role="tab" data-toggle="tab"
					ng-click="activeLangId = '<?php echo $langId; ?>'">

					<?php $catalog = $L10n->catalog($langCode);
							echo __d('m17n', $catalog['language']); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<input type="hidden" name="active_lang_id" ng-value="activeLangId">
	<?php $this->Form->unlockField('active_lang_id'); ?>
</div>
