<?php
/**
 * SwitchLanguage Component
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Component', 'Controller');

/**
 * SwitchLanguage Component
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\ControlPanel\Controller
 */
class SwitchLanguageComponent extends Component {

/**
 * startup
 *
 * @param Controller $controller Controller
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;

		//RequestActionの場合、スキップする
		if (! empty($this->controller->request->params['requested'])) {
			return;
		}
		$this->controller->helpers[] = 'M17n.SwitchLanguage';

		//言語データ取得
		$Language = ClassRegistry::init('M17n.Language');
		$languages = $Language->find('list', array(
			'fields' => array('Language.id', 'Language.code'),
		));
		$this->controller->set('languages', $languages);

		if (isset($this->controller->data['active_language_id'])) {
			$this->controller->set('activeLangId', $this->controller->data['active_language_id']);
		} else {
			$this->controller->set('activeLangId', Configure::read('Config.languageId'));
		}
	}

}
