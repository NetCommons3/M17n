<?php
/**
 * SwitchLanguage Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * SwitchLanguage Helper
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\NetCommons\View\Helper
 */
class SwitchLanguageHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.NetCommonsHtml',
	);

/**
 * 言語切り替えタブ
 *
 * @param string $prefix It is id attribute prefix
 * @return string
 */
	public function tablist($prefix = null) {
		$this->NetCommonsHtml->css('/m17n/css/style.css');

		return $this->_View->element('M17n.switch_language', array(
			'prefix' => $prefix,
			'languages' => $this->_View->viewVars['languages'],
			'activeLangId' => $this->_View->viewVars['activeLangId'],
		));
	}

/**
 * 言語ラベル(切り替え)
 *
 * @param string $name ラベル名
 * @param array $classOptions CSSのクラスオプション
 * @param array $divOptions DIVオプション
 * @return string
 */
	public function label($name, $classOptions = array(), $divOptions = array()) {
		$element = '';

		App::uses('L10n', 'I18n');
		$L10n = new L10n();

		foreach ($this->_View->viewVars['languages'] as $id => $code) {
			$catalog = $L10n->catalog($code);

			$element .= $this->NetCommonsHtml->div($classOptions,
				h($name) . ' ' . __d('m17n', '(' . $catalog['language'] . ')'),
				Hash::merge(array(
					'ng-show' => 'activeLangId === \'' . $id . '\'',
					'ng-cloak' => 'true',
				), $divOptions)
			);
		}

		return $element;
	}

/**
 * 言語inputラベル(切り替え)
 *
 * @param string $name ラベル名
 * @param int $languageId 言語ID
 * @return string
 */
	public function inputLabel($name, $languageId) {
		$element = h($name);

		if (isset($languageId)) {
			App::uses('L10n', 'I18n');
			$L10n = new L10n();
			$catalog = $L10n->catalog($this->_View->viewVars['languages'][$languageId]);

			$element .= ' ' . __d('m17n', '(' . $catalog['language'] . ')');
		}

		return $element;
	}

}
