<?php
	global $lang;
	$imageUrlPath = $id.'/'.$img_title.'.'.$ext;
	$imageFileName = '../../Resource/'.$imageUrlPath;
	$darkImageUrlPath = isset($img_dark_title) ? $id.'/'.$img_dark_title.'.'.$ext : null;
	// Prefer a language-specific asset when present; otherwise use the base image.
	if ($lang && $lang !== 'en') {
		$localizedUrlPath = $lang.'/'.$id.'/'.$img_title.'.'.$ext;
		$localizedFileName = '../../Resource/'.$localizedUrlPath;
		if (file_exists($localizedFileName)) {
			$imageUrlPath = $localizedUrlPath;
			$imageFileName = $localizedFileName;
		}
	}

	$width = 0;
	$height = 0;
	if(file_exists($imageFileName)) {
		if($ext == 'svg') {
			$svgfile = @simplexml_load_file($imageFileName);
			$viewBox = ($svgfile !== false) ? preg_split('/[\s,]+/', trim((string)($svgfile['viewBox'] ?? ''))) : array();
			if(count($viewBox) === 4 && is_numeric($viewBox[2]) && is_numeric($viewBox[3])) {
				$width = (float)$viewBox[2];
				$height = (float)$viewBox[3];
			}
		}
		else {
			$info = @getimagesize($imageFileName);
			if($info !== false && !empty($info[0]) && !empty($info[1])) {
				$width = (float)$info[0];
				$height = (float)$info[1];
			}
		}
	}

	if($width <= 0 || $height <= 0) {
		$placeholderFile = '../../Resource/placeholder.svg';
		$imageUrlPath = 'resource/placeholder.svg';
		$ext = 'svg';
		$width = 160;
		$height = 110;
		if(file_exists($placeholderFile)) {
			$svgfile = @simplexml_load_file($placeholderFile);
			$viewBox = ($svgfile !== false) ? preg_split('/[\s,]+/', trim((string)($svgfile['viewBox'] ?? ''))) : array();
			if(count($viewBox) === 4 && is_numeric($viewBox[2]) && is_numeric($viewBox[3])) {
				$width = (float)$viewBox[2];
				$height = (float)$viewBox[3];
			}
		}
	}
?>
<div class='content-image-container' <?php if(isset($max_height)) { ?> style='max-height: <?php echo $height; ?>px' <?php } ?>>
	<div class="content-image <?php if(!empty($center)) echo 'center'; ?>" style="padding-bottom: <?php echo round($height/$width*100, 2)?>%">
<?php if( $ext == 'svg' && !(isset($extern)) ) { ?>
				<object data='/<?php echo $imageUrlPath ?>' loading='lazy'></object>
<?php } else { ?>
				<?php if($darkImageUrlPath) { ?>
				<img class="theme-image-light" src='/<?php echo $imageUrlPath ?>' loading='lazy' alt="<?php echo $alt ?>">
				<img class="theme-image-dark" src='/<?php echo $darkImageUrlPath ?>' loading='lazy' alt="">
				<?php } else { ?>
				<img src='/<?php echo $imageUrlPath ?>' loading='lazy' alt="<?php echo $alt ?>">
				<?php } ?>
<?php } ?>
	</div>
</div>
