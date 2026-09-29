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
		$full_title = componentExists($target) ? getComponentTitle($target) : $title;
		$hover_title = htmlspecialchars($full_title, ENT_QUOTES, 'UTF-8');
?>
<a
<?php
		$imagePath = getItemImageFileURL($target);
		if($external != null) {
?>
	class='item_block_container' href='<?php echo $external ?>' target='_blank' rel='noopener' title='<?php echo $hover_title ?>' onclick="trackOutboundLink('<?php echo getTitleLabel($title) ?>','<?php echo $external ?>');">
<?php
		}
		else {
?>
	class='XURL item_block_container' href='<?php echo getComponentURL($target) ?>' data-target='<?php echo $target ?>' data-title='<?php echo $title ?>' title='<?php echo $hover_title ?>' aria-label='<?php echo $hover_title ?>'>
<?php	
		}
?><img class='<?php echo itemBlockImageClass($target, 'item_block_image_visible') ?>'<?php $item_style = itemBlockImageStyle($target); if($item_style !== '') echo " style='".$item_style."'"; ?> src='<?php echo $imagePath ?>' loading='lazy' alt="Navigation - link to <?php echo $target ?>"><div class='item_block_text'><div><?php echo getTitleLabel($title) ?></div><?php if($external) { ?><div class='external'><img src='/resource/external.svg' loading='lazy' alt="Navigation - link to <?php echo $target ?>"></div><?php } ?></div></a><?php
	}
?>
