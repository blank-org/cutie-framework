<?php

	function group_text($class, $max_count, ...$list_items) {
?>
<div class='<?php echo $class ?>'>
<?php
		foreach ($list_items as $list_item) {
			item_text($list_item[0], $list_item[1]);
		}
		placeholder($max_count, sizeof($list_items));
?>
</div>
<?php
	};

	function item_text($target, $title) {
		if(strstr($target, '//') == true) {
?>
			<a class="content-link item_block" href='<?php echo $target ?>' ><div><?php echo $title ?></div></a>
<?php
		} else {
?>
			<a class="XURL item_block" href='<?php echo getComponentURL($target) ?>' data-target='<?php echo $target ?>' data-title='<?php echo $title ?>'><div><?php echo getTitleLabel($title) ?></div></a>
<?php
		}
	}
?>
