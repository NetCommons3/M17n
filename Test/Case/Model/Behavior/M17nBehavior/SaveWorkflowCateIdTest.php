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
App::uses('TestM17nBSaveWorkflowCateIdFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * FAQなど、カテゴリーを利用しているプラグイン
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveWorkflowCateIdTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save_workflow_cate_id',
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
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSaveWorkflowCateId');
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
			'TestM17nBSaveWorkflowCateId' => array(
				'language_id' => $results[$index]['langId'],
				'category_id' => null,
				'key' => 'add_key_1',
				'status' => '1',
				'is_active' => true,
				'is_latest' => true,
				'content' => 'Test add 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowCateId' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => false,
				'id' => '6',
			),
		));
		$results[$index]['prepare'] = array();

		// * 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
		$index = 1;
		$results[$index]['langId'] = '2';
		$results[$index]['data'] = array(
			'TestM17nBSaveWorkflowCateId' => array(
				'language_id' => $results[$index]['langId'],
				'category_id' => null,
				'key' => 'test_1',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = array(
			'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[0]
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowCateId' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => false,
				'id' => '6',
			),
		));
		$results[$index]['prepare'] = array();

		// * 2.「日本語のみ」のデータを英語でコンテンツのみ編集
		$index = 2;
		$results[$index]['langId'] = '1';
		$results[$index]['data'] = array(
			'TestM17nBSaveWorkflowCateId' => array(
				'language_id' => $results[$index]['langId'],
				'category_id' => null,
				'key' => 'test_1',
				'status' => '1',
				'is_active' => true,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge(
			array(
				'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[0]
			),
			array(
				'TestM17nBSaveWorkflowCateId' => array(
					'is_translation' => true,
				),
			)
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowCateId' => array(
				'is_original_copy' => false,
				'is_origin' => false,
				'is_translation' => true,
				'id' => '6',
			),
		));
		$results[$index]['prepare'] = array();

		// * 4.「日本語、英語」のデータを日本語でコンテンツのみ編集
		$index = 4;
		$results[$index]['langId'] = '2';
		$results[$index]['data'] = array(
			'TestM17nBSaveWorkflowCateId' => array(
				'language_id' => $results[$index]['langId'],
				'category_id' => null,
				'key' => 'test_3',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = array(
			'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[2]
		);
		$results[$index]['expected'][1] = array(
			'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[3]
		);
		$results[$index]['expected'][2] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowCateId' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => true,
				'id' => '6',
			),
		));
		$results[$index]['prepare'] = array();

		// * 5.「日本語のみ」のデータを日本語でカテゴリIDとコンテンツを編集
		$index = 5;
		$results[$index]['langId'] = '2';
		$results[$index]['data'] = array(
			'TestM17nBSaveWorkflowCateId' => array(
				'language_id' => $results[$index]['langId'],
				'category_id' => '2',
				'key' => 'test_1',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = array(
			'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[0]
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowCateId' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => false,
				'id' => '6',
			),
		));
		$results[$index]['prepare'] = array();

		// * 6.「日本語のみ」のデータを英語でカテゴリーIDとコンテンツを編集
		$index = 6;
		$results[$index]['langId'] = '1';
		$results[$index]['data'] = array(
			'TestM17nBSaveWorkflowCateId' => array(
				'language_id' => $results[$index]['langId'],
				'category_id' => '2',
				'key' => 'test_1',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge(
			array(
				'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[0]
			),
			array(
				'TestM17nBSaveWorkflowCateId' => array(
					'is_translation' => true,
					'is_active' => false,
					'is_latest' => false,
				),
			)
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowCateId' => array(
				'category_id' => '2',
				'is_original_copy' => false,
				'is_origin' => false,
				'is_translation' => true,
				'id' => '6',
			),
		));
		$results[$index]['expected'][2] = Hash::merge(
			array(
				'TestM17nBSaveWorkflowCateId' => (new TestM17nBSaveWorkflowCateIdFixture())->records[0]
			),
			array(
				'TestM17nBSaveWorkflowCateId' => array(
					'category_id' => '2',
					'is_latest' => true,
					'is_translation' => true,
					'id' => '7',
				),
			)
		);
		$results[$index]['prepare'] = array();

		return $results;
	}

}
