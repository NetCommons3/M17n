<?php
/**
 * LanguagesPageFixture
 *
 * @copyright Copyright 2014, NetCommons Project
 * @author Kohei Teraguchi <kteraguchi@commonsnet.org>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Summary for LanguagesPageFixture
 */
class LanguagesPageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'page_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'language_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'page_id' => 1,
			'language_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 1,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 1,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 2,
			'page_id' => 1,
			'language_id' => 2,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 2,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 2,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 3,
			'page_id' => 2,
			'language_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 3,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 3,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 4,
			'page_id' => 2,
			'language_id' => 2,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 4,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 4,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 5,
			'page_id' => 5,
			'language_id' => 5,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 5,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 5,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 6,
			'page_id' => 6,
			'language_id' => 6,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 6,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 6,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 7,
			'page_id' => 7,
			'language_id' => 7,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 7,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 7,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 8,
			'page_id' => 8,
			'language_id' => 8,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 8,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 8,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 9,
			'page_id' => 9,
			'language_id' => 9,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 9,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 9,
			'modified' => '2014-08-04 04:47:08'
		),
		array(
			'id' => 10,
			'page_id' => 10,
			'language_id' => 10,
			'name' => 'Lorem ipsum dolor sit amet',
			'created_user' => 10,
			'created' => '2014-08-04 04:47:08',
			'modified_user' => 10,
			'modified' => '2014-08-04 04:47:08'
		),
	);

}
