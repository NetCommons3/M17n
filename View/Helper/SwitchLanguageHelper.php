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
 * Output with tablist format as language switch
 *
 * @param string $prefix It is id attribute prefix
 * @return string
 */
	public function tablist($prefix = null) {
		return $this->_View->element('M17n.switch_language', array(
			'prefix' => $prefix,
			'languages' => $this->_View->viewVars['languages'],
			'activeLangId' => $this->_View->viewVars['activeLangId'],
		));
	}
}
