<?php
	$imageFile = getComponentImage($id);
	if($imageFile == null) {
		echo "&lt; Exception: no cover image &gt;";
		return;
	}

	if($imageFile['ext'] == 'svg') {
		$svgfile = simplexml_load_file($imageFile['file_path']);
		list($NULL, $NULL, $width, $height) = explode(' ', $svgfile['viewBox']);
	}
	else
		list($width, $height, $type, $attr) = getimagesize($imageFile['file_path']);
?>
<div class='content-image-container'>
	<div class='cover-image' style='padding-bottom: <?php echo round($height/$width*100, 2)?>%'>
		<img src='/<?php echo $id.'.'.$imageFile['ext']; ?>' alt='<?php echo $alt ?>'>
	</div>
</div>
