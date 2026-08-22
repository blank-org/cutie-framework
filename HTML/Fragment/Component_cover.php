<?php
	$imageFile = getComponentImage($id);
	if($imageFile == null) {
		echo "&lt; Exception: no cover image &gt;";
		return;
	}

	if($imageFile['ext'] == 'svg') {
		$svgfile = simplexml_load_file($imageFile['file_path']);
		$viewBox = preg_split('/[\s,]+/', trim((string)($svgfile['viewBox'] ?? '')));
		if(count($viewBox) === 4 && is_numeric($viewBox[2]) && is_numeric($viewBox[3])) {
			$width = (float)$viewBox[2];
			$height = (float)$viewBox[3];
		}
		else {
			$width = (float)preg_replace('/[^0-9.+-eE]/', '', (string)($svgfile['width'] ?? ''));
			$height = (float)preg_replace('/[^0-9.+-eE]/', '', (string)($svgfile['height'] ?? ''));
		}
		if($width <= 0 || $height <= 0) {
			$width = 1;
			$height = 1;
		}
	}
	else
		list($width, $height, $type, $attr) = getimagesize($imageFile['file_path']);
?>
<div class='content-image-container'>
	<div class='cover-image' style='padding-bottom: <?php echo round($height/$width*100, 2)?>%'>
		<img src='/<?php echo $imageFile['url_path']; ?>' alt='<?php echo $alt ?>'>
	</div>
</div>
<?php
	$site_image_credit = dirname(__DIR__, 3).'/HTML/Fragment/Image_credit.php';
	if(is_readable($site_image_credit))
		require $site_image_credit;
?>
