<?php
	$prevId = getPrevId($id);
	$parentId = getParentId($id);
	$nextId = getNextId($id);
?>
<div id='nav-list' class='center page-list'>
	<a id='nav-menu-prev'
	<?php if($prevId != '') { ?>
		class='XURL item_block_container' href='<?php echo '/'.$prevId ?>' data-target='<?php echo $prevId ?>' data-title='<?php echo getComponentLabel($prevId) ?>' >
		<img class='item_block_image item_block_image_visible' src='<?php echo getItemImageFileURL($prevId) ?>' loading='lazy' alt='Navigation - previous page tile'><div class='item_block_text'><div class='arrow'>&#x25C4;</div><div><?php echo getComponentLabel($prevId) ?></div></div>
	<?php }
		else { ?>
		class='item_block-disabled sidebar-nav-norm sidebar-nav-l-1' >
		&nbsp;
	<?php } ?>
	</a><a id='nav-menu-next'
	<?php if($nextId != '') {?>
		class='XURL item_block_container' href='<?php echo '/'.$nextId ?>' data-target='<?php echo $nextId ?>' data-title='<?php echo getComponentLabel($nextId) ?>' >
		<img class='item_block_image item_block_image_visible' src='<?php echo getItemImageFileURL($nextId) ?>' loading='lazy' alt='Navigation - next page tile'><div class='item_block_text'><div><?php echo getComponentLabel($nextId) ?></div><div class='arrow'>&#x25BA;</div></div>
	<?php }
		else { ?>
		class='item_block-disabled sidebar-nav-norm sidebar-nav-l-1' >
		&nbsp;
	<?php }
		if(isComponentExternRoot($parentId)) {?>
	</a><a id='nav-menu-up' class='content-link item_block_container center-arrow' href='/'>
		<img class="item_block_image item_block_image_hidden" loading='lazy' src='<?php echo getItemImageFileURL($parentId) ?> ' alt='Navigation - up level tile'><div class='item_block_text'><div class='arrow'>&#x25B2;</div></div>
	</a>
	<?php } else {?>
	</a><a id='nav-menu-up' class='XURL item_block_container <?php if($parentId == 'root') echo 'center-arrow'?>' href='<?php echo getComponentURL($parentId) ?>' data-target='<?php echo $parentId ?>' data-title='<?php echo getComponentLabel($parentId) ?>'>
		<img class='item_block_image <?php if($parentId == 'root') echo 'item_block_image_hidden'?>' loading='lazy' src='<?php echo getItemImageFileURL($parentId) ?> ' alt='Navigation - up level tile'><div class='item_block_text'><div><?php if($parentId != 'root') echo getComponentLabel($parentId); ?></div><div class='arrow'>&#x25B2;</div></div>
	</a> <?php } ?>
</div>
