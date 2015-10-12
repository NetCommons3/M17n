<?php
/**
 * M17n::weightSort()のテスト
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
 * M17n::weightSort()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\View\Helper\M17nHelper
 */
class MockLangForM17nHelper {

/**
 * weight
 *
 * @var int
 */
	public $weight = null;

/**
 * Constructor
 *
 * @param int $weight 表示順序
 * @return void
 */
	public function __construct($weight) {
		$this->weight = $weight;
	}

}

/**
 * M17n::weightSort()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\View\Helper\M17nHelper
 */
class M17nHelperWeightSortTest extends NetCommonsCakeTestCase {

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
 * weightSort()のテスト用DataProvider
 *
 * ### 戻り値
 *  - lang1Weight lang1の表示順
 *  - lang2Weight lang2の表示順
 *  - expected 結果
 *
 * @return void
 */
	public function dataProviderWeightSort() {
		return array(
			array('lang1Weight' => 1, 'lang2Weight' => 1, 'expected' => 0),
			array('lang1Weight' => 2, 'lang2Weight' => 1, 'expected' => -1),
			array('lang1Weight' => 1, 'lang2Weight' => 2, 'expected' => 1),
		);
	}

/**
 * weightSort()のテスト
 *
 * @param int $lang1Weight lang1の表示順
 * @param int $lang2Weight lang2の表示順
 * @param int $expected 結果
 * @dataProvider dataProviderWeightSort
 * @return void
 */
	public function testWeightSort($lang1Weight, $lang2Weight, $expected) {
		$lang1 = new MockLangForM17nHelper($lang1Weight);
		$lang2 = new MockLangForM17nHelper($lang2Weight);

		$result = $this->M17nHelper->weightSort($lang1, $lang2);

		$this->assertEquals($expected, $result);
	}

/**
 * HTTP_ACCEPT_LANGUAGEパースのテスト
 *
 * @return void
 */
	public function testWeightSortByParseLangHeaders() {
		$result = $this->_testReflectionMethod(
			$this->M17nHelper, '__parseLangHeaders', array('ja,en-US;q=0.7,en;q=0.3')
		);

		$actual = array(
			'language' => 'ja',
			'country' => null,
			'weight' => 1
		);
		$this->assertEquals(get_object_vars($result[0]), $actual);

		$actual = array(
			'language' => 'en',
			'country' => 'us',
			'weight' => 0.7
		);
		$this->assertEquals(get_object_vars($result[1]), $actual);

		$actual = array(
			'language' => 'en',
			'country' => null,
			'weight' => 0.3
		);
		$this->assertEquals(get_object_vars($result[2]), $actual);
	}

}
