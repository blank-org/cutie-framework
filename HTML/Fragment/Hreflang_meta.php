<?php
	$translations_path = __DIR__ . '/../../../Config/Translations.tsv';
	if (file_exists($translations_path)) {
		$fHandle = fopen($translations_path, 'r');
		$header = fgetcsv($fHandle, 0, "\t", "\"", "\\");
		$available_langs = array_slice($header, 1);

		while (($row = fgetcsv($fHandle, 0, "\t", "\"", "\\")) !== FALSE) {
			if ($row[0] == $id || ($id == 'root' && $row[0] == 'root')) {
				foreach ($available_langs as $i => $alt_lang) {
					$status = isset($row[$i + 1]) ? trim($row[$i + 1]) : '';
					if ($status === 'published' || $status === 'publish') {
						$prefix = ($alt_lang === 'en') ? '' : '/' . $alt_lang;
						$href = $config['base_url'] . $prefix;
						if ($id !== 'root') $href .= '/' . $id;
						echo "\t<link rel='alternate' hreflang='{$alt_lang}' href='{$href}' />\n";
					}
				}
				$default_href = $config['base_url'];
				if ($id !== 'root') $default_href .= '/' . $id;
				echo "\t<link rel='alternate' hreflang='x-default' href='{$default_href}' />\n";
				break;
			}
		}
		fclose($fHandle);
	}
?>
