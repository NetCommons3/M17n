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

App::uses('ModelBehavior', 'Model');

/**
 * M17nBehavior
 *
 * 登録するコンテンツデータに対して、対応している言語分登録します。<br>
 *
 * コンテンツデータのテーブルに以下のフィールドを保持してください。
 * * language_id
 *     言語コードに対応するidが登録されます。
 * * is_origin
 *     オリジナルデータとします。
 * * is_translation
 *     翻訳したかどうか。
 *
 * #### サンプルコード
 * ```
 * public $actsAs = array(
 * 	'M17n.M17n' => array(
 * 		'keyField' => 'key', //デフォルト"key"
 *		'allUpdateField' => array('category_id'), //このフィールドが更新された場合、全言語のデータを更新する
 *		'associations' => array(
 *			'(Model名)' => array(
 *				'class' => (クラス名: Plugin.Model形式),
 *				'foreignKey' => (外部キー),
 *			)
 *		),
 * 	),
 * ```
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package  NetCommons\M17n\Model\Befavior
 */
class M17nBehavior extends ModelBehavior {

/**
 * Setup this behavior with the specified configuration settings.
 *
 * @param Model $model Model using this behavior
 * @param array $config Configuration settings for $model
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		parent::setup($model, $config);
		$this->settings[$model->name]['keyField'] = Hash::get($config, 'keyField', 'key');
		$this->settings[$model->name]['allUpdateField'] = Hash::get($config, 'allUpdateField', array());
		$this->settings[$model->name]['associations'] = Hash::get($config, 'associations', array());

		//ビヘイビアの優先順位
		$this->settings['priority'] = 8;
	}

/**
 * M17nフィールドのチェック
 *
 * @param Model $model 呼び出し元Model
 * @return bool
 */
	protected function _hasM17nFields(Model $model) {
		$keyField = $this->settings[$model->name]['keyField'];

		$fields = array(
			$keyField, 'language_id', 'is_origin', 'is_translation',
		);
		if (! $this->__hasFields($model, $fields)) {
			return false;
		}

		return isset($model->data[$model->alias][$keyField]);
	}

/**
 * Workflowフィールドのチェック
 *
 * @param Model $model 呼び出し元Model
 * @return bool
 */
	protected function _hasWorkflowFields(Model $model) {
		$fields = array(
			'status',
			'is_active',
			'is_latest',
		);
		return $this->__hasFields($model, $fields);
	}

/**
 * フィールドのチェック
 *
 * @param Model $model 呼び出し元Model
 * @param arrau $fields フィールド
 * @return bool
 */
	private function __hasFields(Model $model, $fields) {
		foreach ($fields as $field) {
			if (! $model->hasField($field)) {
				return false;
			}
		}
		return true;
	}

/**
 * beforeSave is called before a model is saved. Returning false from a beforeSave callback
 * will abort the save operation.
 *
 * @param Model $model 呼び出し元Model
 * @param array $options Options passed from Model::save().
 * @return mixed False if the operation should abort. Any other result will continue.
 * @see Model::save()
 */
	public function beforeSave(Model $model, $options = array()) {
		if (! $this->_hasM17nFields($model)) {
			return true;
		}

		$keyField = $this->settings[$model->name]['keyField'];

		//チェックするためのWHERE条件
		if ($this->_hasWorkflowFields($model)) {
			$transConditions = array(
				$keyField => $model->data[$model->alias][$keyField],
				'language_id !=' => Current::read('Language.id'),
				'is_latest' => true
			);
			$ownLangConditions = array(
				$keyField => $model->data[$model->alias][$keyField],
				'language_id' => Current::read('Language.id'),
				'is_latest' => true
			);
		} else {
			$transConditions = array(
				$keyField => $model->data[$model->alias][$keyField],
				'language_id !=' => Current::read('Language.id'),
			);
			$ownLangConditions = array(
				$keyField => $model->data[$model->alias][$keyField],
				'language_id' => Current::read('Language.id')
			);
		}

		//データが1件もないことを確認する
		$count = $model->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				$keyField => $model->data[$model->alias][$keyField]
			),
		));
		if ($count <= 0) {
			$model->data[$model->alias]['language_id'] = Current::read('Language.id');
			$model->data[$model->alias]['is_origin'] = true;
			$model->data[$model->alias]['is_translation'] = false;

			return true;
		}

		$model->data[$model->alias]['language_id'] = Current::read('Language.id');

		//翻訳データのチェック
		$count = $model->find('count', array(
			'recursive' => -1,
			'conditions' => $transConditions,
		));
		if ($count > 0) {
			$model->data[$model->alias]['is_translation'] = true;
		} else {
			$model->data[$model->alias]['is_translation'] = false;
		}

		//当言語のデータのチェック
		$data = $model->find('first', array(
			'fields' => array('language_id', 'is_origin', 'is_translation'),
			'recursive' => -1,
			'conditions' => $ownLangConditions,
		));
		if ($data) {
			$model->data[$model->alias]['is_origin'] = $data[$model->alias]['is_origin'];
		} else {
			$model->data[$model->alias]['is_origin'] = false;
			$model->data[$model->alias] = Hash::remove($model->data[$model->alias], 'id');
			$model->id = null;
		}

		return parent::beforeSave($model, $options);
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
		if (! $this->_hasM17nFields($model)) {
			return true;
		}

		$keyField = $this->settings[$model->name]['keyField'];

		if ($this->_hasWorkflowFields($model)) {
			$conditions = array(
				$model->alias . '.' . $keyField => $model->data[$model->alias][$keyField],
				$model->alias . '.' . 'language_id !=' => Current::read('Language.id'),
				$model->alias . '.' . 'is_translation' => false,
				$model->alias . '.' . 'is_latest' => true
			);
		} else {
			$conditions = array(
				$model->alias . '.' . $keyField => $model->data[$model->alias][$keyField],
				$model->alias . '.' . 'language_id !=' => Current::read('Language.id'),
				$model->alias . '.' . 'is_translation' => false,
			);
		}

		//is_translationの更新
		$this->_updateTranslationField($model, $conditions);

		//全言語をコピーする処理
		$conditions[$model->alias . '.' . 'is_translation'] = true;
		$this->_copyData($model, $conditions);

		return parent::afterSave($model, $created, $options);
	}

/**
 * is_translationの更新
 *
 * @param Model $model 呼び出し元Model
 * @param array $conditions 更新条件
 * @return bool
 * @throws InternalErrorException
 */
	protected function _updateTranslationField(Model $model, $conditions) {
		$update = array(
			'is_translation' => true,
		);

		if (! $model->updateAll($update, $conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * 全言語をコピーする処理
 *
 * @param Model $model 呼び出し元Model
 * @param array $conditions 更新条件
 * @return bool
 */
	protected function _copyData(Model $model, $conditions) {
		//全言語をコピーするフィールドがない場合、処理終了
		if (! $this->settings[$model->name]['allUpdateField']) {
			return true;
		}

		$orgData = $model->data;
		$orgId = $model->id;

		//コピー元のデータ取得
		$defaultUpdate = array();
		$copyConditions = $conditions;
		foreach ($this->settings[$model->name]['allUpdateField'] as $field) {
			$fieldValue = Hash::get($model->data[$model->alias], $field);

			$copyConditions['OR'][$model->alias . '.' . $field . ' !='] = $fieldValue;
			$defaultUpdate[$model->alias][$field] = $fieldValue;
		}
		$results = $model->find('all', array(
			'recursive' => -1,
			'conditions' => $copyConditions,
		));

		//データのコピー処理
		foreach ($results as $data) {
			//ワークフローのデータであれば、is_activeとis_latestのフラグを更新する
			if (! $this->_updateWorkflowFields($model, $data)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//ワークフローのデータであれば、新規にデータを生成する
			if ($this->_hasWorkflowFields($model)) {
				unset($data[$model->alias]['id']);
				$model->create(false);
			}

			$update = Hash::merge($data, $defaultUpdate);
			$newData = $model->save($update, ['validate' => false, 'callbacks' => false]);
			if (! $newData) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//ワークフローのデータでコピーする場合で、関連テーブルを更新する
			if (! $this->_updateWorkflowAssociations($model, $newData[$model->alias]['id'], $orgId)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		$model->data = $orgData;
		$model->id = $orgId;

		return true;
	}

/**
 * ワークフローのデータであれば、is_activeとis_latestのフラグを更新する
 *
 * @param Model $model 呼び出し元Model
 * @param array $data 更新データ
 * @return bool
 */
	protected function _updateWorkflowFields(Model $model, $data) {
		if (! $this->_hasWorkflowFields($model)) {
			return true;
		}

		$update = array(
			'is_active' => false,
			'is_latest' => false,
		);
		$conditions = array(
			$model->alias . '.id' => $data[$model->alias]['id']
		);
		return $model->updateAll($update, $conditions);
	}

/**
 * ワークフローのデータでコピーする場合で、関連テーブルを更新する
 * e.g) タスクプラグインなど
 *
 * @param Model $model 呼び出し元Model
 * @param array $newId 更新するID
 * @param array $orgId 更新元ID
 * @return bool
 */
	protected function _updateWorkflowAssociations(Model $model, $newId, $orgId) {
		if (! $this->settings[$model->name]['associations']) {
			return true;
		}
		if (! $this->_hasWorkflowFields($model)) {
			return true;
		}

		foreach ($this->settings[$model->name]['associations'] as $modelName => $modelData) {
			$model->loadModels([$modelName => $modelData['class']]);

			$schema = $model->$modelName->schema();
			unset($schema['id']);
			$schemaColumns = implode(', ', array_keys($schema));

			$tableName = $model->$modelName->tablePrefix . $model->$modelName->table;

			$sql = 'INSERT INTO ' . $tableName . '(' . $schemaColumns . ')' .
					' SELECT ' . preg_replace('/' . $modelData['foreignKey'] . '/', $newId, $schemaColumns) .
					' FROM ' . $tableName .
					' WHERE ' . $modelData['foreignKey'] . ' = ' . $orgId;
			if ($model->$modelName->hasField('plugin_key')) {
				$sql .= ' AND plugin_key = \'' . Inflector::underscore($model->plugin) . '\'';
			}
			$model->$modelName->query($sql);
		}

		return true;
	}

}
