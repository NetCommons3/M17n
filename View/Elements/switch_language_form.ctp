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
?>

<?php echo $this->NetCommonsForm->create(null,
	array(
		'type' => 'get',
		'url' => NetCommonsUrl::actionUrl(array('plugin' => 'm17n', 'controller' => 'm17n', 'action' => 'index')),
	));
?>

<?php echo $this->M17n->languages('language',
		array(
			'label' => false,
			'default' => Configure::read('Config.language'),
			'onchange' => 'submit()'
		)
	); ?>

<?php echo $this->Form->end();