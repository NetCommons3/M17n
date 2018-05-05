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

App::uses('TagFixture', 'Tags.Test/Fixture');

/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Fixture
 */
class Tag4m17nFixture extends TagFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Tag';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'tags';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		//日本語のみ
		array(
			'id' => '1',
			'block_id' => '1',
			'model' => 'TestM17nBSaveWorkflowTagCategory',
			'key' => 'tag_1',
			'language_id' => '2',
			'is_origin' => true,
			'is_translation' => false,
			'is_original_copy' => false,
			'name' => 'TagJa1',
		),
		//英語のみ
		array(
			'id' => '2',
			'block_id' => '1',
			'model' => 'TestM17nBSaveWorkflowTagCategory',
			'key' => 'tag_2',
			'language_id' => '1',
			'is_origin' => true,
			'is_translation' => false,
			'is_original_copy' => false,
			'name' => 'TagEn2',
		),
	);

}
