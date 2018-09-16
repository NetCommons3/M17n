<?php
/**
 * Switch language element
 *   - $languages: Languages data
 *   - $prefix: It is id attribute prefix
 *   - $activeLangCode: active language
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

$enable = array_flip(
	Hash::extract($switchLanguages, '{n}.Language.code')
);
$options = $this->M17n->getLanguagesOptions($enable);
$langCommonQuery = parse_url($this->request->header('REQUEST_URI'), PHP_URL_QUERY);
?>

<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	<?php echo Hash::get($options, Current::read('Language.code')); ?> <span class="caret"></span>
</a>
<ul class="dropdown-menu">
	<?php foreach ($options as $code => $name) : ?>
		<li>
			<?php
				$langQuery = $langCommonQuery;
				if (preg_match('/lang=[^&]*/i', $langQuery)) {
					$langQuery = preg_replace('/lang=[^&]*/iu', 'lang=' . $code, $langQuery);
				} elseif ($langQuery) {
					$langQuery .= '&lang=' . $code;
				} else {
					$langQuery = 'lang=' . $code;
				}
			?>
			<a href="?<?php echo h($langQuery); ?>">
				<?php echo h($name); ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
