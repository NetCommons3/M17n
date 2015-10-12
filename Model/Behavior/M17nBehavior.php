<?php
/**
 * M17nBehavior
 *
 * @property OriginalKey $OriginalKey
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('OriginalKeyBehavior', 'NetCommons.Model/Behavior');

/**
 * M17nBehavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package  NetCommons\M17n\Model\Befavior
 */
class M17nBehavior extends OriginalKeyBehavior {

/**
 * オリジナルデータ
 *
 * @var array
 */
	private $__originalData;

/**
 * 更新前の最新データ
 *
 * @var array
 */
	private $__beforeLastestData;

/**
 * ターゲット言語リスト
 *
 * @var array|bool 新規登録の場合、beforeSaveでtrueをセットし、afterSaveで言語リストを取得する
 */
	private $__target;

/**
 * Setup this behavior with the specified configuration settings.
 *
 * @param Model $model Model using this behavior
 * @param array $config Configuration settings for $model
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		$this->settings = Hash::merge($this->settings, $config);
		if (! isset($this->settings['associations'])) {
			$this->settings['associations'] = array();
		}
	}

/**
 * beforeSave is called before a model is saved. Returning false from a beforeSave callback
 * will abort the save operation.
 *
 * @param Model $model Model using this behavior
 * @param array $options Options passed from Model::save().
 * @return mixed False if the operation should abort. Any other result will continue.
 * @see Model::save()
 */
	public function beforeSave(Model $model, $options = array()) {
		parent::beforeSave($model, $options);

		if (! $model->hasField('key') || ! $model->hasField('language_id') || ! isset($model->data[$model->alias]['key']) ||
				$model->data[$model->alias]['key'] === '') {
			return true;
		}

		$currentData = $model->data;
		if (! $this->__getOriginalData($model)) {
			$model->data = $currentData;
			return true;
		}

		$this->__getTargetLanguage($model);

		$model->data = $currentData;
		return true;
	}

/**
 * afterSave is called after a model is saved.
 *
 * @param Model $model Model using this behavior
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return bool
 * @throws InternalErrorException
 * @see Model::save()
 */
	public function afterSave(Model $model, $created, $options = array()) {
		parent::afterSave($model, $created, $options);

		if (! $this->__target) {
			return true;
		}

		$currentData = $model->data;

		foreach ($this->__target as $target) {
			$data = $currentData;
			//idのセット
			if (isset($target[$model->alias]['id'])) {
				$data[$model->alias]['id'] = $target[$model->alias]['id'];
			} else {
				$data[$model->alias]['id'] = null;
			}
			//language_idのセット
			if (isset($target[$model->alias]['language_id'])) {
				$data[$model->alias]['language_id'] = $target[$model->alias]['language_id'];
			}
			//frame_idのセット
			$data = $this->__getFrameId($model, $data, $target);

			//block_id取得
			$data = $this->__getBlockId($model, $data, $target);

			//その他のid取得
			$data = $this->__getAssociationsId($model, $data);

			//登録処理
			$model->create();
			if (! $model->save($data[$model->alias], array('validate' => false, 'callbacks' => false))) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		$model->data = $currentData;

		return true;
	}

/**
 * オリジナルデータ取得
 *
 * @param Model $model Model using this behavior
 * @return bool
 */
	private function __getOriginalData(Model $model) {
		$this->__originalData = $model->find('first', array(
			'recursive' => 0,
			'conditions' => array($model->alias . '.key' => $model->data[$model->alias]['key']),
			'order' => array($model->alias . '.id' => 'asc'),
		));

		if (! $this->__originalData) {
			$languages = Current::readM17n(null, 'Language');
			$this->__target = array();
			foreach ($languages as $language) {
				if ($language['Language']['id'] === Current::read('Language.id')) {
					continue;
				}
				$options = array();
				$options[$model->alias]['language_id'] = $language['Language']['id'];
				if ($model->hasField('frame_id')) {
					$options['Frame']['id'] = Current::readM17n($language['Language']['id'], 'Frame', 'id');
					$options['Frame']['language_id'] = $language['Language']['id'];
				}
				if ($model->hasField('block_id')) {
					$options['Block']['id'] = Current::readM17n($language['Language']['id'], 'Block', 'id');
					$options['Block']['language_id'] = $language['Language']['id'];
				}
				$this->__target[] = $model->createAll($options);
			}
			return false;
		}

		if ($this->__originalData[$model->alias]['language_id'] !== Current::read('Language.id')) {
			return false;
		}

		return true;
	}

/**
 * ターゲットデータ取得
 *
 * @param Model $model Model using this behavior
 * @return void
 */
	private function __getTargetLanguage(Model $model) {
		if ($model->hasField('is_latest')) {
			$this->__beforeLastestData = $model->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'key' => $model->data[$model->alias]['key'],
					'language_id' => Current::read('Language.id'),
					'is_latest' => true,
				),
				'order' => array('id' => 'desc'),
			));

			$fields = array('id', 'language_id');
			$conditions = array(
				$model->alias . '.key' => $model->data[$model->alias]['key'],
				$model->alias . '.language_id !=' => Current::read('Language.id'),
				$model->alias . '.is_latest' => true,
				$model->alias . '.modified' => $this->__beforeLastestData[$model->alias]['modified'],
			);
		} else {
			$fields = array('id', 'language_id');
			$conditions = array(
				$model->alias . '.key' => $model->data[$model->alias]['key'],
				$model->alias . '.language_id !=' => Current::read('Language.id'),
				$model->alias . '.modified' => $this->__originalData[$model->alias]['modified'],
			);
		}

		$this->__target = $model->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
	}

/**
 * frame_id取得
 *
 * @param Model $model Model using this behavior
 * @param array $data 登録データ
 * @param array $target ターゲットデータ
 * @return bool
 */
	private function __getFrameId(Model $model, $data, $target) {
		//frame_idのセット
		if ($model->hasField('frame_id')) {
			if (isset($target['Frame']['language_id'])) {
				$data[$model->alias]['frame_id'] = Current::readM17n($target['Frame']['language_id'], 'Frame', 'id');
			} elseif (isset($target[$model->alias]['frame_id'])) {
				$data[$model->alias]['frame_id'] = $target[$model->alias]['frame_id'];
			}
		}

		return $data;
	}

/**
 * block_id取得
 *
 * @param Model $model Model using this behavior
 * @param array $data 登録データ
 * @param array $target ターゲットデータ
 * @return bool
 */
	private function __getBlockId(Model $model, $data, $target) {
		//block_idのセット
		if ($model->hasField('block_id')) {
			if (isset($target['Block']['language_id'])) {
				$data[$model->alias]['block_id'] = Current::readM17n($target['Block']['language_id'], 'Block', 'id');
			} elseif (isset($target[$model->alias]['block_id'])) {
				$data[$model->alias]['block_id'] = $target[$model->alias]['block_id'];
			}
		}

		return $data;
	}

/**
 * 関連テーブルのID取得
 *
 * @param Model $model Model using this behavior
 * @param array $data 登録データ
 * @return bool
 */
	private function __getAssociationsId(Model $model, $data) {
		//その他のidをセット
		foreach ($this->settings['associations'] as $field => $association) {
			if (! isset($data[$model->alias][$field]) || ! $data[$model->alias][$field]) {
				continue;
			}
			list(, $assocModel) = pluginSplit($association['className']);
			$this->$assocModel = ClassRegistry::init($association['className'], true);
			$origin = $this->$assocModel->find('first', array(
				'recursive' => -1,
				'fields' => array('key'),
				'conditions' => array('id' => $data[$model->alias][$field])
			));
			$conditions = array(
				'key' => $origin[$this->$assocModel->alias]['key'],
				'language_id' => $data[$model->alias][$key]
			);
			if (isset($association['conditions'])) {
				foreach ($association['conditions'] as $key) {
					$conditions[$key] = $data[$model->alias][$key];
				}
			}
			$assoc = $this->$assocModel->find('first', array(
				'recursive' => -1,
				'fields' => array('id'),
				'conditions' => $conditions,
			));
			if ($assoc) {
				$data[$model->alias][$field] = $assoc[$this->$assocModel->alias]['id'];
			} else {
				$data[$model->alias][$field] = 0;
			}
		}

		return $data;
	}

}
