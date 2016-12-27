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
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * 言語データ
 *
 * @var array
 */
	public static $languages = array();

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
		$this->validate = Hash::merge($this->validate, array(
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
		}

		$languages = $this->find($type, Hash::merge(array(
			'recursive' => -1,
			'conditions' => array(
				'is_active' => true
			),
			'order' => array('weight' => 'asc')
		), $options));
		return $languages;
	}

/**
 * 言語データを取得
 *
 * @return array
 */
	public function getLanguages() {
		if (self::$languages) {
			return self::$languages;
		}

		self::$languages = $this->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'is_active' => true
			),
			'order' => array('weight' => 'asc')
		));

		return self::$languages;
	}

}
