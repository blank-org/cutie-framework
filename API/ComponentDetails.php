<?php

function loadComponents() {
	global $bPublish;
	global $bFull;
	global $lang;
	global $localizedComponentIds;

	$localizedComponentIds = null;

	$read_components = function($file_path) use ($bFull) {
		$fHandle = fopen($file_path, "r");
		$header = array_map('strtolower', fgetcsv($fHandle, 0, "\t", "\"", "\\"));
		$rows = array();
		while(($tsvLine = fgetcsv($fHandle, 0, "\t", "\"", "\\")) !== FALSE) {
			if($tsvLine[0] == "draft") {
				if($bFull)
					array_shift($tsvLine);
				else
					continue;
			}
			$row = array();
			for($j = 0; $j < count($header); $j++)
				$row[$header[$j]] = isset($tsvLine[$j]) ? $tsvLine[$j] : "";
			$rows[] = $row;
		}
		fclose($fHandle);
		return $rows;
	};

	$component = $read_components("../../../Config/ID.tsv");
	if ($lang && $lang !== 'en') {
		$translated_path = "../../../Config/ID_{$lang}.tsv";
		if (file_exists($translated_path)) {
			$translated = $read_components($translated_path);
			$localizedComponentIds = array();
			$indexes = array();
			foreach ($component as $index => $row)
				$indexes[$row['id']] = $index;
			foreach ($translated as $row) {
				$localizedComponentIds[$row['id']] = true;
				if (isset($indexes[$row['id']]))
					$component[$indexes[$row['id']]] = $row;
				else
					$component[] = $row;
			}
		}
	}
	return $component;
}

function isComponentLocalized($id) {
	global $lang;
	global $localizedComponentIds;

	if (!$lang || $lang === 'en')
		return true;
	if ($localizedComponentIds === null)
		return true;
	return isset($localizedComponentIds[$id]);
}

function getComponentIndex($id) {
	global $component;
	
	for($i = 0; $i < count($component); $i++) {
		if($component[$i]['id'] == $id)
			return $i;
	}
	exit_404("Wrong ID : ".$id);
}

function getComponentPageLabel($id) {
	global $component;
	$componentPageLabel = getComponentLabel($id);
	$id_index = getComponentIndex($id);
	// If description contains 'hide_title' (case-insensitive) then return empty label
	if(count($component[$id_index]) > 5 && in_array('hide_title', explode(' ', strtolower($component[$id_index]['description']))))
		return '';
	else
		return $componentPageLabel;
}

function isComponentExternRoot($id) {
	global $component;
	$id_index = getComponentIndex($id);
	$flags = $component[$id_index]['flags'] ?? '';
	return ( $id == 'root' && in_array('external', explode(' ', strtolower($flags))));
}

function getComponentLabel($id) {
	global $component;
	if($id == '')
		return '';
	else
		return $component[getComponentIndex($id)]['label'];
}

function getComponentTitle($id) {
	global $component;
	if($id == '')
		return '';
	else
		return $component[getComponentIndex($id)]['title'];
}

function getComponentDesc($id) {
	global $component;
	return $component[getComponentIndex($id)]['description'];
}

function isArticleComponent($id) {
	global $component;
	if($id == '' || $id == 'root')
		return false;
	$id_index = getComponentIndex($id);
	if(!array_key_exists('type', $component[$id_index]))
		return true;
	return strtolower(trim($component[$id_index]['type'])) == 'article';
}

function getPrevArticleId($id) {
	global $component;
	if(!isArticleComponent($id))
		return '';
	$id_index = getComponentIndex($id);
	$parent_id = getParentId($id);
	for($i = $id_index - 1; $i >= 0; $i--) {
		$candidate_id = $component[$i]['id'];
		if(getParentId($candidate_id) == $parent_id && isArticleComponent($candidate_id) && isComponentLocalized($candidate_id))
			return $candidate_id;
	}
	return '';
}

function getNextArticleId($id) {
	global $component;
	if(!isArticleComponent($id))
		return '';
	$id_index = getComponentIndex($id);
	$parent_id = getParentId($id);
	for($i = $id_index + 1; $i < count($component); $i++) {
		$candidate_id = $component[$i]['id'];
		if(getParentId($candidate_id) == $parent_id && isArticleComponent($candidate_id) && isComponentLocalized($candidate_id))
			return $candidate_id;
	}
	return '';
}

function getComponentModeASYNC($id) {
	global $component;
	return $component[getComponentIndex($id)]['js'];
}

function getSubComponents($id) {
	global $component;
	$pattern = "#".$id."\/[^\/]+$#";
	$matches = array_filter($component, function($a) use($pattern)  {
		return preg_match($pattern, $a['id']) && isComponentLocalized($a['id']);
	});

	$ary = array();
	foreach ($matches as $value) {
		array_push($ary, array($value['id'], $value['label']));
	}
	return $ary;
}

function resolveComponentFile($base) {
	// Prefer directory/index.* when the index actually exists. An empty
	// directory must not shadow a sibling flat file (e.g. root/ vs Root.php).
	if (is_dir($base)) {
		if (file_exists($base."/index.php"))
			return $base."/index.php";
		if (file_exists($base."/index.html"))
			return $base."/index.html";
	}
	if (file_exists($base.".php"))
		return $base.".php";
	if (file_exists($base.".html"))
		return $base.".html";
	return null;
}

function getComponentPath($id) {
	global $lang;

	$idPath = str_replace(' ', '_', $id);

	// Try language-specific path first for non-English
	if ($lang && $lang !== 'en') {
		$resolved = resolveComponentFile("../../HTML/Component/{$lang}/".$idPath);
		if ($resolved !== null)
			return $resolved;
		// Fall through to English
	}

	// English / fallback
	$resolved = resolveComponentFile("../../HTML/Component/".$idPath);
	if ($resolved !== null)
		return $resolved;

	// Preserve prior missing-file behavior for callers that still stat the path.
	return "../../HTML/Component/".$idPath.".html";
}

function getComponentPathStylized($id) {
	$pathStack = array();
	$idStack = "";
	$x = explode("/", $id);
	array_pop($x);
	foreach ($x as $value) {
		$idStack = $idStack.$value;
		$entry = array();
		$entry[] = $idStack;
		$entry[] = getComponentLabel($idStack);
		array_push($pathStack, $entry);
		$idStack = $idStack."/";	
	}
	return $pathStack;
}

function getComponentURLtrimmed($id) {
	global $lang;
	$prefix = ($lang && $lang !== 'en') ? '/' . $lang : '';
	if($id == 'root')
		return $prefix;
	else
		return ($prefix."/".$id);
}

function getComponentURL($id) {
	global $lang;
	$prefix = ($lang && $lang !== 'en') ? '/' . $lang : '';
	if($id == 'root')
		return $prefix."/";
	else
		return ($prefix."/".$id);
}

function getParentId($id) {
	if($id == "root")
		return "";
	$parentId = substr($id, 0, strrpos($id, "/"));
	if($parentId == "")
		return "root";
	else
		return $parentId;
}

function getPrevId($id) {
	global $component;
	$found = false;
	for($i = count($component)-1; $i >= 0; $i--) {
		if(!$found && $component[$i]['id'] == $id)
			$found = true;
		if($found) {
			if($i == 0)
				return "";
			else if(count($component[$i-1]) > 5 && in_array('hidden', explode(' ', strtolower($component[$i-1]['description']))))
				$i--;
			else if(getParentId($id) == getParentId($component[$i-1]['id']) && isComponentLocalized($component[$i-1]['id']))
				return $component[$i-1]['id'];
		}
	}
	exit_404("Wrong ID : ".$id);
}

function getNextId($id) {
	global $component;
	$found = false;
	for($i = 0; $i < count($component); $i++) {
		if(!$found && $component[$i]['id'] == $id)
			$found = true;
		if($found) {
			if($i == count($component)-1)
				return "";
			else if(count($component[$i+1]) > 5 && in_array('hidden', explode(' ', strtolower($component[$i+1]['description']))))
				$i++;
			else if(getParentId($id) == getParentId($component[$i+1]['id']) && isComponentLocalized($component[$i+1]['id']))
				return $component[$i+1]['id'];
		}
	}
	exit_404("Wrong ID : ".$id);
}

function findComponentImageAt($resourceId) {
	$bIndex;
	$ext;
	if(file_exists('../../Resource/'.$resourceId.'.jpg')) {
		$ext = 'jpg';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$resourceId.'.png')) {
		$ext = 'png';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$resourceId.'.svg')) {
		$ext = 'svg';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$resourceId.'/index.jpg')) {
		$ext = 'jpg';
		$bIndex = true;
	}
	else if(file_exists('../../Resource/'.$resourceId.'/index.png')) {
		$ext = 'png';
		$bIndex = true;
	}
	else if(file_exists('../../Resource/'.$resourceId.'/index.svg')) {
		$ext = 'svg';
		$bIndex = true;
	}
	else {
		return null;
	}

	$arr = array();
	$arr['file_path'] = '../../Resource/'.$resourceId.($bIndex? '/index' : '').'.'.$ext;
	$arr['url_path'] = $resourceId.($bIndex? '/index' : '').'.'.$ext;
	$arr['ext'] = $ext;

	return $arr;
}

function getComponentImage($id) {
	global $lang;

	// Prefer a language-specific asset when present; otherwise use the base image.
	if ($lang && $lang !== 'en') {
		$localized = findComponentImageAt($lang.'/'.$id);
		if ($localized !== null)
			return $localized;
	}

	return findComponentImageAt($id);
}


function renderComponentBody($id) {
	// Included article fragments expect these as locals of the caller.
	// Requiring from inside this function needs the same globals in scope.
	global $desc, $alt, $date, $config, $lang, $component, $bPublish;

	ob_start();
	require getComponentPath($id);
	$html = ob_get_clean();

	// Translations often omit the cover callout. If a cover image exists for
	// this slug (language-specific or base fallback), inject the standard cover.
	if ($id === 'root' || getComponentImage($id) === null)
		return $html;
	if (strpos($html, 'cover-image') !== false)
		return $html;

	$alt = $desc;
	ob_start();
	require __DIR__.'/../HTML/Fragment/Component_cover.php';
	echo "\n\t<h2 class='center'>".$desc."</h2>\n";
	$cover = ob_get_clean();

	if (preg_match("/<div\\s+id=['\"]message['\"]\\s*>/", $html)) {
		return preg_replace(
			"/<div\\s+id=['\"]message['\"]\\s*>/",
			"<div id='message'>\n\t\t".trim($cover),
			$html,
			1
		);
	}

	return $cover.$html;
}

function getComponentMetaImage($id) {
	$imageFile = getComponentImage($id);
	if ($imageFile == null || $id == 'root' || $imageFile['ext'] == 'svg')
		return "social.png";
	else
		return $imageFile['url_path'];
}

function getItemImageFilePath($id) {
	$imageFile = getComponentImage($id);
	if($imageFile != null)
		return $imageFile['file_path'];
}

function getItemImageFileURL($id) {
	$imageFile = getComponentImage($id);
	if($imageFile != null)
		return '/'.$imageFile['url_path'];
	else
		return "/resource/placeholder.svg";
}

function getItemImageFileExt($id) {
	$imageFile = getComponentImage($id);
	if($imageFile != null)
		return $imageFile['ext'];
	else
		return null;
}

function getTitleLabel($title) {
	return ($title == '')? 'home' : $title;
}

?>
