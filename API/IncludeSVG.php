<?php
	function includeSVG($path, $file) {
		echo file_get_contents('..\..\Resource\\'.$path.'\\'.$file.'.svg');
	}
?>
