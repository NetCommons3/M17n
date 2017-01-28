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
App::uses('TestM17nBSaveBlockIdFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * BlockIdに関するテスト（keyFieldが指定されている）
 *
 * ### actAsの定義
 * ````
 *	public $actsAs = array(
 *		'M17n.M17n' => array(
 *			'keyField' => 'block_id'
 *		)
 *	);
 * ````
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveBlockIdTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save_block_id',
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
	public $fieldKey = 'block_id';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'M17n', 'TestM17n');
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSaveBlockId');
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
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 1.「日本語のみ」のデータを日本語で編集
		$testNo = 1;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 2.「日本語のみ」のデータを英語で編集
		$testNo = 2;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 3.「日本語、英語」のデータを日本語で編集
		$testNo = 3;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		return $results;
	}

/**
 * テストデータ（$data）を取得する
 *
 * @param int $testNo テストNo
 * @param int $langId 言語ID
 * @return array
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	private function __getData($testNo, $langId) {
		$data = array();

		if ($testNo === 0) {
			// * 0.コンテンツ新規登録
			$data = array(
				'TestM17nBSaveBlockId' => array(
					'language_id' => $langId,
					'block_id' => '99',
					'content' => 'Test add 1',
				),
			);
		} elseif (in_array($testNo, [1, 2], true)) {
			// * 1.「日本語のみ」のデータを日本語で編集
			// * 2.「日本語のみ」のデータを英語で編集
			$data = array(
				'TestM17nBSaveBlockId' => array(
					'id' => '1',
					'language_id' => $langId,
					'block_id' => '1',
					'content' => 'Test edit 1',
				),
			);
		} elseif ($testNo === 3) {
			// * 3.「日本語、英語」のデータを日本語で編集
			$data = array(
				'TestM17nBSaveBlockId' => array(
					'id' => '3',
					'language_id' => $langId,
					'block_id' => '3',
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
			// * 0.コンテンツ新規登録
			$expected[0] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveBlockId' => array(
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => '5',
					),
				)
			);
		} elseif ($testNo === 1) {
			// * 1.「日本語のみ」のデータを日本語で編集
			$expected[0] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveBlockId' => array(
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'created' => (new TestM17nBSaveBlockIdFixture())->records[0]['created'],
						'created_user' => (new TestM17nBSaveBlockIdFixture())->records[0]['created_user'],
					),
				)
			);
		} elseif ($testNo === 2) {
			// * 2.「日本語のみ」のデータを英語で編集
			$expected[0] = array(
				'TestM17nBSaveWorkflowCategory' => Hash::merge(
					(new TestM17nBSaveBlockIdFixture())->records[0],
					array(
						'is_translation' => true,
					)
				)
			);
			$expected[1] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveBlockId' => array(
						'is_original_copy' => false,
						'is_origin' => false,
						'is_translation' => true,
						'id' => '5',
					),
				)
			);
		} elseif ($testNo === 3) {
			// * 3.「日本語、英語」のデータを日本語で編集
			$expected[0] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveBlockId' => array(
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => true,
						'created' => (new TestM17nBSaveBlockIdFixture())->records[2]['created'],
						'created_user' => (new TestM17nBSaveBlockIdFixture())->records[2]['created_user'],
					),
				)
			);
			$expected[1] = array(
				'TestM17nBSaveBlockId' => (new TestM17nBSaveBlockIdFixture())->records[3]
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

}
