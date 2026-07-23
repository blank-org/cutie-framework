<?php

	$SUPPORTED_LANGUAGES = ['hi', 'hi-in'];

	function getOrigCall_raw() {
		if(strlen($_SERVER['QUERY_STRING']) == 0)
			return (substr($_SERVER['REQUEST_URI'], 1));
		else {
			$pos = strpos($_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING']);
			return (substr($_SERVER['REQUEST_URI'], 1, $pos - 2));
		}
	}

	function getLanguage() {
		global $SUPPORTED_LANGUAGES;
		$path = getOrigCall_raw();
		$segments = explode('/', $path);
		if (count($segments) > 0 && in_array($segments[0], $SUPPORTED_LANGUAGES)) {
			return $segments[0];
		}
		return 'en';
	}

	function getOrigCall() {
		global $SUPPORTED_LANGUAGES;
		$path = getOrigCall_raw();
		$segments = explode('/', $path);
		if (count($segments) > 0 && in_array($segments[0], $SUPPORTED_LANGUAGES)) {
			array_shift($segments);
			return implode('/', $segments);
		}
		return $path;
	}

	function addAttribute(DOMElement $e, $name, $value) {
		$a = $e->getAttribute($name);
		$e->setAttribute($name, $a." ".$value);
	}

	function getFileDate($file) {
		date_default_timezone_set("UTC");
		return date("Y M d", filemtime($file));
	}

	function endsWith($haystack, $needle) {
		return preg_match('/' . preg_quote($haystack, '/') . '$/', $needle);
	}

	function matchExt($haystack, $needle) {
		if(strlen($haystack) < strlen($needle))
			return false;
		else
			return (substr($haystack, -strlen($needle)) == $needle);
	}
	
	function loadFiles($dir) {
		$files = scandir($dir);
		array_shift($files);
		array_shift($files);

		return $files;
	}

	function exit_404($message) {
		global $bPublish; 
		header("HTTP/1.0 404 Not Found");
		error_log($message);
		require '404.php';
		exit;
	}

?>
