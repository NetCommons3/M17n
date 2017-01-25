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

App::uses('M17nModelTestCase', 'M17n.TestSuite');
App::uses('TestM17nWorkflowFixture', 'M17n.Test/Fixture');

/**
 * M17nBehavior::save()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Model\Behavior\M17nBehavior
 */
class M17nBehaviorSaveWorkflowTest extends M17nModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.m17n.test_m17n_workflow',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'm17n';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'M17n', 'TestM17n');
		$this->TestModel = ClassRegistry::init('TestM17n.TestM17nWorkflow');
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
			'TestM17nWorkflow' => array(
				'language_id' => $results[$index]['langId'],
				'key' => 'add_key_1',
				'status' => '1',
				'is_active' => true,
				'is_latest' => true,
				'content' => 'Test add 1',
			),
		);
		$results[$index]['expected'][0] = Hash::merge($results[$index]['data'], array(
			'TestM17nWorkflow' => array(
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
			'TestM17nWorkflow' => array(
				'language_id' => $results[$index]['langId'],
				'key' => 'test_1',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = array(
			'TestM17nWorkflow' => (new TestM17nWorkflowFixture())->records[0]
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nWorkflow' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => false,
				'id' => '5',
			),
		));

		// * 2.「日本語のみ」のデータを英語で編集
		$index = 2;
		$results[$index]['langId'] = '1';
		$results[$index]['data'] = array(
			'TestM17nWorkflow' => array(
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
				'TestM17nWorkflow' => (new TestM17nWorkflowFixture())->records[0]
			),
			array(
				'TestM17nWorkflow' => array(
					'is_translation' => true,
				),
			)
		);
		$results[$index]['expected'][1] = Hash::merge($results[$index]['data'], array(
			'TestM17nWorkflow' => array(
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
			'TestM17nWorkflow' => array(
				'language_id' => $results[$index]['langId'],
				'key' => 'test_3',
				'status' => '3',
				'is_active' => false,
				'is_latest' => true,
				'content' => 'Test edit 1',
			),
		);
		$results[$index]['expected'][0] = array(
			'TestM17nWorkflow' => (new TestM17nWorkflowFixture())->records[2]
		);
		$results[$index]['expected'][1] = array(
			'TestM17nWorkflow' => (new TestM17nWorkflowFixture())->records[3]
		);
		$results[$index]['expected'][2] = Hash::merge($results[$index]['data'], array(
			'TestM17nWorkflow' => array(
				'is_original_copy' => false,
				'is_origin' => true,
				'is_translation' => true,
				'id' => '5',
			),
		));

		return $results;
	}

/**
 * save()のテスト
 *
 * @param int $langId 言語ID
 * @param array $data 登録データ
 * @param array $expected 期待値
 * @dataProvider dataProvider
 * @return void
 */
	public function testSave($langId, $data, $expected) {
		//テストデータセット
		Current::write('Language.id', $langId);

		//テスト実施
		$result = $this->TestModel->save($data);
		$this->assertNotEmpty($result);

		$actual = $this->TestModel->find('all', array(
			'recursive' => 1,
			'conditions' => array(
				$this->TestModel->alias . '.key' => $data[$this->TestModel->alias]['key']
			)
		));
		$actual = $this->_parseActual($actual, $expected);

		//チェック
		$this->assertEquals($actual, $expected);
	}

/**
 * $actualをパースする
 *
 * @param array $actual 結果
 * @param array $expected 期待値
 * @return array
 */
	protected function _parseActual($actual, $expected) {
		$actual = Hash::remove($actual, '{n}.TrackableCreator');
		$actual = Hash::remove($actual, '{n}.TrackableUpdater');

		$indexes = array_keys($expected);
		foreach ($indexes as $i) {
			if (! Hash::get($expected, $i . '.' . $this->TestModel->alias . '.created_user')) {
				$actual = Hash::remove($actual, $i . '.' . $this->TestModel->alias . '.created_user');
			}
			if (! Hash::get($expected, $i . '.' . $this->TestModel->alias . '.created')) {
				$actual = Hash::remove($actual, $i . '.' . $this->TestModel->alias . '.created');
			}
			if (! Hash::get($expected, $i . '.' . $this->TestModel->alias . '.modified_user')) {
				$actual = Hash::remove($actual, $i . '.' . $this->TestModel->alias . '.modified_user');
			}
			if (! Hash::get($expected, $i . '.' . $this->TestModel->alias . '.modified')) {
				$actual = Hash::remove($actual, $i . '.' . $this->TestModel->alias . '.modified');
			}
		}

		return $actual;
	}

}
