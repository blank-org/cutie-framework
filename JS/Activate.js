// external PROJECT_TITLE
var curTab;
var gTarget;
var URLid;

var menuActive = false;
var isTranslateButtonActive;
var isSearchButtonActive;

function getMenuContentHeight() {
	var nav = document.getElementById('nav-menu');
	if(!nav)
		return 0;
	var list = nav.querySelector('.sidebar-nav-li') || document.getElementById('nav-menu_container') || nav;
	var navStyle = window.getComputedStyle(nav);
	var extra = (parseFloat(navStyle.paddingTop) || 0) + (parseFloat(navStyle.paddingBottom) || 0);
	return Math.ceil(list.scrollHeight + extra);
}

function getViewportCanvasMinHeight() {
	var inner = document.getElementById('canvas-wrapper-inner-container');
	var wrapper = document.getElementById('wrapper');
	if(!inner)
		return 0;
	var pad = wrapper ? (parseFloat(window.getComputedStyle(wrapper).paddingBottom) || 0) : 0;
	var top = inner.getBoundingClientRect().top + (window.pageYOffset || document.documentElement.scrollTop || 0);
	return Math.max(0, Math.round(window.innerHeight - top - pad));
}

function getMenuFrameHeight() {
	return Math.max(getMenuContentHeight(), getViewportCanvasMinHeight());
}

function applyMenuFrameHeight(height, animate) {
	var canvas = document.getElementById('canvas-main');
	var nav = document.getElementById('nav-menu');
	if(!canvas || !nav)
		return;
	var px = height + 'px';
	if(animate) {
		var current = Math.max(canvas.scrollHeight, nav.scrollHeight);
		canvas.style.maxHeight = current + 'px';
		nav.style.maxHeight = current + 'px';
		nav.style.minHeight = current + 'px';
		void canvas.offsetHeight;
	}
	canvas.style.maxHeight = px;
	nav.style.maxHeight = px;
	nav.style.minHeight = px;
}

function restorePageFrameHeight() {
	var canvas = document.getElementById('canvas-main');
	var nav = document.getElementById('nav-menu');
	if(!canvas || !nav)
		return;
	canvas.style.maxHeight = null;
	nav.style.maxHeight = canvas.scrollHeight + 'px';
	nav.style.minHeight = canvas.scrollHeight + 'px';
}

var activateMenuFn = function() {
	document.getElementById('main-wrapper').classList.add('pml-open');
	document.getElementById('menu-button').classList.add('active');
	activeNav = 'pml-open';
	applyMenuFrameHeight(getMenuFrameHeight(), true);
	menuActive = true;
	if(!(typeof (ga) === 'undefined')) {
		ga('set', 'page', '/'+'menu');
		ga('send', 'pageview');
	}
}

var activateMenu = function() {
    // Update URL to /menu when user opens the menu
    recordState('menu', '');
    activateMenuFn();
}

var activateMainFn = function() {
	document.getElementById('main-wrapper').classList.remove('pml-open');
	document.getElementById('main-wrapper').classList.remove('hide_path_title_updated');
	document.getElementById('menu-button').classList.remove('active');
	menuActive = false;
}

var activateMain = function() {
	replaceState(curTab, document.getElementById('title').innerText);
	activateMainFn();	
}
