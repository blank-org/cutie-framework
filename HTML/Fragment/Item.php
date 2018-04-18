<?php
	require_once 'Fragment\Link.php';
	require_once 'Fragment\Item_text.php';
	require_once 'Fragment\Item_image.php';

	function placeholder($max_count, $nItems) {
		if($max_count) {
			$count = ($max_count - $nItems % $max_count) % $max_count;
			while ($count--) {
				?><div class='sidebar-nav-norm item_block-placeholder'></div><?php
			}
		}
	}
?>
