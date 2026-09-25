<?php

/* Published Config/ID.tsv supplies the tree. Home.json supplies only durable
 * homepage policy, so ordinary published children require no PHP changes. */
function home_menu_config() {
	static $config = null;
	if ($config !== null) return $config;
	$path = __DIR__.'/../../../Config/Home.json';
	$config = is_readable($path) ? json_decode(file_get_contents($path), true) : array();
	if (!is_array($config)) $config = array();
	return $config;
}
function home_menu_slugs($key) {
	$values = home_menu_config()[$key] ?? array();
	return array_map('strtolower', is_array($values) ? $values : array());
}
function home_menu_branch_slug() {
	$branch = strtolower(trim((string)(home_menu_config()['branch'] ?? 'world')));
	return $branch !== '' && componentExists($branch) ? $branch : 'world';
}
function home_menu_leaf_slugs() { return home_menu_slugs('leafSlugs'); }
function home_menu_cap_children_of() { return home_menu_slugs('capChildrenOf'); }
function home_menu_large_group_slugs() { return home_menu_slugs('largeGroupSlugs'); }
function home_menu_is_large_group($slug) { return in_array(strtolower($slug), home_menu_large_group_slugs(), true); }
function home_menu_selected_child_slugs() {
	$out = array();
	foreach ((home_menu_config()['selectedChildren'] ?? array()) as $parent => $children)
		$out[strtolower($parent)] = array_map('strtolower', is_array($children) ? $children : array());
	return $out;
}
function home_menu_selected_child_limit() {
	$out = array();
	foreach ((home_menu_config()['childLimits'] ?? array()) as $parent => $limit)
		$out[strtolower($parent)] = max(0, (int)$limit);
	return $out;
}
function home_menu_parent_slug($slug) {
	$pos = strrpos($slug, '/');
	return $pos === false ? '' : substr($slug, 0, $pos);
}
function home_menu_branch_children($slug) {
	$slug = strtolower($slug);
	$synthetic = home_menu_config()['syntheticChildren'][$slug] ?? null;
	if (!is_array($synthetic)) return getSubComponents($slug);
	$out = array();
	foreach ($synthetic as $child) {
		$child = strtolower((string)$child);
		if (componentExists($child) && isComponentLocalized($child))
			$out[] = array($child, getComponentLabel($child));
	}
	return $out;
}
function home_menu_is_leaf($slug) {
	$slug = strtolower($slug);
	if (in_array($slug, home_menu_leaf_slugs(), true)) return true;
	$parent = home_menu_parent_slug($slug);
	return $parent !== '' && in_array($parent, home_menu_cap_children_of(), true);
}
function home_menu_visible_children($parent_slug, $children) {
	$parent_slug = strtolower($parent_slug);
	$allow = home_menu_selected_child_slugs();
	if (isset($allow[$parent_slug]) && count($allow[$parent_slug]) > 0) {
		$by_slug = array();
		foreach ($children as $child) $by_slug[strtolower($child[0])] = $child;
		$visible = array();
		foreach ($allow[$parent_slug] as $slug) if (isset($by_slug[$slug])) $visible[] = $by_slug[$slug];
		return array($visible, count($visible) < count($children));
	}
	$limits = home_menu_selected_child_limit();
	if (isset($limits[$parent_slug]) && count($children) > $limits[$parent_slug])
		return array(array_slice($children, 0, $limits[$parent_slug]), true);
	return array($children, false);
}
function home_menu_render_branch($slug) {
	$label = getComponentLabel($slug);
	$children = home_menu_branch_children($slug);
	$control_id = 'home-' . trim(preg_replace('/[^a-z0-9]+/i', '-', $slug), '-') . '-children';
?>
	<div class="home-menu-node">
		<button class="home-menu-toggle" type="button" aria-expanded="true" aria-controls="<?php echo $control_id ?>" aria-label="Collapse <?php echo htmlspecialchars($label) ?> descendants" data-home-menu-toggle data-home-menu-label="<?php echo htmlspecialchars($label) ?> descendants"><span aria-hidden="true"></span></button>
<?php group_image('page-list home-menu-level home-menu-level-0', 0, array($slug, $label)); ?>
		<div class="home-menu-subtree" id="<?php echo $control_id ?>">
<?php home_menu_render_tree($children, 1, $slug); ?>
		</div>
	</div>
<?php
}
function home_menu_render_tree($items, $level, $parent_slug) {
	$truncated = false;
	if ($parent_slug) list($items, $truncated) = home_menu_visible_children($parent_slug, $items);
	$count = count($items);
	foreach ($items as $index => $item) {
		$item[1] = trim($item[1]);
		$children = home_menu_branch_children($item[0]);
		$has_children = count($children) > 0 && !home_menu_is_leaf($item[0]);
		$show_more = $truncated && $index === $count - 1;
		$is_hub = strtolower($parent_slug) === strtolower(home_menu_config()['branch'] ?? 'world');
		$control_id = 'home-' . trim(preg_replace('/[^a-z0-9]+/i', '-', $item[0]), '-') . '-children';
		$node_class = 'home-menu-node';
		if ($is_hub) $node_class .= ' home-menu-hub';
		if (home_menu_is_large_group($item[0])) $node_class .= ' home-menu-large-group';
		if ($show_more) $node_class .= ' home-menu-has-more';
?>
		<div class="<?php echo $node_class ?>">
<?php if ($has_children) { ?>
			<button class="home-menu-toggle" type="button" aria-expanded="true" aria-controls="<?php echo $control_id ?>" aria-label="Collapse <?php echo htmlspecialchars($item[1]) ?> descendants" data-home-menu-toggle data-home-menu-label="<?php echo htmlspecialchars($item[1]) ?> descendants"><span aria-hidden="true"></span></button>
<?php }
		if ($show_more) { ?><div class="home-menu-tile-row"><?php }
		group_image('page-list home-menu-level home-menu-level-' . $level, 0, $item);
		if ($show_more) { ?>
			<a class="home-menu-more" href="<?php echo htmlspecialchars(getComponentURL($parent_slug)) ?>" aria-label="<?php echo htmlspecialchars('More ' . getComponentLabel($parent_slug) . ' articles') ?>"><span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span></a>
		</div>
<?php }
		if ($has_children) { ?>
			<div class="home-menu-subtree" id="<?php echo $control_id ?>">
<?php home_menu_render_tree($children, $level + 1, $item[0]); ?>
			</div>
<?php } ?>
		</div>
<?php
	}
}

function sidebar_menu_config() {
	static $config = null;
	if ($config !== null) return $config;
	$path = __DIR__.'/../../../Config/Menu.json';
	$config = is_readable($path) ? json_decode(file_get_contents($path), true) : array();
	if (!is_array($config)) $config = array();
	return $config;
}

function sidebar_menu_groups() {
	$groups = sidebar_menu_config()['groups'] ?? array();
	$out = array();
	foreach (is_array($groups) ? $groups : array() as $group) {
		if (!is_array($group) || !in_array($group['kind'] ?? '', array('image', 'text'), true))
			continue;
		$items = array();
		$slugs = $group['items'] ?? array();
		if (!empty($group['includeHomeHubs'])) {
			$slugs = array_merge($slugs, home_menu_config()['syntheticChildren'][home_menu_branch_slug()] ?? array());
		}
		foreach ($slugs as $slug) {
			if (is_string($slug) && componentExists($slug))
				$items[] = $slug;
		}
		if ($items) $out[] = array('kind' => $group['kind'], 'items' => $items);
	}
	return $out;
}

function sidebar_menu_label($slug, $language) {
	$overrides = sidebar_menu_config()['labels'][$slug] ?? array();
	if (is_array($overrides)) {
		if (isset($overrides[$language]) && $overrides[$language] !== '')
			return $overrides[$language];
		if ($language === 'en' && isset($overrides['en']) && $overrides['en'] !== '')
			return $overrides['en'];
	}
	return getComponentLabel($slug);
}
