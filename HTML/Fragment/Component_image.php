<?php
	$imageFileName = '..\\..\\Resource\\'.$id.'\\'.$title.'.'.$ext;
	if($ext == 'svg') {
		$svgfile = simplexml_load_file($imageFileName);
		list($NULL, $NULL, $width, $height) = explode(' ', $svgfile['viewBox']);
	}
	else
		list($width, $height, $type, $attr) = getimagesize($imageFileName);
?>
<div class='content-image-container' <?php if(isset($max_height)) { ?> style='max-height: <?php echo $height; ?>px' <?php } ?>>
	<div class="content-image <?php if(!empty($center)) echo 'center'; ?>" style="padding-bottom: <?php echo round($height/$width*100, 2)?>%">
<?php if($ext == 'svg') { ?>
				<object data='/<?php echo $id.'/'.$title.'.'.$ext ?>' loading='lazy'></object>
<?php } else { ?>
				<img src='/<?php echo $id.'/'.$title.'.'.$ext ?>' loading='lazy' alt="<?php echo $alt ?>">
<?php } ?>
	</div>
</div>
