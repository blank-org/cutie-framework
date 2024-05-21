<?php

function loadConfig() {
	global $variant; 
	
	$fHandle = fopen(__DIR__.'/../../Config/Vars.tsv', 'r');
	while(($tsvLine = fgetcsv($fHandle, 0, "\t")) !== FALSE) {
		$config[$tsvLine[0]] = $tsvLine[1];
	}
	fclose($fHandle);
	
	$config_variant_fpath = __DIR__.'/../../Config/Vars_'.$variant.'.tsv';
	if(file_exists($config_variant_fpath)) {
		$fHandle = fopen($config_variant_fpath, 'r');
		while(($tsvLine = fgetcsv($fHandle, 0, "\t")) !== FALSE) {
			$config[$tsvLine[0]] = $tsvLine[1];
		}
		fclose($fHandle);
	}
	
	return $config;
}

?>
