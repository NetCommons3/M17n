<?php
/**
 * SwitchLanguageComponent::startup()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * SwitchLanguageComponent::startup()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Controller\Component\SwitchLanguageComponent
 */
class SwitchLanguageComponentStartupTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'm17n';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'M17n', 'TestM17n');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * startup()のGETテスト
 *
 * @return void
 */
	public function testStartupGet() {
		//テストコントローラ生成
		$this->generateNc('TestM17n.TestSwitchLanguageComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction('/test_m17n/test_switch_language_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/TestSwitchLanguageComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$expected = '2';
		$this->assertEquals($this->vars['activeLangId'], $expected);

		$expected = array(
			'1' => 'en',
			'2' => 'ja',
		);
		$this->assertEquals($this->vars['languages'], $expected);
	}

/**
 * startup()のGETテスト
 *
 * @return void
 */
	public function testStartupGetByRequestAction() {
		//テストコントローラ生成
		$this->generateNc('TestM17n.TestSwitchLanguageComponentRequestAction');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction('/test_m17n/test_switch_language_component_request_action/index',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/TestSwitchLanguageComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$pattern = '/' . preg_quote('Controller/Component/TestSwitchLanguageComponentRequestAction/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->assertArrayNotHasKey('activeLangId', $this->vars);
		$this->assertArrayNotHasKey('languages', $this->vars);
	}

/**
 * startup()のPOSTテスト
 *
 * @return void
 */
	public function testStartupPost() {
		//テストコントローラ生成
		$this->generateNc('TestM17n.TestSwitchLanguageComponent');

		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction('/test_m17n/test_switch_language_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');

		//テスト実行
		$this->_testPostAction('post', array('active_language_id' => '1'),
				'/test_m17n/test_switch_language_component/index', null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/TestSwitchLanguageComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$expected = '1';
		$this->assertEquals($this->vars['activeLangId'], $expected);

		$expected = array(
			'1' => 'en',
			'2' => 'ja',
		);
		$this->assertEquals($this->vars['languages'], $expected);
	}

}
