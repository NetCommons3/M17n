<?php
/**
 * M17nBehavior::save()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('M17nBehaviorSaveTestBase', 'M17n.TestSuite');
App::uses('TestM17nBSaveFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'm17n';

/**
 * Field key
 *
 * @var string
 */
	public $fieldKey = 'key';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'M17n', 'TestM17n');
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSave');
	}

/**
 * テストデータ
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return array テストデータ
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	public function dataProvider() {
		//データ生成
		$results = array();

		// * 0.コンテンツ新規登録
		$index = 0;
		$results[$index]['langId'] = '2';
		$results[$index]['data'] = array(
			'TestM17nBSave' => array(
				'language_id' => $results[$index]['langId'],
				'key' => 'add_key_1',
				'content' => 'Test add 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSave' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => false,
				'id' => '5',
			),
		));

		// * 1.「日本語のみ」のデータを日本語で編集
		$index = 1;
		$results[$index]['langId'] = '2';
		$results[$index]['data'] = array(
			'TestM17nBSave' => array(
				'id' => '1',
				'language_id' => $results[$index]['langId'],
				'key' => 'test_1',
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSave' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => false,
				'created' => (new TestM17nBSaveFixture())->records[0]['created'],
				'created_user' => (new TestM17nBSaveFixture())->records[0]['created_user'],
			),
		));

		// * 2.「日本語のみ」のデータを英語で編集
		$index = 2;
		$results[$index]['langId'] = '1';
		$results[$index]['data'] = array(
			'TestM17nBSave' => array(
				'id' => '1',
				'language_id' => $results[$index]['langId'],
				'key' => 'test_1',
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge(
			array(
				'TestM17nBSave' => (new TestM17nBSaveFixture())->records[0]
			),
			array(
				'TestM17nBSave' => array(
					'is_translation' => true,
				),
			)
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSave' => array(
				'is_original_copy' => false,
				'is_origin' => false,
				'is_translation' => true,
				'id' => '5',
			),
		));

		// * 4.「日本語、英語」のデータを日本語で編集
		$index = 4;
		$results[$index]['langId'] = '2';
		$results[$index]['data'] = array(
			'TestM17nBSave' => array(
				'id' => '3',
				'language_id' => $results[$index]['langId'],
				'key' => 'test_3',
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSave' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => true,
				'created' => (new TestM17nBSaveFixture())->records[2]['created'],
				'created_user' => (new TestM17nBSaveFixture())->records[2]['created_user'],
			),
		));
		$results[$index]['expected'][1] = array(
			'TestM17nBSave' => (new TestM17nBSaveFixture())->records[3]
		);

		return $results;
	}

}
