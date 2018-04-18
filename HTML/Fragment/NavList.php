<?php
	$prevId = getPrevId($id);
	$parentId = getParentId($id);
	$nextId = getNextId($id);
?>
<div id='nav-list' class='center page-list'>
	<a id='nav-menu-prev'
	<?php if($prevId != "") { ?>
		class='XURL item_block_container' href='<?php echo "/".$prevId ?>' data-target='<?php echo $prevId ?>' data-title='<?php echo getComponentTitle($prevId) ?>' >
		<img class='item_block_image item_block_image_visible' src='<?php echo getItemImageFileURL($prevId) ?>'><div class="item_block_text"><div class='arrow'>&#x25C1;</div><div><?php echo getComponentTitle($prevId) ?></div></div>
	<?php }
		else { ?>
		class='item_block-disabled sidebar-nav-norm sidebar-nav-l-1' >
		&nbsp;
	<?php } ?>
	</a><a id='nav-menu-next'
	<?php if($nextId != "") {?>
		class='XURL item_block_container' href='<?php echo "/".$nextId ?>' data-target='<?php echo $nextId ?>' data-title='<?php echo getComponentTitle($nextId) ?>' >
		<img class='item_block_image item_block_image_visible' src='<?php echo getItemImageFileURL($nextId) ?>'><div class="item_block_text"><div><?php echo getComponentTitle($nextId) ?></div><div class='arrow'>&#x25B7;</div></div>
	<?php }
		else { ?>
		class='item_block-disabled sidebar-nav-norm sidebar-nav-l-1' >
		&nbsp;
	<?php } ?>
	</a><a id='nav-menu-up' class='XURL item_block_container <?php if($parentId == "root") echo 'center-arrow'?>' href='<?php echo "/".$parentId ?>' data-target='<?php echo $parentId ?>' data-title='<?php echo getComponentTitle($parentId) ?>'>
		<img class='item_block_image <?php if($parentId == "root") echo "item_block_image_hidden"?>' src='<?php echo getItemImageFileURL($parentId) ?> '><div class="item_block_text"><div><?php if($parentId != "root") echo getComponentTitle($parentId); ?></div><div class='arrow'>&#x25B3;</div></div>
	</a>
</div>
