<?php
/**
 * M17nGetTest TestCase
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

//@codeCoverageIgnoreStart;
App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');
//@codeCoverageIgnoreEnd;

/**
 * M17nGetTest TestCase
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\TestSuite
 * @codeCoverageIgnore
 */
abstract class M17nGetTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	private $__fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'm17n';

/**
 * Fixtures load
 *
 * @param string $name The name parameter on PHPUnit_Framework_TestCase::__construct()
 * @param array  $data The data parameter on PHPUnit_Framework_TestCase::__construct()
 * @param string $dataName The dataName parameter on PHPUnit_Framework_TestCase::__construct()
 * @return void
 */
	public function __construct($name = null, array $data = array(), $dataName = '') {
		if (! isset($this->fixtures)) {
			$this->fixtures = array();
		}
		$this->fixtures = array_merge($this->__fixtures, $this->fixtures);
		parent::__construct($name, $data, $dataName);
	}

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Language::$languages = array();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Language::$languages = array();

		parent::tearDown();
	}

}
