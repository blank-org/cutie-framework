<?php

function loadComponents() {
	global $bPublish;
	global $bFull;

	$fHandle = fopen("../../../Config/ID.tsv", "r");
	fgetcsv($fHandle);
	$i = 0;
	while(($tsvLine = fgetcsv($fHandle, 0, "\t")) !== FALSE) {
		if($tsvLine[0] == "#") {
			if($bFull)
				array_shift($tsvLine);
			else
				continue;
		}
		for($j = 0; $j < count($tsvLine); $j++)
			$component[$i][$j] = $tsvLine[$j];
		$i++;
	}

	return $component;
}

function getComponentIndex($id) {
	global $component;
	
	for($i = 0; $i < count($component); $i++)
		if($component[$i][0] == $id)
				return $i;
				
	exit_404("Wrong ID"." : ".$id);
}

function getComponentPageLabel($id) {
	global $component;
	$componentPageLabel = getComponentLabel($id);
	$id_index = getComponentIndex($id);
	if( count($component[$id_index]) > 5 && in_array( 'HIDE_TITLE', explode(' ', $component[$id_index][5]) ) )
		return '';
	else
		return $componentPageLabel;
}

function isComponentExternRoot($id) {
	global $component;
	$id_index = getComponentIndex($id);
	return ( $id == 'root' && count($component[$id_index]) > 5 && in_array( 'EXTERNAL', explode(' ', $component[$id_index][5]) ) );	
}

function getComponentLabel($id) {
	global $component;

	if($id == '')
		return '';
	else
		return $component[getComponentIndex($id)][1];
}

function getComponentTitle($id) {
	global $component;

	if($id == '')
		return '';
	else
		return $component[getComponentIndex($id)][2];
}

function getComponentDesc($id) {
	global $component;
	return $component[getComponentIndex($id)][4];
}

function getComponentModeASYNC($id) {
	global $component;
	return $component[getComponentIndex($id)][3];
}

function getSubComponents($id) {
	global $component;

	$pattern = "#".$id."\/[^\/]+$#";
	$matches = array_filter($component, function($a) use($pattern)  {
		return preg_grep($pattern, $a);
	});

	$ary = [];
	foreach ($matches as $key => $value) {
		array_push($ary, array($value[0], $value[1]));
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
	foreach ($x as $key => $value) {
		$idStack = $idStack.$value;
		$x = array();
		array_push($x, $idStack);
		array_push($x, getComponentLabel($idStack));
		array_push($pathStack, $x);
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
		if($found == false && $component[$i][0] == $id)
			$found = true;
		if($found == true) {
			if($i == 0)
				return "";
			else if( count($component[$i-1]) > 5 && in_array( 'HIDDEN', explode(' ', $component[$i-1][5]) ) )
				$i--;
			else if(getParentId($id) == getParentId($component[$i-1][0]))
				return $component[$i-1][0];
		}
	}
	exit_404("Wrong ID"." : ".$id);
}

function getNextId($id) {
	global $component;
	$found = false;
	for($i = 0; $i < count($component); $i++) {
		if($found == false && $component[$i][0] == $id)
			$found = true;
		if($found == true) {
			if($i == count($component)-1)
				return "";
			else if( count($component[$i+1]) > 5 && in_array( 'HIDDEN', explode(' ', $component[$i+1][5]) ) )
				$i++;
			else if(getParentId($id) == getParentId($component[$i+1][0]))
				return $component[$i+1][0];
		}
	}
	exit_404("Wrong ID"." : ".$id);
}

function getComponentImage($id) {
	$bIndex;
	$ext;
	if(file_exists('../../Resource/'.$id.'.'.'jpg')) {
		$ext = 'jpg';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$id.'.'.'png')) {
		$ext = 'png';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$id.'.'.'svg')) {
		$ext = 'svg';
		$bIndex = false;
	}
	else if(file_exists('../../Resource/'.$id.'/'.'Index'.'.'.'jpg')) {
		$ext = 'jpg';
		$bIndex = true;
	}
	else if(file_exists('../../Resource/'.$id.'/'.'Index'.'.'.'png')) {
		$ext = 'png';
		$bIndex = true;
	}
	else if(file_exists('../../Resource/'.$id.'/'.'Index'.'.'.'svg')) {
		$ext = 'svg';
		$bIndex = true;
	}
	else {
		return null;
	}

	$arr = [];
	$arr['file_path'] = '../../Resource/'.$id.($bIndex? '/'.'Index' : null).'.'.$ext;
	$arr['url_path'] = $id.($bIndex? '/'.'index' : null).'.'.$ext;
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
