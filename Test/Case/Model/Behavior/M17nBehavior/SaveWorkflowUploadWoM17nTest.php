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
App::uses('TestM17nBSaveWorkflowUploadWoM17nFixture', 'M17n.Test/Fixture');
App::uses('UploadFile4m17nFixture', 'M17n.Test/Fixture');
App::uses('UploadFilesContent4m17nFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * PhotoAlbumなど、Uploadを利用しているプラグイン
 * ※UploadFileは、Files.AttachmentBehaviorで事前に登録されている
 *
 * ### actAsの定義
 * ````
 *	public $actsAs = array(
 *		'NetCommons.OriginalKey',
 *		'Workflow.Workflow',
 *		'Files.Attachment' => ['photo'],
 *		'M17n.M17n' => array(
 *			'associations' => array(
 *				'UploadFilesContent' => array(
 *					'class' => 'Files.UploadFilesContent',
 *					'foreignKey' => 'content_id',
 *					'isM17n' => false,
 *				),
 *			),
 *		),
 *	);
 * ````
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveWorkflowUploadWoM17nTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save_workflow_upload_wo_m17n',
		'plugin.m17n.upload_file4m17n',
		'plugin.m17n.upload_files_content4m17n',
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
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSaveWorkflowUploadWoM17n');
	}

/**
 * テストデータ
 *
 * ### 前提
 * UploadFileは、Files.AttachmentBehaviorで事前に登録されていることとする
 *
 * ### テストパタン
 * - 0.コンテンツ新規登録(アップロードあり)
 * - 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
 * - 2.「日本語のみ」のデータを日本語でコンテンツとアップロードのみ編集
 * - 3.「日本語のみ」のデータを英語でコンテンツのみ編集
 * - 4.「日本語のみ」のデータを英語でコンテンツとアップロードを編集
 * - 5.「日本語、英語」のデータを日本語でコンテンツのみ編集
 * - 6.「日本語、英語」のデータを日本語でコンテンツとアップロードのみ編集
 * - 7.「日本語、英語」のデータを英語でコンテンツのみ編集
 * - 8.「日本語、英語」のデータを英語でコンテンツとアップロードを編集
 *
 * ### 戻り値
 *  - langId 言語ID
 *  - data 登録データ
 *  - expected 期待値
 *  - prepare 関連するデータ作成
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		//データ生成
		$this->__newId = (string)(count((new TestM17nBSaveWorkflowUploadWoM17nFixture())->records) + 1);
		$this->__newUploadContentId = (string)(count((new UploadFilesContent4m17nFixture())->records) + 1);
		$this->__newUploadId = (string)(count((new UploadFile4m17nFixture())->records) + 1);
		$this->__newUpload = array(
			'id' => $this->__newUploadId,
			'plugin_key' => 'test_m17n',
			'content_key' => 'add_key_1',
			'field_name' => 'photo',
			'original_name' => 'test_m17n.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'test_m17n.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => '1',
			'download_count' => '1',
			'total_download_count' => '1',
			'room_id' => '2',
			'block_key' => 'block_1',
		);

		$results = array();

		// * 0.コンテンツ新規登録(アップロードあり)
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

		// * 2.「日本語のみ」のデータを日本語でコンテンツとアップロードのみ編集
		$testNo = 2;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 3.「日本語のみ」のデータを英語でコンテンツのみ編集
		$testNo = 3;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 4.「日本語のみ」のデータを英語でコンテンツとアップロードのみ編集
		$testNo = 4;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 5.「日本語、英語」のデータを日本語でコンテンツのみ編集
		$testNo = 5;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 6.「日本語、英語」のデータを日本語でコンテンツとアップロードのみ編集
		$testNo = 6;
		$results[$testNo]['langId'] = '2';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 7.「日本語、英語」のデータを英語でコンテンツのみ編集
		$testNo = 7;
		$results[$testNo]['langId'] = '1';
		$results[$testNo]['data'] = $this->__getData($testNo, $results[$testNo]['langId']);
		$results[$testNo]['expected'] = $this->__getExpected($testNo, $results[$testNo]['langId']);
		$results[$testNo]['prepare'] = $this->__getPrepare($testNo);

		// * 8.「日本語、英語」のデータを英語でコンテンツとアップロードを編集
		$testNo = 8;
		$results[$testNo]['langId'] = '1';
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
 */
	private function __getData($testNo, $langId) {
		if ($testNo === 0) {
			$data = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => array(
					'language_id' => $langId,
					'key' => 'add_key_1',
					'status' => '1',
					'content' => 'Test add 1',
				),
			);
		} elseif (in_array($testNo, [1, 2], true)) {
			$data = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => array(
					'language_id' => $langId,
					'key' => 'test_1',
					'status' => '3',
					'content' => 'Test edit 1',
				),
			);
		} elseif (in_array($testNo, [3, 4], true)) {
			$data = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => array(
					'language_id' => $langId,
					'key' => 'test_1',
					'status' => '1',
					'content' => 'Test edit 1',
				),
			);
		} elseif (in_array($testNo, [5, 6, 7, 8], true)) {
			$data = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => array(
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
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
	private function __getExpected($testNo, $langId) {
		$expected = array();

		if (in_array($testNo, [1, 3], true)) {
			$photo = (new UploadFile4m17nFixture())->records[0];
		} elseif (in_array($testNo, [2, 4], true)) {
			$photo = Hash::merge($this->__newUpload, array('content_key' => 'test_1'));
		} elseif (in_array($testNo, [5, 7], true)) {
			$photo = (new UploadFile4m17nFixture())->records[2];
		} elseif (in_array($testNo, [6, 8], true)) {
			$photo = Hash::merge($this->__newUpload, array('content_key' => 'test_3'));
		}

		if ($testNo === 0) {
			$expected[0] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflowUploadWoM17n' => array(
						'is_active' => true,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => $this->__newId,
					),
					'UploadFile' => array(
						'photo' => $this->__newUpload,
					),
				)
			);
		} elseif (in_array($testNo, [1, 2], true)) {
			$expected[0] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[0],
					array(
						'is_active' => true,
						'is_latest' => false,
					)
				),
				'UploadFile' => array(
					'photo' => (new UploadFile4m17nFixture())->records[0],
				),
			);
			$expected[1] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflowUploadWoM17n' => array(
						'is_active' => false,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => false,
						'id' => $this->__newId,
					),
					'UploadFile' => array(
						'photo' => $photo,
					),
				)
			);
		} elseif (in_array($testNo, [3, 4], true)) {
			$expected[0] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[0],
					array(
						'is_active' => false,
						'is_latest' => false,
						'is_translation' => true,
					)
				),
				'UploadFile' => array(
					'photo' => (new UploadFile4m17nFixture())->records[0],
				),
			);
			$expected[1] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflowUploadWoM17n' => array(
						'is_active' => true,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => false,
						'is_translation' => true,
						'id' => $this->__newId,
					),
					'UploadFile' => array(
						'photo' => $photo,
					),
				)
			);
			$expected[2] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[0],
					array(
						'id' => (string)($this->__newId + 1),
						'is_active' => true,
						'is_latest' => true,
						'is_translation' => true,
					)
				),
				'UploadFile' => array(
					'photo' => $photo,
				),
			);
		} elseif (in_array($testNo, [5, 6], true)) {
			$expected[0] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[2],
					array(
						'is_active' => true,
						'is_latest' => false,
					)
				),
				'UploadFile' => array(
					'photo' => (new UploadFile4m17nFixture())->records[2],
				),
			);
			$expected[1] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[3],
					array(
						'is_active' => false,
						'is_latest' => false,
					)
				),
				'UploadFile' => array(
					'photo' => (new UploadFile4m17nFixture())->records[2],
				),
			);
			$expected[2] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflowUploadWoM17n' => array(
						'id' => $this->__newId,
						'is_active' => false,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => true,
						'is_translation' => true,
					),
					'UploadFile' => array(
						'photo' => $photo,
					),
				)
			);
			$expected[3] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[3],
					array(
						'id' => (string)($this->__newId + 1),
					)
				),
				'UploadFile' => array(
					'photo' => $photo,
				),
			);

		} elseif (in_array($testNo, [7, 8], true)) {
			$expected[0] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[2],
					array(
						'is_active' => false,
						'is_latest' => false,
					)
				),
				'UploadFile' => array(
					'photo' => (new UploadFile4m17nFixture())->records[2],
				),
			);
			$expected[1] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[3],
					array(
						'is_active' => true,
						'is_latest' => false,
					)
				),
				'UploadFile' => array(
					'photo' => (new UploadFile4m17nFixture())->records[2],
				),
			);
			$expected[2] = Hash::merge(
				$this->__getData($testNo, $langId),
				array(
					'TestM17nBSaveWorkflowUploadWoM17n' => array(
						'id' => $this->__newId,
						'is_active' => false,
						'is_latest' => true,
						'is_original_copy' => false,
						'is_origin' => false,
						'is_translation' => true,
					),
					'UploadFile' => array(
						'photo' => $photo,
					),
				)
			);
			$expected[3] = array(
				'TestM17nBSaveWorkflowUploadWoM17n' => Hash::merge(
					(new TestM17nBSaveWorkflowUploadWoM17nFixture())->records[2],
					array(
						'id' => (string)($this->__newId + 1),
					)
				),
				'UploadFile' => array(
					'photo' => $photo,
				),
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
		if ($testNo === 0) {
			$prepare = array(
				'Files.UploadFile' => array($this->__newUpload),
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => $this->__newUploadId,
					),
				),
			);
		} elseif (in_array($testNo, [1, 3], true)) {
			$prepare = array(
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => '1',
					),
				),
			);
		} elseif (in_array($testNo, [2, 4], true)) {
			$prepare = array(
				'Files.UploadFile' => array(
					Hash::merge($this->__newUpload, array('content_key' => 'test_1'))
				),
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => $this->__newUploadId,
					),
				),
			);
		} elseif ($testNo === 5) {
			$prepare = array(
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => '3',
					),
				),
			);
		} elseif ($testNo === 6) {
			$prepare = array(
				'Files.UploadFile' => array(
					Hash::merge($this->__newUpload, array('content_key' => 'test_3'))
				),
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => $this->__newUploadId,
					),
				),
			);
		} elseif ($testNo === 7) {
			$prepare = array(
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => '3',
					),
				),
			);
		} elseif ($testNo === 8) {
			$prepare = array(
				'Files.UploadFile' => array(
					Hash::merge($this->__newUpload, array('content_key' => 'test_3'))
				),
				'Files.UploadFilesContent' => array(
					array(
						'id' => $this->__newUploadContentId,
						'plugin_key' => 'test_m17n',
						'content_id' => $this->__newId,
						'upload_file_id' => $this->__newUploadId,
					),
				),
			);
		}

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

		$testNoes = array_keys($expected);
		foreach ($testNoes as $i) {
			if (! Hash::get($expected, $i . '.UploadFile.photo.created_user')) {
				$actual = Hash::remove($actual, $i . '.UploadFile.photo.created_user');
			}
			if (! Hash::get($expected, $i . '.UploadFile.photo.created')) {
				$actual = Hash::remove($actual, $i . '.UploadFile.photo.created');
			}
			if (! Hash::get($expected, $i . '.UploadFile.photo.modified_user')) {
				$actual = Hash::remove($actual, $i . '.UploadFile.photo.modified_user');
			}
			if (! Hash::get($expected, $i . '.UploadFile.photo.modified')) {
				$actual = Hash::remove($actual, $i . '.UploadFile.photo.modified');
			}
		}

		return $actual;
	}

}
