<?php
/**
 * M17nModelTestCase TestCase
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//@codeCoverageIgnoreStart;
App::uses('M17nModelTestCase', 'M17n.TestSuite');
//@codeCoverageIgnoreEnd;

/**
 * M17nModelTestCase TestCase
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\TestSuite
 * @codeCoverageIgnore
 */
class M17nBehaviorSaveTestBase extends M17nModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	private $__fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'm17n';

/**
 * Fixtures load
 *
 * @param string $name The name parameter on PHPUnit_Framework_TestCase::__construct()
 * @param array  $data The data parameter on PHPUnit_Framework_TestCase::__construct()
 * @param string $dataName The dataName parameter on PHPUnit_Framework_TestCase::__construct()
 * @return void
 */
	public function __construct($name = null, array $data = array(), $dataName = '') {
		if (! isset($this->fixtures)) {
			$this->fixtures = array();
		}
		$this->fixtures = array_merge($this->__fixtures, $this->fixtures);
		parent::__construct($name, $data, $dataName);
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

		$alias = $this->TestModel->alias;
		$actual = $this->TestModel->find('all', array(
			'recursive' => 1,
			'conditions' => array(
				$alias . '.' . $this->fieldKey => $data[$alias][$this->fieldKey]
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
