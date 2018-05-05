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
App::uses('TestM17nBSaveWorkflowTagCategoryFixture', 'M17n.Test/Fixture');
App::uses('Tag4m17nFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * FAQなど、カテゴリーを利用しているプラグイン
 *
 * ### actAsの定義
 * ````
 *	public $actsAs = array(
 *		'Workflow.Workflow',
 *		'M17n.M17n' => array(
 *			'commonFields' => array(
 *				'category_id', 'title_icon',
 *			),
 *			'associations' => array(
 *				'TagsContent' => array(
 *					'class' => 'Tags.TagsContent',
 *					'foreignKey' => 'content_id',
 *					'fieldForIdentifyPlugin' => array('field' => 'model', 'value' => 'BlogEntry'),
 *					'isM17n' => true
 *				),
 *			),
 *			'afterCallback' => false,
 *		),
 *	);
 * ````
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveWorkflowTagCategoryTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save_workflow_tag_category',
		'plugin.m17n.tag4m17n',
		'plugin.m17n.tags_content4m17n',
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
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSaveWorkflowTagCategory');
	}

/**
 * テストデータ
 *
 * ### テストパタン
 * - 0.コンテンツ新規登録
 * - 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
 * - 2.「日本語のみ」のデータを日本語でカテゴリIDとコンテンツを編集
 * - 3.「日本語のみ」のデータを日本語でタグとコンテンツを編集
 * - 4.「日本語のみ」のデータを日本語でタグ・カテゴリ・コンテンツを編集
 * - 5.「日本語のみ」のデータを英語でコンテンツのみ編集
 * - 6.「日本語のみ」のデータを英語でカテゴリIDとコンテンツを編集
 * - 7.「日本語のみ」のデータを英語でタグとコンテンツを編集
 * - 8.「日本語のみ」のデータを英語でタグ・カテゴリ・コンテンツを編集
 * - 9.「日本語、英語」のデータを日本語でコンテンツのみ編集
 * - 10.「日本語、英語」のデータを日本語でコンテンツのみ編集
 * - 11.「日本語、英語」のデータを日本語でカテゴリIDとコンテンツを編集
 * - 12.「日本語、英語」のデータを日本語でタグとコンテンツを編集
 * - 13.「日本語、英語」のデータを日本語でタグ・カテゴリ・コンテンツを編集
 * - 14.「日本語、英語」のデータを英語でコンテンツのみ編集
 * - 15.「日本語、英語」のデータを英語でカテゴリIDとコンテンツを編集
 * - 16.「日本語、英語」のデータを英語でタグとコンテンツを編集
 * - 17.「日本語、英語」のデータを英語でタグ・カテゴリ・コンテンツを編集
 *
 * ### 戻り値
 * - langId 言語ID
 * - data 登録データ
 * - expected 期待値
 * - prepare 関連するデータ作成
 *
 * @return array テストデータ
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	public function dataProvider() {
		//データ生成
		$this->__newId = (string)(count((new TestM17nBSaveWorkflowTagCategoryFixture())->records) + 1);
		$this->__newTagId = (string)(count((new Tag4m17nFixture())->records) + 1);
		$this->__addTagName = 'AddTag';
		$results = array();

		// * 0.コンテンツ新規登録
		$testNo = 0;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
		$testNo = 1;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 2.「日本語のみ」のデータを日本語でカテゴリIDとコンテンツを編集
		$testNo = 2;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 3.「日本語のみ」のデータを日本語でタグとコンテンツを編集
		$testNo = 3;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 4.「日本語のみ」のデータを日本語でタグ・カテゴリ・コンテンツを編集
		$testNo = 4;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 5.「日本語のみ」のデータを英語でコンテンツのみ編集
		$testNo = 5;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 6.「日本語のみ」のデータを英語でカテゴリIDとコンテンツを編集
		$testNo = 6;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 7.「日本語のみ」のデータを英語でタグとコンテンツを編集
		$testNo = 7;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 8.「日本語のみ」のデータを英語でタグ・カテゴリ・コンテンツを編集
		$testNo = 8;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 9.「日本語、英語」のデータを日本語でコンテンツのみ編集
		$testNo = 9;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 10.「日本語、英語」のデータを日本語でカテゴリIDとコンテンツを編集
		$testNo = 10;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 11.「日本語、英語」のデータを日本語でタグとコンテンツを編集
		$testNo = 11;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 12.「日本語、英語」のデータを日本語でタグ・カテゴリ・コンテンツを編集
		$testNo = 12;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 13.「日本語、英語」のデータを英語でコンテンツのみ編集
		$testNo = 13;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 14.「日本語、英語」のデータを英語でカテゴリIDとコンテンツを編集
		$testNo = 14;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 15.「日本語、英語」のデータを英語でタグとコンテンツを編集
		$testNo = 15;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 16.「日本語、英語」のデータを英語でタグ・カテゴリ・コンテンツを編集
		$testNo = 16;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		return $results;
	}

/**
 * テストの実行
 *
 * @param array $data テストデータ
 * @return void
 */
	protected function _executeTestSave($data) {
		$savedData = $this->TestModel->save($data);
		$this->assertNotEmpty($savedData);

		$this->TestModel->set($savedData);
		$this->TestModel->saveM17nData();
	}

/**
 * テストデータ（$data）を取得する
 *
 * @param int $testNo テストNo
 * @param int $langId 言語ID
 * @return array
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
	private function __getData($testNo, $langId) {
		$data = array();
		if (in_array($testNo, [1, 2, 3, 4, 5, 6, 7, 8], true)) {
			$key = 'test_1';
		} elseif (in_array($testNo, [9, 10, 11, 12, 13, 14, 15, 16], true)) {
			$key = 'test_3';
		}

		if ($testNo === 0) {
			// * 0.コンテンツ新規登録
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '1',
					'key' => 'add_key_1',
					'status' => '1',
					'content' => 'Test add 1',
				),
				'Tag' => array(
					0 => array('name' => $this->__addTagName),
				)
			);
		} elseif (in_array($testNo, [1, 5, 9], true)) {
			// * 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
			// * 5.「日本語のみ」のデータを英語でコンテンツのみ編集
			// * 9.「日本語、英語」のデータを日本語でコンテンツのみ編集
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '1',
					'key' => $key,
					'status' => '3',
					'content' => 'Test edit 1',
				),
				'Tag' => array(
					0 => array('name' => (new Tag4m17nFixture())->records[0]['name']),
				)
			);
		} elseif (in_array($testNo, [2, 6, 10], true)) {
			// * 2.「日本語のみ」のデータを日本語でカテゴリIDとコンテンツを編集
			// * 6.「日本語のみ」のデータを英語でカテゴリIDとコンテンツを編集
			// * 10.「日本語、英語」のデータを日本語でカテゴリIDとコンテンツを編集
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '2',
					'key' => $key,
					'status' => '1',
					'content' => 'Test edit 1',
				),
				'Tag' => array(
					0 => array('name' => (new Tag4m17nFixture())->records[0]['name']),
				)
			);
		} elseif (in_array($testNo, [3, 7, 11, 15], true)) {
			// * 3.「日本語のみ」のデータを日本語でタグとコンテンツを編集
			// * 7.「日本語のみ」のデータを英語でタグとコンテンツを編集
			// * 11.「日本語、英語」のデータを日本語でタグとコンテンツを編集
			// * 15.「日本語、英語」のデータを英語でタグとコンテンツを編集
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '1',
					'key' => $key,
					'status' => '1',
					'content' => 'Test edit 1',
				),
				'Tag' => array(
					0 => array('name' => $this->__addTagName),
				)
			);
		} elseif (in_array($testNo, [4, 8, 12, 16], true)) {
			// * 4.「日本語のみ」のデータを日本語でタグ・カテゴリ・コンテンツを編集
			// * 8.「日本語のみ」のデータを英語でタグ・カテゴリ・コンテンツを編集
			// * 12.「日本語、英語」のデータを日本語でタグ・カテゴリ・コンテンツを編集
			// * 16.「日本語、英語」のデータを英語でタグ・カテゴリ・コンテンツを編集
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '2',
					'key' => $key,
					'status' => '1',
					'content' => 'Test edit 1',
				),
				'Tag' => array(
					0 => array('name' => $this->__addTagName),
				)
			);
		} elseif ($testNo === 13) {
			// * 13.「日本語、英語」のデータを英語でコンテンツのみ編集
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '1',
					'key' => $key,
					'status' => '3',
					'content' => 'Test edit 1',
				),
				'Tag' => array(
					0 => array('name' => (new Tag4m17nFixture())->records[1]['name']),
				)
			);
		} elseif ($testNo === 14) {
			// * 14.「日本語、英語」のデータを英語でカテゴリIDとコンテンツを編集
			$data = array(
				'TestM17nBSaveWorkflowTagCategory' => array(
					'language_id' => $langId,
					'block_id' => '1',
					'category_id' => '2',
					'key' => $key,
					'status' => '1',
					'content' => 'Test edit 1',
				),
				'Tag' => array(
					0 => array('name' => (new Tag4m17nFixture())->records[1]['name']),
				)
			);
		}

		return $data;
	}

/**
 * テストデータの期待値（$expected）を取得する
 *
 * @param int $testNo テストNo
 * @param int $langId 言語ID
 * @return array
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	private function __getExpected($testNo, $langId) {
		$expected = array();

		if (in_array($testNo, [0, 3, 4, 7, 8, 11, 12, 15, 16], true)) {
			$saveTagData = array(
				0 => array(
					'id' => $this->__newTagId,
					'block_id' => '1',
					'model' => 'TestM17nBSaveWorkflowTagCategory',
					'key' => Security::hash('Tag', 'md5'),
					'language_id' => $langId,
					'is_origin' => true,
					'is_translation' => false,
					'is_original_copy' => false,
				)
			);
		} elseif (in_array($testNo, [1, 2, 5, 6, 9, 10], true)) {
			$saveTagData = array(
				0 => (new Tag4m17nFixture())->records[0],
			);
		} elseif (in_array($testNo, [13, 14], true)) {
			$saveTagData = array(
				0 => (new Tag4m17nFixture())->records[1],
			);
		}

		$data = $this->__getData($testNo, $langId);
		$isActive = ($data['TestM17nBSaveWorkflowTagCategory']['status'] === '1');
		$categoryId = $data['TestM17nBSaveWorkflowTagCategory']['category_id'];

		if ($testNo === 0) {
			// * 0.コンテンツ新規登録
			$expected[0] = Hash::merge(
				$data,
				array(
					'TestM17nBSaveWorkflowTagCategory' => array(
						'is_active' => $isActive,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => $this->__newId,
					),
					'Tag' => $saveTagData,
				)
			);
		} elseif (in_array($testNo, [1, 2, 3, 4], true)) {
			// * 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
			// * 2.「日本語のみ」のデータを日本語でカテゴリIDとコンテンツを編集
			// * 3.「日本語のみ」のデータを日本語でタグとコンテンツを編集
			// * 4.「日本語のみ」のデータを日本語でタグ・カテゴリ・コンテンツを編集
			$expected[0] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[0],
					array(
						'is_active' => !$isActive,
						'is_latest' => false,
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[0],
				)
			);
			$expected[1] = Hash::merge(
				$data,
				array(
					'TestM17nBSaveWorkflowTagCategory' => array(
						'is_active' => $isActive,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => $this->__newId,
					),
					'Tag' => $saveTagData,
				)
			);
		} elseif (in_array($testNo, [5, 6, 7, 8], true)) {
			// * 5.「日本語のみ」のデータを英語でコンテンツのみ編集
			// * 6.「日本語のみ」のデータを英語でカテゴリIDとコンテンツを編集
			// * 7.「日本語のみ」のデータを英語でタグとコンテンツを編集
			// * 8.「日本語のみ」のデータを英語でタグ・カテゴリ・コンテンツを編集
			$expected[0] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[0],
					array(
						'is_active' => false,
						'is_latest' => false,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => true,
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[0],
				)
			);
			$expected[1] = Hash::merge(
				$data,
				array(
					'TestM17nBSaveWorkflowTagCategory' => array(
						'is_active' => $isActive,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => false,
						'is_translation' => true,
						'id' => $this->__newId,
					),
					'Tag' => $saveTagData
				)
			);
			$expected[2] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[0],
					array(
						'category_id' => $categoryId,
						'is_active' => true,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => true,
						'id' => (string)($this->__newId + 1),
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[0],
				)
			);
		} elseif (in_array($testNo, [9, 10, 11, 12], true)) {
			// * 9.「日本語、英語」のデータを日本語でコンテンツのみ編集
			// * 10.「日本語、英語」のデータを日本語でカテゴリIDとコンテンツを編集
			// * 11.「日本語、英語」のデータを日本語でタグとコンテンツを編集
			// * 12.「日本語、英語」のデータを日本語でタグ・カテゴリ・コンテンツを編集
			$expected[0] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[2],
					array(
						'is_active' => !$isActive,
						'is_latest' => false,
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[0],
				)
			);
			$expected[1] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[3],
					array(
						'is_active' => false,
						'is_latest' => false,
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[1],
				)
			);
			$expected[2] = Hash::merge(
				$data,
				array(
					'TestM17nBSaveWorkflowTagCategory' => array(
						'is_active' => $isActive,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => true,
						'id' => $this->__newId,
					),
					'Tag' => $saveTagData
				)
			);
			$expected[3] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[3],
					array(
						'category_id' => $categoryId,
						'is_active' => true,
						'is_latest' => true,
						'id' => (string)($this->__newId + 1),
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[1],
				)
			);
		} elseif (in_array($testNo, [13, 14, 15, 16], true)) {
			// * 13.「日本語、英語」のデータを英語でコンテンツのみ編集
			// * 14.「日本語、英語」のデータを英語でカテゴリIDとコンテンツを編集
			// * 15.「日本語、英語」のデータを英語でタグとコンテンツを編集
			// * 16.「日本語、英語」のデータを英語でタグ・カテゴリ・コンテンツを編集
			$expected[0] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[2],
					array(
						'is_active' => false,
						'is_latest' => false,
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[0],
				)
			);
			$expected[1] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[3],
					array(
						'is_active' => !$isActive,
						'is_latest' => false,
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[1],
				)
			);
			$expected[2] = Hash::merge(
				$data,
				array(
					'TestM17nBSaveWorkflowTagCategory' => array(
						'is_active' => $isActive,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => false,
						'is_translation' => true,
						'id' => $this->__newId,
					),
					'Tag' => $saveTagData
				)
			);
			$expected[3] = array(
				'TestM17nBSaveWorkflowTagCategory' => Hash::merge(
					(new TestM17nBSaveWorkflowTagCategoryFixture())->records[2],
					array(
						'category_id' => $categoryId,
						'is_active' => true,
						'is_latest' => true,
						'id' => (string)($this->__newId + 1),
					)
				),
				'Tag' => array(
					0 => (new Tag4m17nFixture())->records[0],
				)
			);
		}

		return $expected;
	}

/**
 * テストデータの初期値（$prepare）を取得する
 *
 * @param int $testNo テストNo
 * @return array
 */
	private function __getPrepare($testNo) {
		$prepare = array();
		return $prepare;
	}

/**
 * $actualをパースする
 *
 * @param array $actual 結果
 * @param array $expected 期待値
 * @return array
 */
	protected function _parseActual($actual, $expected) {
		$actual = parent::_parseActual($actual, $expected);

		$actual = Hash::remove($actual, '{n}.Tag.{n}.created_user');
		$actual = Hash::remove($actual, '{n}.Tag.{n}.created');
		$actual = Hash::remove($actual, '{n}.Tag.{n}.modified_user');
		$actual = Hash::remove($actual, '{n}.Tag.{n}.modified');

		return $actual;
	}

}
