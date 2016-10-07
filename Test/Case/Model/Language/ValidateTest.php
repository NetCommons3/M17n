<?php
/**
 * Language::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('LanguageFixture', 'M17n.Test/Fixture');

/**
 * Language::validate()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Language
 */
class LanguageValidateTest extends NetCommonsValidateTest {

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
	protected $_methodName = 'validates';

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 */
	public function dataProviderValidationError() {
		$data['Language'] = (new LanguageFixture())->records[0];

		return array(
			//code
			array(
				'data' => $data, 'field' => 'code', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')
			),
			//weight
			array(
				'data' => $data, 'field' => 'weight', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array(
				'data' => $data, 'field' => 'weight', 'value' => 'a',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array(
				'data' => $data, 'field' => 'weight', 'value' => '1',
				'message' => true
			),
			//is_active
			array(
				'data' => $data, 'field' => 'is_active', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array(
				'data' => $data, 'field' => 'is_active', 'value' => 'a',
				'message' => __d('net_commons', 'Invalid request.')
			),
			array(
				'data' => $data, 'field' => 'is_active', 'value' => '0',
				'message' => true
			),
			array(
				'data' => $data, 'field' => 'is_active', 'value' => '1',
				'message' => true
			),
			array(
				'data' => $data, 'field' => 'is_active', 'value' => '2',
				'message' => __d('net_commons', 'Invalid request.')
			),
		);
	}

}
