<?php
	require_once '../../Framework/API/Pre.php';
	require_once 'Fragment/Item.php';
	
	$component = loadComponents();

	$id = getOrigCall();
	$menu_active_class = "";

	if(strlen($id) == 0)
		$id = "root";
	$menu_active_class;
	if($id == "menu") {
		$menu_active_class = "pml-open";
		$id = "root";
	}
	
	$label = getComponentPageLabel($id);
	$title = getComponentTitle($id);
	$desc = getComponentDesc($id);
	$date = getFileDate(getComponentPath($id));

	// $dom = new DOMDocument();

	// ob_start();
	require '..\..\HTML\Template\Base.php';
	// $dom->loadHTML(ob_get_contents());
	// ob_end_clean();

	// AddAttribute(($dom->getElementById($id)), "class", "sidebar-nav-high");
	// echo $dom->saveHTML();
?>
