<?php
/**
 * MailsMigration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * MailsMigration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Config\Migration
 */
class M17nAddFieldsMigration extends NetCommonsMigration {

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		if ($direction === 'up') {
			foreach ($this->models as $modelName => $addConditions) {
				$model = $this->generateModel($modelName);

				$update = array('is_translation' => true);
				if ($model->hasField('key')) {
					$conditions = array('LENGTH(' . $model->alias . '.`key`) != 32');
				} else {
					$conditions = array();
				}
				$conditions = Hash::merge($conditions, $addConditions);
				if (! $model->updateAll($update, $conditions)) {
					return false;
				}

				$update = array('is_origin' => false);
				$conditions = array($model->alias . '.language_id !=' => '2');
				$conditions = Hash::merge($conditions, $addConditions);
				if (! $model->updateAll($update, $conditions)) {
					return false;
				}
			}
		}
		return true;
	}
}
