<?php
/**
 * SwitchLanguageComponentテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * SwitchLanguageComponentテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\test_app\Plugin\TestM17n\Controller
 */
class TestSwitchLanguageComponentRequestActionController extends AppController {

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->autoRender = true;
		$view = $this->requestAction('/test_m17n/test_switch_language_component/index', array('return'));
		$this->set('view', $view);
	}

}
