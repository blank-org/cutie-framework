<?php
	function link_xurl($target, $title) {
?><a class="content-link XURL" href='<?php echo getComponentURLtrimmed($target) ?>' data-target='<?php echo $target ?>' data-title='<?php echo $title ?>'><?php echo getTitleLabel($title) ?></a><?php	
	}
?>
