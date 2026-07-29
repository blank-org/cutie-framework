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
?>
