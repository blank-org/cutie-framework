// external PROJECT_TITLE
var curTab;
var gTarget;
var URLid;

var menuActive = false;
var isTranslateButtonActive;
var isSearchButtonActive;

var activateMenuFn = function() {
	var nav_menu = document.querySelector( '#nav-menu' ),
		canvas_main = document.querySelector( '#canvas-main' ),
		main_wrapper = document.querySelector( '#main-wrapper' ),
		menu_button = document.querySelector( '#menu-button' );
	main_wrapper.classList.add('pml-open');
	menu_button.classList.add('active');
	activeNav = 'pml-open';
	var height = nav_menu.scrollHeight;
	canvas_main.style.maxHeight = height+'px';
	nav_menu.style.maxHeight = null;
	menuActive = true;
	if(!(typeof (ga) === 'undefined')) {
		ga('set', 'page', '/'+'menu');
		ga('send', 'pageview');
	}
}

var activateMenu = function() {
	window.history.pushState({'id':curTab}, '', '/'+curTab);
	activateMenuFn();
}

var activateMainFn = function() {
	document.getElementById('main-wrapper').classList.remove('pml-open');
	document.getElementById('main-wrapper').classList.remove('hide_path_title_updated');
	document.getElementById('menu-button').classList.remove('active');
	menuActive = false;
}

var activateMain = function() {
	window.history.pushState({'id':curTab}, '', '/'+curTab);
	activateMainFn();	
}

