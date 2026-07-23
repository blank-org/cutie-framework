function initLoad() {
	if(!initLoadDone && document.readyState === 'interactive') {
		init();
		initLoadDone = true;
	}
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
			translate_box = document.querySelector('#google_translate_element'),
			search_box = document.querySelector('#search_box');
	
		if(!!hashID) {
			curTab = 'root';
			loadCanvas(document.getElementById(hashID));
		}
		else if(!!URLid)
			curTab = URLid;
		else
			curTab = 'root';
	
		if(URLid == 'menu') {
			menuActive = true;
			menu_button.classList.add('active');
			canvas_main.style.maxHeight = document.querySelector('#nav-menu').scrollHeight+'px';
		}
		else
			document.querySelector('#nav-menu').style.maxHeight = canvas_main.scrollHeight+'px';
	
		if (!hashID && !URLid)
			replaceState('root', '');
		else if(URLid == 'menu')
			replaceState('menu', '');
		else
			recordState(URLid, '');
	
		menu_button.addEventListener( 'click', function() {
			if (!menuActive) {
				activateMenu();
			}
			else {
				activateMain();
				canvas_main.style.maxHeight = null;
				document.querySelector('#nav-menu').style.maxHeight = canvas_main.scrollHeight+'px';
			}
		} );
	
		var lang_switcher = document.querySelector('#language-switcher'),
			lang_has_native = lang_switcher && lang_switcher.querySelector('.lang-link');

		translate_button.addEventListener( 'click', function() {
			if(typeof isTranslateButtonActive === 'undefined') {
				// If native translations available, show language switcher
				// Otherwise fall back to Google Translate
				if (!lang_has_native) {
					var scriptTag = document.createElement('script');
					scriptTag.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
					translate_button.parentNode.appendChild(scriptTag);
				}
				isTranslateButtonActive = false;
			}
			if(!isTranslateButtonActive) {
				translate_button.classList.add('header-button-active');
				if (lang_has_native) {
					lang_switcher.classList.remove('hide_display');
				} else {
					translate_box.classList.remove('hide_display');
				}
				isTranslateButtonActive = true;
			}
			else {
				translate_button.classList.remove('header-button-active');
				if (lang_has_native) {
					lang_switcher.classList.add('hide_display');
				} else {
					translate_box.classList.add('hide_display');
				}
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

		initPageFunction(curTab);
		return false;

}
