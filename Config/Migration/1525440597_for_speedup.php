<?php
/**
 * 速度改善のためのインデックス見直し
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * 速度改善のためのインデックス見直し
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Config\Migration
 */
class ForSpeedup extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'for_speedup';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'languages' => array('indexes' => array('is_active')),
			),
			'create_field' => array(
				'languages' => array(
					'indexes' => array(
						'is_active' => array('column' => array('is_active', 'weight', 'code', 'id'), 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'languages' => array(
					'indexes' => array(
						'is_active' => array(),
					),
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
