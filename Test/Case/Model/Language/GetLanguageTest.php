<?php
/**
 * Language::getLanguage()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * Language::getLanguage()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Language
 */
class LanguageGetLanguageTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.language',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'm17n';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'Language';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getLanguage';

/**
 * getLanguage()のテスト
 *
 * @return void
 */
	public function testGetLanguageAsAll() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$type = 'all';
		$options = array();

		//テスト実施
		$result = $this->$model->$methodName($type, $options);

		//チェック
		$expected = array(
			0 => array(
				'Language' => array(
					'id' => '1',
					'code' => 'en',
					'weight' => '1',
					'is_active' => true,
					'created_user' => null,
					'created' => '2014-07-03 05:00:39',
					'modified_user' => null,
					'modified' => '2014-07-03 05:00:39',
				),
			),
			1 => array(
				'Language' => array(
					'id' => '2',
					'code' => 'ja',
					'weight' => '2',
					'is_active' => true,
					'created_user' => null,
					'created' => '2014-07-03 05:00:39',
					'modified_user' => null,
					'modified' => '2014-07-03 05:00:39',
				),
			),
		);
		$this->assertEquals($result, $expected);
	}

/**
 * getLanguage()のテスト
 *
 * @return void
 */
	public function testGetLanguageAsList() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$type = 'list';
		$options = array();

		//テスト実施
		$result = $this->$model->$methodName($type, $options);

		//チェック
		$expected = array(
			'1' => 'en',
			'2' => 'ja',
		);
		$this->assertEquals($result, $expected);
	}

/**
 * getLanguage()のテスト
 *
 * @return void
 */
	public function testGetLanguageAsListWFields() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$type = 'list';
		$options = array(
			'fields' => array('code', 'code')
		);

		//テスト実施
		$result = $this->$model->$methodName($type, $options);

		//チェック
		$expected = array(
			'en' => 'en',
			'ja' => 'ja',
		);
		$this->assertEquals($result, $expected);
	}

}
