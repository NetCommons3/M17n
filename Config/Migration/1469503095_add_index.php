<?php
/**
 * AddIndex migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * AddIndex migration
 *
 * @package NetCommons\M17n\Config\Migration
 */
class AddIndex extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'languages' => array(
					'is_active' => array('type' => 'boolean', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'Visible from user or not.
Only user w/ administrator role can edit this flag whether it\'s true or false.'),
				),
			),
			'create_field' => array(
				'languages' => array(
					'indexes' => array(
						'is_active' => array('column' => 'is_active', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'languages' => array(
					'is_active' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => 'Visible from user or not.
Only user w/ administrator role can edit this flag whether it\'s true or false.'),
				),
			),
			'drop_field' => array(
				'languages' => array('indexes' => array('is_active')),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
