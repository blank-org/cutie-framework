<?php

function loadConfig() {
	global $variant; 
	
	$fHandle = fopen(__DIR__.'/../../Config/Vars.tsv', 'r');
	while(($tsvLine = fgetcsv($fHandle, 0, "\t", "\"", "\\")) !== FALSE) {
		if(isset($tsvLine[0], $tsvLine[1])) {
			$config[trim($tsvLine[0])] = trim($tsvLine[1]);
		}
	}
	fclose($fHandle);
	
	$config_variant_fpath = __DIR__.'/../../Config/Vars_'.$variant.'.tsv';
	if(file_exists($config_variant_fpath)) {
		$fHandle = fopen($config_variant_fpath, 'r');
		while(($tsvLine = fgetcsv($fHandle, 0, "\t", "\"", "\\")) !== FALSE) {
			if(isset($tsvLine[0], $tsvLine[1])) {
				$config[trim($tsvLine[0])] = trim($tsvLine[1]);
			}
		}
		fclose($fHandle);
	}

	if(!isset($config['layout']) || $config['layout'] === '') {
		$config['layout'] = 'classic';
	}
	if(!isset($config['skin']) || $config['skin'] === '') {
		$config['skin'] = 'none';
	}
	
	return $config;
}

/**
 * Shell layout mode from Config/Vars.tsv `layout`.
 * classic = article shell (default, unchanged for existing sites)
 * wide    = modern full-bleed shell
 */
function getLayoutMode() {
	global $config;
	$layout = isset($config['layout']) ? strtolower(trim($config['layout'])) : 'classic';
	return in_array($layout, array('classic', 'wide'), true) ? $layout : 'classic';
}

/**
 * Visual skin from Config/Vars.tsv `skin`.
 * none  = no shared skin utilities (default)
 * glass = translucent panel tokens/utilities
 */
function getSkinMode() {
	global $config;
	$skin = isset($config['skin']) ? strtolower(trim($config['skin'])) : 'none';
	return in_array($skin, array('none', 'glass'), true) ? $skin : 'none';
}

?>
