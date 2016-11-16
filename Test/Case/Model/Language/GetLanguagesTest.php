<?php
/**
 * Language::getLanguages()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('M17nGetTest', 'M17n.TestSuite');
App::uses('Language', 'M17n.Model');

/**
 * Language::getLanguages()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Language
 */
class LanguageGetLanguagesTest extends M17nGetTest {

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
	protected $_methodName = 'getLanguages';

/**
 * getLanguages()のテスト
 *
 * @return void
 */
	public function testGetLanguages() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テスト実施
		$result = $this->$model->$methodName();

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
		$this->assertEquals($expected, $result);
	}

/**
 * getLanguages()の既に取得済みの場合のテスト
 *
 * @return void
 */
	public function testGetLanguagesWithData() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		Language::$languages = array('test dummy');

		//テスト実施
		$result = $this->$model->$methodName();

		//チェック
		$expected = array('test dummy');
		$this->assertEquals($expected, $result);
	}

}
