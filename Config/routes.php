<?php
/**
 * router file
 *
 * @author Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

Router::connect('/m17n/:action', array(
	'plugin' => 'm17n', 'controller' => 'm17n'
));
