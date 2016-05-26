<?php
/**
 * M17nSaveBehavior
 *
 * @property OriginalKey $OriginalKey
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ModelBehavior', 'Model');

/**
 * M17nSaveBehavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package  NetCommons\M17n\Model\Befavior
 */
class M17nSaveBehavior extends ModelBehavior {

/**
 * afterValidate is called just after model data was validated, you can use this callback
 * to perform any data cleanup or preparation if needed
 *
 * @param Model $model Model using this behavior
 * @return mixed False will stop this event from being passed to other behaviors
 */
	public function afterValidate(Model $model) {
		$langId = Hash::get($model->data, 'RoomsLanguage.language_id');

		$languages = Current::readM17n(null, 'Language');
		$langCode = Hash::get(
			Hash::extract($languages, '{n}.Language[id=' . $langId . '].code'), '0'
		);

		App::uses('L10n', 'I18n');
		$catalog = (new L10n())->catalog($langCode);

		foreach ($model->validationErrors as $field => $errors) {
			foreach ($errors as $index => $message) {
				$message .= ' ' . __d('m17n', '(' . $catalog['language'] . ')');
				$model->validationErrors[$field][$index] = $message;
			}
		}
		return true;
	}

}
