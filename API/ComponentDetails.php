<?php

function loadComponents() {
	global $bPublish;
	global $bFull;
	
	$file_path = "../../../Config/ID.tsv";
	$fHandle = fopen($file_path, "r");
	// Read header and convert column names to lowercase
	$header = fgetcsv($fHandle, 0, "\t");
	$header = array_map('strtolower', $header);
	
	$component = array();
	while(($tsvLine = fgetcsv($fHandle, 0, "\t")) !== FALSE) {
		// Use "draft" as the marker for non-published rows
		if($tsvLine[0] == "draft") {
			if($bFull)
				array_shift($tsvLine);
			else
				continue;
		}
		$row = array();
		for($j = 0; $j < count($header); $j++) {
			$row[$header[$j]] = isset($tsvLine[$j]) ? $tsvLine[$j] : "";
		}
		$component[] = $row;
	}
	fclose($fHandle);
	return $component;
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
	return ( $id == 'root' && count($component[$id_index]) > 5 && in_array('external', explode(' ', strtolower($component[$id_index]['description']))));
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

function getComponentModeASYNC($id) {
	global $component;
	return $component[getComponentIndex($id)]['js'];
}

function getSubComponents($id) {
	global $component;
	$pattern = "#".$id."\/[^\/]+$#";
	$matches = array_filter($component, function($a) use($pattern)  {
		return preg_match($pattern, $a['id']);
	});

	$ary = array();
	foreach ($matches as $value) {
		array_push($ary, array($value['id'], $value['label']));
	}
	return $ary;
}

function getComponentPath($id) {
	$s = "../../HTML/Component/".str_replace(' ','_', $id);
	if(file_exists($s)) {
		$s = $s."/Index";
	}
	if(file_exists($s.".php"))
		return ($s.".php");
	else
		return ($s.".html");
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
	if($id == 'root')
		return "";
	else
		return ("/".$id);
}

function getComponentURL($id) {
	if($id == 'root')
		return "/";
	else
		return ("/".$id);
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
			else if(getParentId($id) == getParentId($component[$i-1]['id']))
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
			else if(getParentId($id) == getParentId($component[$i+1]['id']))
				return $component[$i+1]['id'];
		}
	}
	exit_404("Wrong ID : ".$id);
}

function getComponentImage($id) {
	$bIndex;
	$ext;
	if(file_exists('../../Resource/'.$id.'.jpg')) {
		$ext = 'jpg';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$id.'.png')) {
		$ext = 'png';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$id.'.svg')) {
		$ext = 'svg';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$id.'/Index.jpg')) {
		$ext = 'jpg';
		$bIndex = true;
	}
	else if(file_exists('../../Resource/'.$id.'/Index.png')) {
		$ext = 'png';
		$bIndex = true;
	}
	else if(file_exists('../../Resource/'.$id.'/Index.svg')) {
		$ext = 'svg';
		$bIndex = true;
	}
	else {
		return null;
	}

	$arr = array();
	$arr['file_path'] = '../../Resource/'.$id.($bIndex? '/Index' : '').'.'.$ext;
	$arr['url_path'] = $id.($bIndex? '/index' : '').'.'.$ext;
	$arr['ext'] = $ext;

	return $arr;
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
