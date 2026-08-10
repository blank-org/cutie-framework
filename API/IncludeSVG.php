<?php
	function includeSVG($path, $file) {
		$resource = __DIR__.'/../../Resource';
		$relativePath = trim(str_replace('\\', '/', $path), '/');
		$relativeFile = ($relativePath !== '' ? $relativePath.'/' : '').$file.'.svg';
		$svg = $resource.'/'.$relativeFile;
		if(!is_file($svg))
			$svg = $resource.'/'.strtolower($relativeFile);
		echo file_get_contents($svg);
	}

	function includeResourceSVG($filename) {
		$resource = __DIR__.'/../../Resource';
		$candidates = array($filename, strtolower($filename));
		foreach ($candidates as $name) {
			$svg = $resource.'/'.$name;
			if (is_file($svg)) {
				echo file_get_contents($svg);
				return;
			}
		}
	}
?>
