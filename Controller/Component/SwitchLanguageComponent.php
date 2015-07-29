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
		//RequestActionの場合、スキップする
		if (! empty($this->controller->request->params['requested'])) {
			return;
		}
		$this->controller = $controller;
		$this->controller->helpers[] = 'M17n.SwitchLanguage';

		//言語データ取得
		$Language = ClassRegistry::init('M17n.Language');
		$languages = $Language->find('list', array(
			'fields' => array('Language.id', 'Language.code'),
			'conditions' => array('Language.code' => Configure::read('Config.language')) //多言語の登録処理を後で追加
		));
		$this->controller->set('languages', $languages);

		if (isset($this->controller->data['active_lang_code'])) {
			$this->controller->set('activeLangCode', $this->controller->data['active_lang_code']);
		} else {
			$this->controller->set('activeLangCode', Configure::read('Config.language'));
		}
	}

}
