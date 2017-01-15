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
class AddM17nBehavior extends ModelBehavior {

/**
 * is_translationの更新
 *
 * @param Model $model 呼び出し元Model
 * @return bool
 * @throws InternalErrorException
 */
	public function saveTranslation(Model $model) {
		//$dataSource = ConnectionManager::getDataSource($model->useDbConfig);
		//$tables = $dataSource->listSources();

		//if (! in_array($model->tablePrefix . $model->useTable, $tables)) {
		//	return false;
		//}

		return true;
	}

}
