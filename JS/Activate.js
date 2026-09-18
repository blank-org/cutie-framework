// external PROJECT_TITLE
var curTab;
var gTarget;
var URLid;

var menuActive = false;
var isTranslateButtonActive;
var isSearchButtonActive;

var footerFadeToken = 0;
var PAGE_SLIDE_MS = 1000;

function hideFooterNow() {
	var footer = document.getElementById('footer-wrapper');
	if(!footer)
		return;
	footer.style.transition = 'none';
	footer.classList.add('hide');
	void footer.offsetWidth;
	footer.style.transition = '';
}

function fadeFooterIn() {
	var footer = document.getElementById('footer-wrapper');
	if(!footer)
		return;
	footer.classList.add('hide');
	footer.style.transition = 'none';
	void footer.offsetWidth;
	footer.style.transition = '';
	requestAnimationFrame(function() {
		footer.classList.remove('hide');
	});
}

function fadeFooterAfterPageSlide(afterSlide) {
	var token = ++footerFadeToken;
	hideFooterNow();
	setTimeout(function() {
		if(token !== footerFadeToken)
			return;
		if(afterSlide)
			afterSlide();
		requestAnimationFrame(function() {
			if(token !== footerFadeToken)
				return;
			fadeFooterIn();
		});
	}, PAGE_SLIDE_MS);
}

function settleMenuLayout() {
	var main = document.getElementById('main-wrapper');
	if(main)
		main.classList.add('pml-settled');
}

function unsettleMenuLayout() {
	var main = document.getElementById('main-wrapper');
	if(!main)
		return;
	main.classList.remove('pml-settled');
	void main.offsetWidth;
}

var activateMenuFn = function() {
	document.getElementById('main-wrapper').classList.add('pml-open');
	document.getElementById('menu-button').classList.add('active');
	activeNav = 'pml-open';
	if(!menuActive) {
		fadeFooterAfterPageSlide(function() {
			if(menuActive)
				settleMenuLayout();
		});
	}
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
	unsettleMenuLayout();
	document.getElementById('main-wrapper').classList.remove('pml-open');
	document.getElementById('main-wrapper').classList.remove('hide_path_title_updated');
	document.getElementById('menu-button').classList.remove('active');
	menuActive = false;
}

var activateMain = function() {
	replaceState(curTab, document.getElementById('title').innerText);
	activateMainFn();	
}
