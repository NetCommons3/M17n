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
App::uses('Plugin', 'PluginManager.Model');

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
 *		'commonFields' => array('category_id'), //このフィールドが更新された場合、全言語のデータを更新する
 *		'associations' => array(
 *			'(Model名)' => array(
 *				'class' => (クラス名: Plugin.Model形式),
 *				'foreignKey' => (外部キー),
 *				'isM17n' => 多言語ありかどうか,
 *			)
 *		),
 *		'afterCallback' => afterSaveを実行するかどうか,
 *		'isWorkflow' => ワークフローかどうか。省略もしくはNULLの場合、
 * 	),
 * ```
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package  NetCommons\M17n\Model\Befavior
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
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
		$this->settings[$model->name]['commonFields'] = Hash::get($config, 'commonFields', array());
		$this->settings[$model->name]['associations'] = Hash::get($config, 'associations', array());
		$this->settings[$model->name]['afterCallback'] = Hash::get($config, 'afterCallback', true);
		$this->settings[$model->name]['isWorkflow'] = Hash::get(
			$config, 'isWorkflow', $this->_hasWorkflowFields($model)
		);

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
 * ルームに関するプラグインかどうか
 *
 * @param Model $model 呼び出し元Model
 * @param bool|null $isRoom ルームプラグインかどうか
 * @return bool
 */
	public function isM7nGeneralPlugin(Model $model) {
		if (! $model->hasField('language_id')) {
			return false;
		}
		if (Current::read('Plugin.type') !== Plugin::PLUGIN_TYPE_FOR_FRAME) {
			return true;
		}
		if (! Current::read('Room.id') || ! Current::read('Plugin.is_m17n') ||
				! Current::read('Space.is_m17n')) {
			return false;
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
		if (! $keyField) {
			return true;
		}

		if (! $this->isM7nGeneralPlugin($model)) {
			return true;
		}

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
			'callbacks' => false,
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
			'callbacks' => false,
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
			'callbacks' => false,
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
			return parent::afterSave($model, $created, $options);
		}

		if (! $this->isM7nGeneralPlugin($model)) {
			return true;
		}

		$conditions = $this->_getSaveConditions($model);
		if (! $conditions) {
			return parent::afterSave($model, $created, $options);
		}

		//is_translationの更新
		$this->updateTranslationField($model);

		//多言語化の処理
		if (! $this->settings[$model->name]['afterCallback']) {
			return parent::afterSave($model, $created, $options);
		}

		$newOrgData = $model->data;
		$newOrgId = $model->id;

		$this->saveM17nData($model);

		$model->data = $newOrgData;
		$model->id = $newOrgId;

		return parent::afterSave($model, $created, $options);
	}

/**
 * saveの条件を取得する
 *
 * @param Model $model Model using this behavior
 * @return array
 * @throws InternalErrorException
 * @see Model::save()
 */
	protected function _getSaveConditions(Model $model) {
		$keyField = $this->settings[$model->name]['keyField'];
		if (! $keyField) {
			return false;
		}

		if ($this->_hasWorkflowFields($model)) {
			$conditions = array(
				$model->alias . '.' . $keyField => $model->data[$model->alias][$keyField],
				$model->alias . '.' . 'language_id !=' => Current::read('Language.id'),
				$model->alias . '.' . 'is_translation' => false,
				$model->alias . '.' . 'is_latest' => true
			);
		} elseif ($this->_hasM17nFields($model)) {
			$conditions = array(
				$model->alias . '.' . $keyField => $model->data[$model->alias][$keyField],
				$model->alias . '.' . 'language_id !=' => Current::read('Language.id'),
				$model->alias . '.' . 'is_translation' => false,
			);
		} else {
			$conditions = array(
				$model->alias . '.' . $keyField => $model->data[$model->alias][$keyField],
			);
		}

		return $conditions;
	}

/**
 * is_translationの更新
 *
 * @param Model $model 呼び出し元Model
 * @return bool
 * @throws InternalErrorException
 */
	public function updateTranslationField(Model $model) {
		$conditions = $this->_getSaveConditions($model);

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
 * @param array|null $commonFields 共通フィールド
 * @param array|null $associations 関連情報
 * @return bool
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
	public function saveM17nData(Model $model, $commonFields = null, $associations = null) {
		//全言語をコピーするフィールドがない場合、処理終了
		if (! isset($commonFields)) {
			$commonFields = $this->settings[$model->name]['commonFields'];
		}
		if (! isset($associations)) {
			$associations = $this->settings[$model->name]['associations'];
		}
		if (! $commonFields && ! $associations) {
			return true;
		}
		if (! $model->id || ! $model->data) {
			return true;
		}

		//基準データの保持
		$baseData = $model->data;

		//コピー対象データ取得
		$commonUpdate = array();
		$conditions = array();
		if ($commonFields) {
			foreach ($commonFields as $field) {
				$fieldValue = Hash::get($model->data[$model->alias], $field);

				$conditions[$model->alias . '.' . $field . ' !='] = $fieldValue;
				$commonUpdate[$model->alias][$field] = $fieldValue;
			}
		}
		$targetConditions = $this->_getSaveConditions($model);
		$targetConditions[$model->alias . '.' . 'is_translation'] = true;
		if (! $this->settings[$model->name]['associations']) {
			$targetConditions['OR'] = $conditions;
		}
		$targetDatas = $model->find('all', array(
			'recursive' => -1,
			'callbacks' => false,
			'conditions' => $targetConditions,
		));

		//データのコピー処理
		$options = array(
			'baseData' => $baseData,
			'commonFields' => $commonFields,
			'commonUpdate' => $commonUpdate,
			'associations' => $associations,
		);
		$this->_saveM17nData($model, $targetDatas, $options);

		return true;
	}

/**
 * 多言語データの登録処理
 *
 * ## $options
 * array(
 *		'baseData' => 基準となるデータ,
 *		'commonFields' => 共通フィールド,
 *		'commonUpdate' => 共通フィールドの更新データ,
 *		'associations' => 関連情報,
 * );
 *
 * @param Model $model 呼び出し元Model
 * @param array $targetDatas 対象データ
 * @param array $options オプション
 * @return bool
 * @throws InternalErrorException
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 */
	protected function _saveM17nData(Model $model, $targetDatas, $options) {
		$associations = Hash::get($options, 'associations', array());
		$commonUpdate = Hash::get($options, 'commonUpdate', array());
		$baseData = Hash::get($options, 'baseData');
		$isWorkflow = Hash::get(
			$options,
			'isWorkflow',
			Hash::get($this->settings, $model->name . '.isWorkflow')
		);
		$languageId = Hash::get($options, 'languageId');

		//データのコピー処理
		foreach ($targetDatas as $targetData) {
			if ($languageId) {
				$targetData[$model->alias]['language_id'] = $languageId;
			}

			//ワークフローのデータであれば、is_activeとis_latestのフラグを更新する
			if (! $this->_updateWorkflowFields($model, $targetData)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//ワークフローのデータであれば、新規にデータを生成する
			$update = Hash::merge($targetData, $commonUpdate);
			if ($this->_hasWorkflowFields($model) || $isWorkflow) {
				unset($update[$model->alias]['id']);
				$model->create(false);
			}

			//データの更新処理
			$newData = $model->save($update, ['validate' => false, 'callbacks' => false]);
			if (! $newData) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//関連テーブルの更新処理
			$options2 = array(
				'baseData' => $baseData,
				'targetData' => $targetData,
				'newData' => $newData,
				'isWorkflow' => $isWorkflow,
				'languageId' => Hash::get($newData[$model->alias], 'language_id')
			);
			if (! $this->_updateWorkflowAssociations($model, $options2, $associations)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

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
 *
 * ## $options
 * array(
 *		'baseData' => 基準となるデータ,
 *		'targetData' => 対象データ,
 *		'newData' => 登録したデータ,
 *		'isWorkflow' => ワークフローかどうか
 * );
 *
 * @param Model $model 呼び出し元Model
 * @param array $options オプション
 * @param array|null $associations 関連情報
 * @return bool
 */
	protected function _updateWorkflowAssociations(Model $model, $options, $associations = null) {
		if (! isset($associations)) {
			$associations = $this->settings[$model->name]['associations'];
		}
		if (! $associations) {
			return true;
		}

		foreach ($associations as $modelName => $modelData) {
			$model->loadModels([$modelName => $modelData['class']]);

			if (Hash::get($modelData, 'isM17n')) {
				$id = $options['targetData'][$model->alias]['id'];
			} else {
				$id = $options['baseData'][$model->alias]['id'];
			}

			if ($model->$modelName->hasField('plugin_key')) {
				$conditions = array(
					$modelData['foreignKey'] => $id,
					'plugin_key' => Inflector::underscore($model->plugin)
				);
			} else {
				$conditions = array(
					$modelData['foreignKey'] => $id,
				);
			}

			$targetDatas = $model->$modelName->find('all', array(
				'recursive' => -1,
				'callbacks' => false,
				'conditions' => $conditions,
			));

			$commonFields = Hash::get(
				$modelData,
				'commonFields',
				Hash::get($this->settings, $model->$modelName->name . '.commonFields', array())
			);
			$associations2 = Hash::get($modelData, 'associations');

			$options2 = array(
				'foreignKey' => $modelData['foreignKey'],
				'associationId' => $options['newData'][$model->alias]['id'],
				'commonFields' => $commonFields,
				'associations' => $associations2,
				'isWorkflow' => Hash::get($options, 'isWorkflow'),
				'languageId' => Hash::get($options, 'languageId'),
			);
			$this->_saveWorkflowAssociations($model->$modelName, $targetDatas, $options2);
		}

		return true;
	}

/**
 * ワークフローのデータでコピーする場合で、関連テーブルを更新する
 *
 * ## $options
 * array(
 *		'foreignKey' => 外部キーのフィールド,
 *		'associationId' => 関連データのID,
 *		'commonFields' => 共通フィールド,
 *		'associations' => 関連情報,
 *		'isWorkflow' => ワークフローかどうか
 * );
 *
 * @param Model $model 呼び出し元Model※_updateWorkflowAssociations()の$model->$modelName
 * @param array $targetDatas 対象データ
 * @param array $options オプション
 * @return bool
 */
	protected function _saveWorkflowAssociations(Model $model, $targetDatas, $options) {
		$associations = Hash::get($options, 'associations');
		$commonFields = Hash::get($options, 'commonFields');
		$foreignKey = Hash::get($options, 'foreignKey');
		$associationId = Hash::get($options, 'associationId');

		foreach ($targetDatas as $targetData) {
			$commonUpdate = array();
			$commonUpdate[$model->alias][$foreignKey] = $associationId;
			foreach ($commonFields as $field) {
				$fieldValue = Hash::get($targetData[$model->alias], $field);
				$commonUpdate[$model->alias][$field] = $fieldValue;
			}

			//データのコピー処理
			$options2 = array(
				'baseData' => $targetData,
				'commonFields' => $commonFields,
				'commonUpdate' => $commonUpdate,
				'associations' => $associations,
				'isWorkflow' => Hash::get($options, 'isWorkflow'),
				'languageId' => Hash::get($options, 'languageId'),
			);
			$this->_saveM17nData($model, [$targetData], $options2);
		}
	}

}
