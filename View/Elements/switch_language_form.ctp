<?php
/**
 * Switch language element
 *   - $languages: Languages data
 *   - $prefix: It is id attribute prefix
 *   - $activeLangCode: active language
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$enable = array_flip(
	Hash::extract($switchLanguages, '{n}.Language.code')
);
$options = $this->M17n->getLanguagesOptions($enable);
?>

<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	<?php echo Hash::get($options, Configure::read('Config.language')); ?> <span class="caret"></span>
</a>
<ul class="dropdown-menu">
	<?php foreach ($options as $code => $name) : ?>
		<li>
			<?php echo $this->NetCommonsHtml->link($name, array('plugin' => 'm17n', 'controller' => 'm17n', 'action' => 'index', '?' => ['language' => $code])); ?>
		</li>
	<?php endforeach; ?>
</ul>
