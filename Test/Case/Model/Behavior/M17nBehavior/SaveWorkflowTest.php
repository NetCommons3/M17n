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
App::uses('TestM17nBSaveWorkflowFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * お知らせなど、ワークフローを使っているプラグイン
 *
 * ### actAsの定義
 * ````
 *	public $actsAs = array(
 *		'Workflow.Workflow',
 *		'M17n.M17n'
 *	);
 * ````
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveWorkflowTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save_workflow',
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
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSaveWorkflow');
	}

/**
 * テストデータ
 *
 * ### テストパタン
 * - 0.コンテンツ新規登録
 * - 1.「日本語のみ」のデータを日本語で編集
 * - 2.「日本語のみ」のデータを英語で編集
 * - 3.「日本語、英語」のデータを日本語で編集
 *
 * ### 戻り値
 * - langId 言語ID
 * - data 登録データ
 * - expected 期待値
 * - prepare 関連するデータ作成
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		//データ生成
		$results = array();

		// * 0.コンテンツ新規登録
		$testNo = 0;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = array();

		// * 1.「日本語のみ」のデータを日本語で編集
		$testNo = 1;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = array();

		// * 2.「日本語のみ」のデータを英語で編集
		$testNo = 2;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = array();

		// * 3.「日本語、英語」のデータを日本語で編集
		$testNo = 3;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = array();

		return $results;
	}

/**
 * テストデータ（$data）を取得する
 *
 * @param int $testNo テストNo
 * @param int $langId 言語ID
 * @return array
 */
	private function __getData($testNo, $langId) {
		$data = array();

		if ($testNo === 0) {
			$data = array(
				'TestM17nBSaveWorkflow' => array(
					'language_id' => $langId,
					'key' => 'add_key_1',
					'status' => '1',
					'content' => 'Test add 1',
				),
			);
		} elseif ($testNo === 1) {
			$data = array(
				'TestM17nBSaveWorkflow' => array(
					'language_id' => $langId,
					'key' => 'test_1',
					'status' => '3',
					'content' => 'Test edit 1',
				),
			);
		} elseif ($testNo === 2) {
			$data = array(
				'TestM17nBSaveWorkflow' => array(
					'language_id' => $langId,
					'key' => 'test_1',
					'status' => '1',
					'content' => 'Test edit 1',
				),
			);
		} elseif ($testNo === 3) {
			$data = array(
				'TestM17nBSaveWorkflow' => array(
					'language_id' => $langId,
					'key' => 'test_3',
					'status' => '3',
					'content' => 'Test edit 1',
				),
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

		if ($testNo === 0) {
			$expected[0] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_active' => true,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => '5',
					),
				)
			);
		} elseif ($testNo === 1) {
			$expected[0] = Hash::merge(
				array(
					'TestM17nBSaveWorkflow' => (new TestM17nBSaveWorkflowFixture())->records[0]
				),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_active' => true,
						'is_latest' => false,
					),
				)
			);
			$expected[1] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_active' => false,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => '5',
					),
				)
			);
		} elseif ($testNo === 2) {
			$expected[0] = Hash::merge(
				array(
					'TestM17nBSaveWorkflow' => (new TestM17nBSaveWorkflowFixture())->records[0]
				),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_translation' => true,
					),
				)
			);
			$expected[1] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_active' => true,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => false,
						'is_translation' => true,
						'id' => '5',
					),
				)
			);
		} elseif ($testNo === 3) {
			$expected[0] = Hash::merge(
				array(
					'TestM17nBSaveWorkflow' => (new TestM17nBSaveWorkflowFixture())->records[2]
				),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_active' => true,
						'is_latest' => false,
					),
				)
			);
			$expected[1] = Hash::merge(
				array(
					'TestM17nBSaveWorkflow' => (new TestM17nBSaveWorkflowFixture())->records[3]
				),
				array()
			);
			$expected[2] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflow' => array(
						'is_active' => false,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => true,
						'id' => '5',
					),
				)
			);
		}

		return $expected;
	}

}
