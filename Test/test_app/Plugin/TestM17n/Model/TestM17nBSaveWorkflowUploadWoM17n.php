<?php
/**
 * M17nBehaviorテスト用Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppModel', 'Model');

/**
 * M17nBehaviorテスト用Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\test_app\Plugin\TestM17n\Model
 */
class TestM17nBSaveWorkflowUploadWoM17n extends AppModel {

/**
 * 使用ビヘイビア
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
		'Workflow.Workflow',
		'Files.Attachment' => ['photo'],
		'M17n.M17n' => array(
			'associations' => array(
				'UploadFilesContent' => array(
					'class' => 'Files.UploadFilesContent',
					'foreignKey' => 'content_id',
					'isM17n' => false,
				),
			),
		),
	);

}
