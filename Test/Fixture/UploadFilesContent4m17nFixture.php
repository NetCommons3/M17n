<?php
/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UploadFilesContentFixture', 'Files.Test/Fixture');

/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Fixture
 */
class UploadFilesContent4m17nFixture extends UploadFilesContentFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UploadFilesContent';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'upload_files_contents';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'plugin_key' => 'test_m17n',
			'content_id' => '1',
			'upload_file_id' => '1',
		),
		array(
			'id' => '2',
			'plugin_key' => 'test_m17n',
			'content_id' => '2',
			'upload_file_id' => '2',
		),
		array(
			'id' => '3',
			'plugin_key' => 'test_m17n',
			'content_id' => '3',
			'upload_file_id' => '3',
		),
		array(
			'id' => '4',
			'plugin_key' => 'test_m17n',
			'content_id' => '4',
			'upload_file_id' => '4',
		),
	);

}
