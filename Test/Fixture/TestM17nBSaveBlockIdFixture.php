<?php
/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Fixture
 */
class TestM17nBSaveBlockIdFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => ''),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'block_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'is_origin' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'is_translation' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'is_original_copy' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'オリジナルのコピー。言語を新たに追加したときに使用する'),
		'content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => ''),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => ''),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => ''),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => ''),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//日本語のみ
		array(
			'id' => '1',
			'language_id' => '2',
			'block_id' => '1',
			'is_origin' => true,
			'is_translation' => false,
			'is_original_copy' => false,
			'content' => 'Test 1',
			'created_user' => '1',
			'created' => '2017-01-25 00:00:00',
			'modified_user' => '1',
			'modified' => '2017-01-25 00:00:00'
		),
		//英語のみ
		array(
			'id' => '2',
			'language_id' => '1',
			'block_id' => '2',
			'is_origin' => true,
			'is_translation' => false,
			'is_original_copy' => false,
			'content' => 'Test 2',
			'created_user' => '1',
			'created' => '2017-01-25 00:00:00',
			'modified_user' => '1',
			'modified' => '2017-01-25 00:00:00'
		),
		//日本語、英語両方あり
		array(
			'id' => '3',
			'language_id' => '2',
			'block_id' => '3',
			'is_origin' => true,
			'is_translation' => true,
			'is_original_copy' => false,
			'content' => 'Test 3',
			'created_user' => '1',
			'created' => '2017-01-25 00:00:00',
			'modified_user' => '1',
			'modified' => '2017-01-25 00:00:00'
		),
		array(
			'id' => '4',
			'language_id' => '1',
			'block_id' => '3',
			'is_origin' => false,
			'is_translation' => true,
			'is_original_copy' => false,
			'content' => 'Test 3',
			'created_user' => '1',
			'created' => '2017-01-25 00:00:00',
			'modified_user' => '1',
			'modified' => '2017-01-25 00:00:00'
		),
	);

}
