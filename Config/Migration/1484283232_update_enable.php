<?php
/**
 * 多言語化対応
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * 多言語化対応
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Config\Migration
 */
class UpdateEnable extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'update_enable';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
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
		$Language = $this->generateModel('Language');

		if ($direction === 'up') {
			$update = array(
				'is_active' => false
			);
			$conditions = array(
				'id' => '1'
			);
			if (! $Language->updateAll($update, $conditions)) {
				return false;
			}

			$update = array(
				'is_active' => true
			);
			$conditions = array(
				'id' => '2'
			);
			if (! $Language->updateAll($update, $conditions)) {
				return false;
			}
		}

		return true;
	}
}
