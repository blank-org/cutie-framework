<?php
	$translations_path = __DIR__ . '/../../../../Config/Translations.tsv';
	$available_translations = array();
	if (file_exists($translations_path)) {
		$fHandle = fopen($translations_path, 'r');
		$header = fgetcsv($fHandle, 0, "\t", "\"", "\\");
		$langs = array_slice($header, 1);
		while (($row = fgetcsv($fHandle, 0, "\t", "\"", "\\")) !== FALSE) {
			if ($row[0] == $id || ($id == 'root' && $row[0] == 'root')) {
				foreach ($langs as $i => $alt_lang) {
					$status = isset($row[$i + 1]) ? trim($row[$i + 1]) : '';
					if (($status === 'published' || $status === 'publish') && $alt_lang !== $lang) {
						$available_translations[] = $alt_lang;
					}
				}
				break;
			}
		}
		fclose($fHandle);
	}

	$lang_names = array('en' => 'English', 'hi' => 'हिन्दी', 'hi-in' => 'हिन्दी (भारत)');
?>
<div id='language-switcher'>
<?php if (!empty($available_translations)) { ?>
	<?php foreach ($available_translations as $alt_lang) {
		$prefix = ($alt_lang === 'en') ? '' : '/' . $alt_lang;
		$href = $prefix;
		if ($id !== 'root') $href .= '/' . $id;
		if (!$href) $href = '/';
		$name = isset($lang_names[$alt_lang]) ? $lang_names[$alt_lang] : $alt_lang;
	?>
	<a href='<?php echo $href ?>' class='lang-link'><?php echo $name ?></a>
	<?php } ?>
<?php } ?>
</div>
