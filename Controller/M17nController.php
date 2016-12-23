<?php
/**
 * M17n Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('M17nAppController', 'M17n.Controller');

/**
 * M17n Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\M17n\Controller
 */
class M17nController extends M17nAppController {

/**
 * 言語切り替えアクション
 *
 * @return void
 */
	public function index() {
		$this->redirect($this->request->referer());
	}

}
