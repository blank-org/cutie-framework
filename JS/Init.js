function initLoad() {
	if(!initLoadDone && document.readyState !== 'loading') {
		init();
		initLoadDone = true;
	}
}

function prepareGoogleTranslate(translate_button) {
	if(document.querySelector('script[data-google-translate]'))
		return;

	var scriptTag = document.createElement('script');
	scriptTag.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
	scriptTag.async = true;
	scriptTag.setAttribute('data-google-translate', '');
	translate_button.parentNode.appendChild(scriptTag);
}

function init() {
	
		setXURL(document);
		var hashID = getHashID();
		URLid = getURLid();
	
		var canvas_main = document.querySelector('#canvas-main'),
			menu_button = document.querySelector('.toggle-push-left'),
			menu_items = document.querySelectorAll('.XURL'),
			header_button = document.querySelector('#header_button'),
			translate_button = document.querySelector('#translate-button'),
			search_button = document.querySelector('#search-button'),
			translate_box = document.querySelector('#translation-controls'),
			search_box = document.querySelector('#search_box');
	
		if(!!hashID) {
			curTab = 'root';
			// Only treat hash as a canvas deep-link when it points at an XURL control.
			// In-page section anchors (#portfolio, #contact, …) are handled by root.js.
			var hashEl = document.getElementById(hashID);
			if(hashEl && hashEl.classList && hashEl.classList.contains('XURL') && hashEl.getAttribute('data-target'))
				loadCanvasH(hashEl);
		}
		else if(!!URLid)
			curTab = URLid;
		else
			curTab = 'root';
	
		if(URLid == 'menu') {
			menuActive = true;
			menu_button.classList.add('active');
			applyMenuFrameHeight(getMenuFrameHeight(), false);
		}
		else {
			document.querySelector('#nav-menu').style.maxHeight = canvas_main.scrollHeight+'px';
			document.querySelector('#nav-menu').style.minHeight = canvas_main.scrollHeight+'px';
		}
	
		if (!hashID && !URLid)
			replaceState('root', '');
		else if(URLid == 'menu')
			replaceState('menu', '');
		else if (URLid)
			replaceState(URLid, document.getElementById('title').textContent);
		else if (hashID)
			// hash-only URLs (/#portfolio): keep hash, but attach SPA state so Back works after /work.
			ensureHistoryState('root', '');
	
		menu_button.addEventListener( 'click', function() {
			if (!menuActive) {
				activateMenu();
			}
			else {
				activateMain();
				restorePageFrameHeight();
			}
		} );
	
		translate_button.addEventListener( 'click', function() {
			if(typeof isTranslateButtonActive === 'undefined')
				isTranslateButtonActive = false;
			if(!isTranslateButtonActive) {
				translate_button.classList.add('header-button-active');
				translate_box.classList.remove('hide_display');
				isTranslateButtonActive = true;
			}
			else {
				translate_button.classList.remove('header-button-active');
				translate_box.classList.add('hide_display');
				isTranslateButtonActive = false;
			}
		} );
	
		search_button.addEventListener( 'click', function() {
			if(isSearchButtonActive === undefined) {
				gcse_init();
				isSearchButtonActive = false;
			}
			if(!isSearchButtonActive) {
				search_button.classList.add('header-button-active');
				search_box.classList.remove('hide_display');
				isSearchButtonActive = true;
				var search_input = search_box.querySelector('input[type="search"], input[name="q"], input.gsc-input');
				if(search_input)
					search_input.focus();
			}
			else {
				search_button.classList.remove('header-button-active');
				search_box.classList.add('hide_display');
				isSearchButtonActive = false;
			}
		});
	
		[].forEach.call(document.getElementsByClassName('coming-soon'), function(el) { el.addEventListener( 'click', function() {
			if(!(typeof (ga) === 'undefined')) {
				ga('send', 'event', {
					'eventCategory': 'download',
					'eventAction': 'click'
				});
			}
			alert("Hold your breath! Coming soon..");
		});});
	
		[].slice.call(menu_items).forEach( function(el,i) {
				el.addEventListener( 'click', function() {
					activateMainFn();
				} );
			} );
	
		if (!supportsSvg()) {
			var image_div = document.getElementsByClassName('image');
			var i;
			var l = image_div.length;
			for (i = 0; i < l; i++) {
				image_div[i].classList.add('no-svg');
			}
			// or even .className += ' no-svg'; for deeper support
		}
	
		if(typeof initDarkMode !== 'undefined')
			initDarkMode();

		prepareGoogleTranslate(translate_button);

		initPageFunction(curTab);
		return false;

}
