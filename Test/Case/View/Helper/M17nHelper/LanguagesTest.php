<?php
/**
 * M17n::countries()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('M17nHelper', 'M17n.View/Helper');

/**
 * M17n::countries()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\View\Helper\M17nHelper
 */
class M17nHelperLanguagesTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$Controller = new Controller();
		$View = new View($Controller);
		$this->M17nHelper = new M17nHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->M17nHelper);

		parent::tearDown();
	}

/**
 *languages()のテスト用DataProvider
 *
 * ### 戻り値
 *  - method メソッド名
 *  - options オプション
 *  - expected 結果
 *
 * @return void
 */
	public function dataProviderLanguages() {
		return array(
			array('method' => 'assertTextContains',
				'options' => array('class' => 'test-class', 'default' => 'ja'),
				'expected' => '<select name="data[test]" class="test-class"'),
			array('method' => 'assertTextContains',
				'options' => array('class' => 'test-class', 'default' => 'ja'),
				'expected' => '<option value="ja" selected="selected">'),

			array('method' => 'assertTextNotContains', 'options' => array(),
				'expected' => '<option value="ja" selected="selected">'),
		);
	}

/**
 * languages()のテスト
 *
 * @param string $method メソッド名
 * @param array $options オプション
 * @param int $expected 結果
 * @dataProvider dataProviderLanguages
 * @return void
 */
	public function testLanguages($method, $options, $expected) {
		$result = $this->M17nHelper->languages('test', $options);
		$this->$method($expected, $result);
	}

/**
 * languages()のrequest->query['language']テスト
 *
 * @return void
 */
	public function testLanguagesByQuery() {
		$this->M17nHelper->request->query['language'] = 'ja';
		$result = $this->M17nHelper->languages('test', array());
		$this->assertTextContains('<option value="ja" selected="selected">', $result);
	}

}
