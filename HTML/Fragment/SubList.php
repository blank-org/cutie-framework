<?php
	$sub_components = getSubComponents($id);
	if ($sub_components) {
		group_image_id('sub-list', 'center page-list', 0, ...$sub_components);
	} else {
		$next_tree_article = getNextTreeArticleId($id);
		if ($next_tree_article !== '') { ?>
<div id='sub-list' class='center page-list'>
	<a class='XURL item_block_container' href='<?php echo htmlspecialchars(getComponentURL($next_tree_article), ENT_QUOTES) ?>' data-target='<?php echo htmlspecialchars($next_tree_article, ENT_QUOTES) ?>' data-title='<?php echo htmlspecialchars(getComponentLabel($next_tree_article), ENT_QUOTES) ?>' aria-label='<?php echo htmlspecialchars('Fast forward to ' . getComponentLabel($next_tree_article), ENT_QUOTES) ?>'>
		<div class='item_block_text'><div><svg aria-hidden='true' focusable='false' width='18' height='14' viewBox='0 0 20 16' style='vertical-align:-2px' fill='currentColor'><path d='M0 1 9 8 0 15zM10 1 19 8 10 15z'/></svg> <?php echo htmlspecialchars(getComponentLabel($next_tree_article), ENT_QUOTES) ?></div><div class='arrow'>&#x25B6;</div></div>
	</a>
</div>
<?php	}
	}
?>
