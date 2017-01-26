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
App::uses('TestM17nBSaveWorkflowUploadFixture', 'M17n.Test/Fixture');
App::uses('UploadFile4m17nFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * PhotoAlbumなど、Uploadを利用しているプラグイン
 * ※UploadFileは、Files.AttachmentBehaviorで事前に登録されている
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveWorkflowUploadTest extends M17nBehaviorSaveTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_b_save_workflow_upload',
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
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nBSaveWorkflowUpload');
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
		$newUpload = array(
			'id' => '5',
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

//		// * 0.コンテンツ新規登録(アップロードあり)
//		$index = 0;
//		$results[$index]['langId'] = '2';
//		$results[$index]['data'] = array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'language_id' => $results[$index]['langId'],
//				'key' => 'add_key_1',
//				'status' => '1',
//				'is_active' => true,
//				'is_latest' => true,
//				'content' => 'Test add 1',
//			),
//		);
//		$results[$index]['expected'][0] = Hash::merge($results[$index]['data'], array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'is_original_copy' => false,
//				'is_origin' => true,
//				'is_translation' => false,
//				'id' => '5',
//			),
//			'UploadFile' =>array(
//				'photo' => $newUpload,
//			),
//		));
//		$results[$index]['prepare'] = array(
//			'Files.UploadFile' => array($newUpload),
//			'Files.UploadFilesContent' => array(
//				array(
//					'id' => '5',
//					'plugin_key' => 'test_m17n',
//					'content_id' => '5',
//					'upload_file_id' => '5',
//				),
//			),
//		);
//
//		// * 1.「日本語のみ」のデータを日本語でコンテンツのみ編集
//		$index = 1;
//		$results[$index]['langId'] = '2';
//		$results[$index]['data'] = array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'language_id' => $results[$index]['langId'],
//				'key' => 'test_1',
//				'status' => '3',
//				'is_active' => false,
//				'is_latest' => true,
//				'content' => 'Test edit 1',
//			),
//		);
//		$results[$index]['expected'][0] = array(
//			'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[0],
//			'UploadFile' =>array(
//				'photo' => (new UploadFile4m17nFixture())->records[0],
//			),
//		);
//		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'is_original_copy' => false,
//				'is_origin' => true,
//				'is_translation' => false,
//				'id' => '5',
//			),
//			'UploadFile' =>array(
//				'photo' => (new UploadFile4m17nFixture())->records[0],
//			),
//		));
//		$results[$index]['prepare'] = array(
//			'Files.UploadFilesContent' => array(
//				array(
//					'id' => '5',
//					'plugin_key' => 'test_m17n',
//					'content_id' => '5',
//					'upload_file_id' => '1',
//				),
//			),
//		);
//
//		// * 2.「日本語のみ」のデータを日本語でコンテンツとアップロードのみ編集
//		$index = 2;
//		$results[$index]['langId'] = '2';
//		$results[$index]['data'] = array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'language_id' => $results[$index]['langId'],
//				'key' => 'test_1',
//				'status' => '3',
//				'is_active' => false,
//				'is_latest' => true,
//				'content' => 'Test edit 1',
//			),
//		);
//		$results[$index]['expected'][0] = array(
//			'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[0],
//			'UploadFile' =>array(
//				'photo' => (new UploadFile4m17nFixture())->records[0],
//			),
//		);
//		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'is_original_copy' => false,
//				'is_origin' => true,
//				'is_translation' => false,
//				'id' => '5',
//			),
//			'UploadFile' =>array(
//				'photo' => Hash::merge($newUpload, array('content_key' => 'test_1')),
//			),
//		));
//		$results[$index]['prepare'] = array(
//			'Files.UploadFile' => array(
//				Hash::merge($newUpload, array('content_key' => 'test_1'))
//			),
//			'Files.UploadFilesContent' => array(
//				array(
//					'id' => '5',
//					'plugin_key' => 'test_m17n',
//					'content_id' => '5',
//					'upload_file_id' => '5',
//				),
//			),
//		);

		// * 3.「日本語のみ」のデータを英語でコンテンツのみ編集
		$index = 3;
		$results[$index]['langId'] = '1';
		$results[$index]['data'] = array(
			'TestM17nBSaveWorkflowUpload' => array(
				'language_id' => $results[$index]['langId'],
				'key' => 'test_1',
				'status' => '1',
				'is_active' => true,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge(
			array(
				'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[0],
				'UploadFile' =>array(
					'photo' => (new UploadFile4m17nFixture())->records[0],
				),
			),
			array(
				'TestM17nBSaveWorkflowUpload' => array(
					'is_translation' => true,
				),
			)
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nBSaveWorkflowUpload' => array(
				'is_original_copy' => false,
				'is_origin' => false,
				'is_translation' => true,
				'id' => '5',
			),
			'UploadFile' =>array(
				'photo' => (new UploadFile4m17nFixture())->records[0],
			),
		));
		$results[$index]['prepare'] = array(
			'Files.UploadFilesContent' => array(
				array(
					'id' => '5',
					'plugin_key' => 'test_m17n',
					'content_id' => '5',
					'upload_file_id' => '1',
				),
			),
		);

//		// * 4.「日本語、英語」のデータを日本語でコンテンツのみ編集
//		$index = 4;
//		$results[$index]['langId'] = '2';
//		$results[$index]['data'] = array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'language_id' => $results[$index]['langId'],
//				'key' => 'test_3',
//				'status' => '3',
//				'is_active' => false,
//				'is_latest' => true,
//				'content' => 'Test edit 1',
//			),
//		);
//		$results[$index]['expected'][0] = array(
//			'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[2]
//		);
//		$results[$index]['expected'][1] = array(
//			'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[3]
//		);
//		$results[$index]['expected'][2] = Hash::merge($results[$index]['data'], array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'is_original_copy' => false,
//				'is_origin' => true,
//				'is_translation' => true,
//				'id' => '5',
//			),
//		));
//
//		// * 5.「日本語のみ」のデータを日本語でカテゴリIDとコンテンツを編集
//		$index = 5;
//		$results[$index]['langId'] = '2';
//		$results[$index]['data'] = array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'language_id' => $results[$index]['langId'],
//				'key' => 'test_1',
//				'status' => '3',
//				'is_active' => false,
//				'is_latest' => true,
//				'content' => 'Test edit 1',
//			),
//		);
//		$results[$index]['expected'][0] = array(
//			'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[0]
//		);
//		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'is_original_copy' => false,
//				'is_origin' => true,
//				'is_translation' => false,
//				'id' => '5',
//			),
//		));
//
//		// * 6.「日本語のみ」のデータを英語でカテゴリーIDとコンテンツを編集
//		$index = 6;
//		$results[$index]['langId'] = '1';
//		$results[$index]['data'] = array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'language_id' => $results[$index]['langId'],
//				'key' => 'test_1',
//				'status' => '3',
//				'is_active' => false,
//				'is_latest' => true,
//				'content' => 'Test edit 1',
//			),
//		);
//		$results[$index]['expected'][0] = Hash::merge(
//			array(
//				'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[0]
//			),
//			array(
//				'TestM17nBSaveWorkflowUpload' => array(
//					'is_translation' => true,
//					'is_active' => false,
//					'is_latest' => false,
//				),
//			)
//		);
//		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
//			'TestM17nBSaveWorkflowUpload' => array(
//				'is_original_copy' => false,
//				'is_origin' => false,
//				'is_translation' => true,
//				'id' => '5',
//			),
//		));
//		$results[$index]['expected'][2] = Hash::merge(
//			array(
//				'TestM17nBSaveWorkflowUpload' => (new TestM17nBSaveWorkflowUploadFixture())->records[0]
//			),
//			array(
//				'TestM17nBSaveWorkflowUpload' => array(
//					'is_latest' => true,
//					'is_translation' => true,
//					'id' => '7',
//				),
//			)
//		);

		return $results;
	}

/**
 * save()のテスト
 *
 * @param int $langId 言語ID
 * @param array $data 登録データ
 * @param array $expected 期待値
 * @param array $prepare 関連するデータ作成
 * @dataProvider dataProvider
 * @return void
 */
	public function testSave($langId, $data, $expected, $prepare) {

		parent::testSave($langId, $data, $expected, $prepare);
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

		$indexes = array_keys($expected);
		foreach ($indexes as $i) {
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
