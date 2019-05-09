<?php
/**
 * Language Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('M17nAppModel', 'M17n.Model');

/**
 * Language Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Model
 */
class Language extends M17nAppModel {

/**
 * Language code map
 *
 * @var array
 */
	private $__labelLanguages = [
		'en' => 'English',
		'ja' => 'Japanese',
	];

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'M17n.SaveM17n',
		'NetCommons.NetCommonsCache',
	);

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = ValidateMerge::merge($this->validate, array(
			'code' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'weight' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'is_active' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * 言語データを取得
 *
 * @param string $type 取得タイプ
 * @param array $options 取得オプション
 * @return array 言語データ
 */
	public function getLanguage($type = null, $options = array()) {
		if (! $type && ! $options) {
			return $this->getLanguages();
		}
		if (! $type) {
			$type = 'all';
		}

		if ($type === 'list' && ! isset($options['fields'])) {
			$options['fields'] = array('id', 'code');
		} elseif (! isset($options['fields'])) {
			$options['fields'] = [
				'id', 'code', 'weight', 'is_active'
			];
		}

		if ($type !== 'first' && ! isset($options['order'])) {
			$options['order'] = [
				'weight' => 'asc'
			];
		}

		$languages = $this->cacheFindQuery($type, Hash::merge(array(
			'recursive' => -1,
			'conditions' => array(
				'is_active' => true
			),
		), $options));
		return $languages;
	}

/**
 * 言語データを取得
 *
 * @return array
 */
	public function getLanguages() {
		$result = $this->cacheFindQuery('all', array(
			'recursive' => -1,
			'fields' => [
				'id', 'code', 'weight', 'is_active'
			],
			'conditions' => array(
				'is_active' => true
			),
			'order' => array('weight' => 'asc')
		));
		return $result;
	}

/**
 * 複数言語か否か
 *
 * @return bool
 */
	public function isMultipleLang() {
		$result = $this->cacheFindQuery('count', array(
			'recursive' => -1,
			'conditions' => array(
				'is_active' => true
			),
		));
		return $result > 1;
	}

/**
 * 言語データを取得
 *
 * @return array
 */
	public function getLanguagesWithName() {
		$languages = $this->cacheFindQuery('all', array(
			'fields' => array('code', 'is_active'),
			'recursive' => -1,
		));

		$activeLangs = $this->cacheRead('activeLangs');
		$enableLangs = $this->cacheRead('enableLangs');
		if (! $activeLangs || ! $enableLangs) {
			$activeLangs = [];
			$enableLangs = [];
			foreach ($languages as $lang) {
				$code = $lang[$this->alias]['code'];
				if (! isset($this->__labelLanguages[$code])) {
					continue;
				}
				$enableLangs[$code] = __d('m17n', $this->__labelLanguages[$code]);
				if ($lang[$this->alias]['is_active']) {
					$activeLangs[] = $code;
				}
			}

			//キャッシュの書き込み
			$this->cacheWrite($activeLangs, 'activeLangs');
			$this->cacheWrite($enableLangs, 'enableLangs');
		}

		return array($activeLangs, $enableLangs);
	}

/**
 * 利用可能な言語登録
 *
 * 呼び出しもとでbeginを実行する
 *
 * @param array $data リクエストデータ
 * @return bool
 * @throws InternalErrorException
 */
	public function saveActive($data) {
		if (! $this->validateActive($data)) {
			return false;
		}

		$update = array(
			'is_active' => true,
		);
		$conditions = array(
			'code' => $data['Language']['code']
		);
		if (! $this->updateAll($update, $conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		$update = array(
			'is_active' => false,
		);
		$conditions = array(
			'code NOT IN' => $data['Language']['code']
		);
		if (! $this->updateAll($update, $conditions)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//キャッシュのクリア
		$this->cacheClear();

		$languages = $this->cacheFindQuery('list', array(
			'recursive' => -1,
			'conditions' => array(
				'is_active' => true,
			),
		));
		foreach ($languages as $langId) {
			$this->copyOrignalData($langId);
		}

		return true;
	}

/**
 * 利用可能な言語登録の入力チェック
 *
 * @param array $data リクエストデータ
 * @return bool
 */
	public function validateActive($data) {
		$fieldName = 'Language.code';

		$languages = $this->cacheFindQuery('list', array(
			'fields' => array('code', 'is_active'),
			'recursive' => -1,
		));
		$defaultLangs = array_keys($languages);

		if (! isset($data['Language']['code']) || ! is_array($data['Language']['code']) ||
				count($data['Language']['code']) === 0) {
			$this->invalidate(
				$fieldName, __d('site_manager', 'Please select the language to use.')
			);
			return false;
		}

		if (array_diff($data['Language']['code'], $defaultLangs)) {
			$this->invalidate(
				$fieldName, __d('net_commons', 'Invalid request.')
			);
		}

		return count($this->validationErrors) === 0;
	}

}
