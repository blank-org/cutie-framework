<?php
	
	function group_image($div_class, $max_count, ...$list_items) {
		group_image_id('', $div_class, $max_count, ...$list_items);
	}
	
	function group_image_id($div_id, $div_class, $max_count, ...$list_items) {
?>
<div <?php if($div_id != '') echo "id='$div_id'" ?> class='<?php echo $div_class ?>'>
<?php
		foreach ($list_items as $list_item) {
			if(sizeof($list_item) > 2)
				item_image($list_item[0], $list_item[1], $list_item[2]);
			else
				item_image($list_item[0], $list_item[1], null);
		}
		placeholder($max_count, sizeof($list_items));
?>
</div>
<?php
	};

	function item_image($target, $title, $external) {
?>
<a
<?php
		$imagePath = getItemImageFileURL($target);
		if($external != null) {
?>
	class='item_block_container' href='<?php echo $external.'/'.$target ?>' target='_blank' onclick="trackOutboundLink('<?php echo getTitleLabel($title) ?>','<?php echo $external.'/'.$target ?>'); return false;">
<?php
		}
		else {
?>
	class='XURL item_block_container' href='<?php echo getComponentURL($target) ?>' data-target='<?php echo $target ?>' data-title='<?php echo $title ?>'>
<?php	
		}
?><img class='item_block_image item_block_image_visible' src='<?php echo $imagePath ?>' alt="Navigation - link to <?php echo $target ?>"><div class='item_block_text'><div><?php echo getTitleLabel($title) ?></div><?php if($external) { ?><div class='external'><img src='/resource/external.svg'></div><?php } ?></div></a><?php
	}
?>
