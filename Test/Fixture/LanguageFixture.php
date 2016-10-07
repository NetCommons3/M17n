<?php
/**
 * LanguageFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * LanguageFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Case\Fixture
 */
class LanguageFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'code' => 'en',
			'weight' => '1',
			'is_active' => true,
			'created_user' => null,
			'created' => '2014-07-03 05:00:39',
			'modified_user' => null,
			'modified' => '2014-07-03 05:00:39'
		),
		array(
			'id' => '2',
			'code' => 'ja',
			'weight' => '2',
			'is_active' => true,
			'created_user' => null,
			'created' => '2014-07-03 05:00:39',
			'modified_user' => null,
			'modified' => '2014-07-03 05:00:39'
		),
		array(
			'id' => '3',
			'code' => 'ch',
			'weight' => '3',
			'is_active' => false,
			'created_user' => null,
			'created' => '2014-07-03 05:00:39',
			'modified_user' => null,
			'modified' => '2014-07-03 05:00:39'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('M17n') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new M17nSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}

}
