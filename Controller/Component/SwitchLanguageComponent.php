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
 * 多言語フィールド
 *
 * @var array
 */
	public $fields = array();

/**
 * startup
 *
 * @param Controller $controller Controller
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;

		//RequestActionの場合、スキップする
		if (! empty($controller->request->params['requested'])) {
			return;
		}
		$controller->helpers[] = 'M17n.SwitchLanguage';

		//言語データ取得
		$Language = ClassRegistry::init('M17n.Language');
		$languages = $Language->find('list', array(
			'recursive' => -1,
			'fields' => array('id', 'code'),
			'conditions' => array('is_active' => true),
			'order' => 'weight'
		));
		$controller->set('languages', $languages);

		if (isset($controller->data['active_language_id'])) {
			$controller->set('activeLangId', $controller->data['active_language_id']);
		} else {
			$controller->set('activeLangId', Current::read('Language.id'));
		}

		$this->_setM17nRequestValue();
	}

/**
 * リクエスデータ内の多言語で未入力の場合、Current言語の入力されている内容をセットする
 *
 * @return void
 */
	protected function _setM17nRequestValue() {
		$controller = $this->controller;

		if (! $controller->request->is(array('post', 'put'))) {
			return;
		}

		$langId = Current::read('Language.id');
		foreach ($this->fields as $fieldName) {
			list($model, $field) = pluginSplit($fieldName);

			$value = Hash::get(
				Hash::extract($controller->data, $model . '.{n}[language_id=' . $langId . '].' . $field), '0'
			);

			foreach ($controller->data[$model] as $i => $data) {
				if ($data[$field]) {
					continue;
				}

				$controller->request->data[$model][$i][$field] = $value;
			}
		}
	}

}
