<?php
	function includeSVG($path, $file) {
		$resource = __DIR__.'/../../Resource';
		$relativePath = trim(str_replace('\\', '/', $path), '/');
		if($relativePath !== '')
			$resource .= '/'.$relativePath;
		echo file_get_contents($resource.'/'.$file.'.svg');
	}
?>
