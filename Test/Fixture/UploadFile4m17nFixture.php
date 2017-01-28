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

App::uses('UploadFileFixture', 'Files.Test/Fixture');

/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Fixture
 */
class UploadFile4m17nFixture extends UploadFileFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UploadFile';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'upload_files';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'plugin_key' => 'test_m17n',
			'content_key' => 'test_1',
			'field_name' => 'photo',
			'original_name' => 'foo.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'foobarhash.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => '1',
			'download_count' => '1',
			'total_download_count' => '1',
			'room_id' => '2',
			'block_key' => 'block_1',
		),
		array(
			'id' => '2',
			'plugin_key' => 'test_m17n',
			'content_key' => 'test_2',
			'field_name' => 'photo',
			'original_name' => 'foo.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'foobarhash.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => '1',
			'download_count' => '1',
			'total_download_count' => '1',
			'room_id' => '2',
			'block_key' => 'block_1',
		),
		array(
			'id' => '3',
			'plugin_key' => 'test_m17n',
			'content_key' => 'test_3',
			'field_name' => 'photo',
			'original_name' => 'foo.jpg',
			'path' => 'files/upload_file/real_file_name/1/',
			'real_file_name' => 'foobarhash.jpg',
			'extension' => 'jpg',
			'mimetype' => 'image/jpg',
			'size' => '1',
			'download_count' => '1',
			'total_download_count' => '1',
			'room_id' => '2',
			'block_key' => 'block_1',
		),
	);

}
