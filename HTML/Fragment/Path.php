<?php
	if($id == 'root')
		echo '&nbsp;';
	else {
		if(isComponentExternRoot('root')) {
?>
	<a class='content-link' href='/'>&#8962; </a>
<?php
		} else {
?>
	<a class='XURL content-link' href='/' data-target='root' data-title=''>&#8962; </a>
<?php
		}
?>
	<span class='path-separator'>\</span>
<?php
		foreach (getComponentPathStylized($id) as $key => $value) {
?>
		<a class='XURL content-link' href='<?php echo '/'.$value[0] ?>' data-target='<?php echo $value[0] ?>' data-title='<?php echo $value[1] ?>'>
			<?php echo $value[1] ?>
		</a>
		<span class='path-separator'>\</span>
<?php
		}
	}
?>
