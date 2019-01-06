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
 * AddM17nBehavior
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
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package  NetCommons\M17n\Model\Befavior
 */
class SaveM17nBehavior extends ModelBehavior {

/**
 * 他言語データ登録処理
 *
 * @param Model $model 呼び出し元Model
 * @param int $languageId 言語ID
 * @return bool
 */
	public function copyOrignalData(Model $model, $languageId) {
		$dataSource = ConnectionManager::getDataSource($model->useDbConfig);
		$this->_tables = $dataSource->listSources();

		$model->loadModels([
			'Plugin' => 'PluginManager.Plugin',
			'Language' => 'M17n.Language'
		]);
		$this->_enableLangs = $model->Language->getLanguage('list', array('fields' => ['id', 'id']));

		$executePlugins = array(
			Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER, Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER,
			Plugin::PLUGIN_TYPE_FOR_FRAME, Plugin::PLUGIN_TYPE_CORE
		);

		$plugins = array_unique(array_merge(
			App::objects('plugins'),
			array_map('basename', glob(ROOT . DS . 'app' . DS . 'Plugin' . DS . '*', GLOB_ONLYDIR))
		));

		foreach ($plugins as $pluginDir) {
			$pluginPath = ROOT . DS . 'app' . DS . 'Plugin' . DS . $pluginDir . DS . 'Model';
			if (! file_exists($pluginPath)) {
				continue;
			}

			$plugin = $model->Plugin->cacheFindQuery('first', array(
				'recursive' => -1,
				'conditions' => array(
					'key' => Inflector::underscore($pluginDir),
					'language_id' => [Current::read('Language.id'), '0'],
					'type' => $executePlugins,
				),
			));

			if (! $plugin) {
				continue;
			}

			if (! $this->_copyOrignalDataByPlugin($model, $plugin, $languageId)) {
				return false;
			}
		}

		return true;
	}

/**
 * 他言語データ登録処理
 *
 * @param Model $model 呼び出し元Model
 * @param array $plugin プラグインデータ
 * @param int $languageId 言語ID
 * @return bool
 */
	protected function _copyOrignalDataByPlugin(Model $model, $plugin, $languageId) {
		$camelPluginKey = Inflector::camelize($plugin['Plugin']['key']);
		$modelPath = ROOT . DS . 'app' . DS . 'Plugin' . DS . $camelPluginKey . DS . 'Model';

		$modelNames = array_map(
			'basename', glob($modelPath . DS . '*', GLOB_NOSORT)
		);

		foreach ($modelNames as $name) {
			$name = pathinfo($modelPath . DS . $name, PATHINFO_FILENAME);
			if (is_dir($modelPath . DS . $name) || in_array($name, ['UserSearch'], true)) {
				continue;
			}
			$model->loadModels([$name => $camelPluginKey . '.' . $name]);

			$TargetModel = $model->$name;
			if (is_object($TargetModel) && ! $this->_isOriginalCopy($TargetModel)) {
				continue;
			}

			if (! $this->_countOriginalCopy($TargetModel, $languageId, $plugin['Plugin']['type'])) {
				continue;
			}
			if (! $this->_executeOriginalCopy($TargetModel, $languageId, $plugin['Plugin']['type'])) {
				return false;
			}
			if (! $this->_updateOriginalCopy($TargetModel)) {
				return false;
			}
		}

		return true;
	}

/**
 * 他言語データ登録処理
 *
 * @param Model $TargetModel 実行するModel
 * @param int $langId 言語ID
 * @param int $pluguinType プラグインタイプ
 * @return bool
 * @throws InternalErrorException
 */
	protected function _executeOriginalCopy(Model $TargetModel, $langId, $pluguinType) {
		$tableName = $TargetModel->tablePrefix . $TargetModel->table;

		$schema = $TargetModel->schema();
		unset($schema['id']);

		$schemaKeys = array_map(function ($value) {
			return '`' . $value . '`';
		}, array_keys($schema));

		$schemaColumns = array_map(function ($value) use ($langId, $pluguinType) {
			if (in_array($value, ['modified'])) {
				return '\'' . gmdate('Y-m-d H:i:s') . '\'';
			} elseif ($value === 'language_id') {
				return $langId;
			} elseif ($value === 'is_origin') {
				return '0';
			} elseif ($value === 'is_translation') {
				if ($pluguinType === Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER ||
						$pluguinType === Plugin::PLUGIN_TYPE_FOR_SYSTEM_MANGER) {
					return 'Origin.`' . $value . '`';
				} else {
					return '1';
				}
			} elseif ($value === 'is_original_copy') {
				return '1';
			} else {
				return 'Origin.`' . $value . '`';
			}
		}, array_combine($schemaKeys, array_keys($schema)));

		$sql = 'INSERT INTO ' . $tableName . '(' . implode(', ', array_keys($schemaColumns)) . ') ' .
				'SELECT ' . implode(', ', array_values($schemaColumns));
		$sql .= $this->_getSqlFrom($TargetModel, $langId);
		$sql .= $this->_getSqlWhere($TargetModel, $pluguinType);

		CakeLog::info(
			'[original copy ' . $TargetModel->plugin . '] ' .
			$TargetModel->name . ' execute'
		);

		//CakeLog::debug(var_export($sql, true));
		$TargetModel->query($sql);

		CakeLog::info(
			'[original copy ' . $TargetModel->plugin . '] ' .
			$TargetModel->name . ' success rows=' . $TargetModel->getAffectedRows()
		);

		return true;
	}

/**
 * 他言語データ更新処理
 *
 * @param Model $TargetModel 実行するModel
 * @return bool
 * @throws InternalErrorException
 */
	protected function _updateOriginalCopy(Model $TargetModel) {
		if ($TargetModel->name === 'PagesLanguage') {
			CakeLog::info(
				'[original copy ' . $TargetModel->plugin . '] ' .
				$TargetModel->name . ' update'
			);
			$update = array(
				'is_translation' => true
			);
			$conditions = array(
				'OR' => array(
					'page_id' => $this->_getPageIdTop($TargetModel),
					'name !=' => ''
				),
				'is_translation' => false
			);
			if (! $TargetModel->updateAll($update, $conditions)) {
				return false;
			}

			CakeLog::info(
				'[original copy ' . $TargetModel->plugin . '] ' .
				$TargetModel->name . ' success'
			);
		}

		return true;
	}

/**
 * フィールドキーを取得する
 *
 * @param Model $TargetModel 実行するModel
 * @return string|bool
 */
	protected function _getFieldKey(Model $TargetModel) {
		if ($TargetModel->name === 'PagesLanguage') {
			return $TargetModel->getM17nSettings('keyField');
		}

		if (! $TargetModel->hasField('language_id') ||
				! $TargetModel->hasField('is_origin') ||
				! $TargetModel->hasField('is_translation') ||
				! $TargetModel->hasField('is_original_copy')) {
			return false;
		}

		if ($TargetModel->Behaviors->loaded('M17n.M17n')) {
			return $TargetModel->getM17nSettings('keyField');
		}
		if ($TargetModel->hasField('key')) {
			return 'key';
		}

		return false;
	}

/**
 * テーブルSQL生成
 *
 * @param Model $TargetModel 実行するModel
 * @param int $langId 言語ID
 * @return string
 */
	protected function _getSqlFrom(Model $TargetModel, $langId) {
		$tableName = $TargetModel->tablePrefix . $TargetModel->table;
		$keyField = '`' . $this->_getFieldKey($TargetModel) . '`';

		$sql = '';
		$sql .= ' FROM ' . $tableName . ' AS Origin' .
				' LEFT JOIN ' . $tableName . ' AS Target';
		$sql .= ' ON (' .
					'Origin.' . $keyField . ' = Target.' . $keyField .
					' AND Target.language_id = ' . $langId .
				')';
		return $sql;
	}

/**
 * 条件SQL生成
 *
 * @param Model $TargetModel 実行するModel
 * @param int $pluguinType プラグインタイプ
 * @return string|bool
 */
	protected function _getSqlWhere(Model $TargetModel, $pluguinType) {
		$sql = '';

		$sql .= ' WHERE Origin.is_origin = 1' .
				' AND Origin.language_id IN (' . implode(', ', $this->_enableLangs) . ')' .
				' AND Target.id IS NULL';

		if ($TargetModel->name === 'PagesLanguage') {
			$pageIds = $this->_getPageIdTop($TargetModel);
			$sql .=
				' AND (Origin.page_id IN (' . implode(', ', $pageIds) . ')' .
					' OR Origin.name = \'\')';
		} elseif ($TargetModel->hasField('is_lastest')) {
			$sql .=
				' AND Origin.is_translation = 1' .
				' AND Origin.is_lastest = 1';
		} elseif ($pluguinType !== Plugin::PLUGIN_TYPE_FOR_SITE_MANAGER &&
						$pluguinType !== Plugin::PLUGIN_TYPE_FOR_SYSTEM_MANGER) {
			$sql .=
				' AND Origin.is_translation = 1';
		}

		return $sql;
	}

/**
 * page_id_topを取得する
 *
 * @param Model $TargetModel 実行するModel
 * @return array
 */
	protected function _getPageIdTop(Model $TargetModel) {
		$TargetModel->loadModels(['Room' => 'Rooms.Room']);
		$pageIds = $TargetModel->Room->find('list', array(
			'recursive' => -1,
			'fields' => array('Room.page_id_top', 'Room.page_id_top'),
			'conditions' => array(
				'Room.page_id_top NOT' => null,
			),
		));
		return $pageIds;
	}

/**
 * オリジナルのコピーをするかどうか
 *
 * @param Model $TargetModel 実行するModel
 * @return string|bool
 */
	protected function _isOriginalCopy(Model $TargetModel) {
		if (! in_array($TargetModel->tablePrefix . $TargetModel->useTable, $this->_tables)) {
			return false;
		}

		if ($TargetModel->name === 'PagesLanguage') {
			return true;
		}
		if (! $TargetModel->hasField('language_id') ||
				! $TargetModel->hasField('is_origin') ||
				! $TargetModel->hasField('is_translation') ||
				! $TargetModel->hasField('is_original_copy')) {
			return false;
		}

		if ($TargetModel->Behaviors->loaded('M17n.M17n')) {
			return (bool)$TargetModel->getM17nSettings('keyField');
		}
		return $TargetModel->hasField('key');
	}

/**
 * 登録件数を取得する
 *
 * @param Model $TargetModel 実行するModel
 * @param int $langId 言語ID
 * @param int $pluguinType プラグインタイプ
 * @return string
 */
	protected function _countOriginalCopy(Model $TargetModel, $langId, $pluguinType) {
		$sql = '';

		$sql = 'SELECT COUNT(*) AS cnt' .
		$sql .= $this->_getSqlFrom($TargetModel, $langId);
		$sql .= $this->_getSqlWhere($TargetModel, $pluguinType);

		$result = $TargetModel->query($sql);

		return (int)Hash::get($result, '0.0.cnt', 0);
	}

}
