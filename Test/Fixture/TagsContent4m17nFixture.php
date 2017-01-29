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

App::uses('TagsContentFixture', 'Tags.Test/Fixture');

/**
 * M17nBehavior::save()テスト用Fixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Test\Fixture
 */
class TagsContent4m17nFixture extends TagsContentFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'TagsContent';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'tags_contents';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'model' => 'TestM17nBSaveWorkflowTagCategory',
			'content_id' => '1',
			'tag_id' => '1',
		),
		array(
			'id' => '2',
			'model' => 'TestM17nBSaveWorkflowTagCategory',
			'content_id' => '2',
			'tag_id' => '2',
		),
		array(
			'id' => '3',
			'model' => 'TestM17nBSaveWorkflowTagCategory',
			'content_id' => '3',
			'tag_id' => '1',
		),
		array(
			'id' => '4',
			'model' => 'TestM17nBSaveWorkflowTagCategory',
			'content_id' => '4',
			'tag_id' => '2',
		),
	);

}
