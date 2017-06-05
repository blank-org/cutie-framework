<?php
	require_once 'Fragment\link.php';

	function group($max_count, ...$list_items) {
?>
<div class='sidebar-nav-group'>
<?php
		foreach ($list_items as $list_item) {
			item($list_item[0], $list_item[1]);
		}
		placeholder( $max_count - 1 );
?>
</div>
<?php
	};

	function item($target, $title) {
?><a class='XURL block' href='/<?php echo url_part($target) ?>' data-target='<?php echo $target ?>' data-title='<?php echo $title ?>'><div><?php echo title_label($title) ?></div></a><?php
	}

	function placeholder($count) {
		while ($count--) {
?><div class='sidebar-nav-norm placeholder-empty'></div><?php
		}
	}

?>
