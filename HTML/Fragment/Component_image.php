<?php
	global $lang;
	$imageUrlPath = $id.'/'.$img_title.'.'.$ext;
	$imageFileName = '../../Resource/'.$imageUrlPath;
	// Prefer a language-specific asset when present; otherwise use the base image.
	if ($lang && $lang !== 'en') {
		$localizedUrlPath = $lang.'/'.$id.'/'.$img_title.'.'.$ext;
		$localizedFileName = '../../Resource/'.$localizedUrlPath;
		if (file_exists($localizedFileName)) {
			$imageUrlPath = $localizedUrlPath;
			$imageFileName = $localizedFileName;
		}
	}
	if($ext == 'svg') {
		$svgfile = simplexml_load_file($imageFileName);
		list($NULL, $NULL, $width, $height) = explode(' ', $svgfile['viewBox']);
	}
	else
		list($width, $height, $type, $attr) = getimagesize($imageFileName);
?>
<div class='content-image-container' <?php if(isset($max_height)) { ?> style='max-height: <?php echo $height; ?>px' <?php } ?>>
	<div class="content-image <?php if(!empty($center)) echo 'center'; ?>" style="padding-bottom: <?php echo round($height/$width*100, 2)?>%">
<?php if( $ext == 'svg' && !(isset($extern)) ) { ?>
				<object data='/<?php echo $imageUrlPath ?>' loading='lazy'></object>
<?php } else { ?>
				<img src='/<?php echo $imageUrlPath ?>' loading='lazy' alt="<?php echo $alt ?>">
<?php } ?>
	</div>
</div>
