var curRequestId = 0;

function loadCanvasI(m) {
	if(this.classList.contains('article-title-nav-disabled')) {
		signalDisabledArticleNavigation(this);
		return false;
	}
	loadCanvasH(this);
	return false;
}

function signalDisabledArticleNavigation(link) {
	link.classList.remove('article-title-nav-denied');
	void link.offsetWidth;
	link.classList.add('article-title-nav-denied');
	setTimeout(function() {
		link.classList.remove('article-title-nav-denied');
	}, 380);
}

function scrollToArticleNavigation() {
	var navigation = document.getElementById('nav-list');
	if(!navigation)
		return false;

	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	navigation.scrollIntoView({
		behavior: reduceMotion ? 'auto' : 'smooth',
		block: 'center'
	});

	navigation.classList.remove('nav-list-highlight');
	void navigation.offsetWidth;
	navigation.classList.add('nav-list-highlight');
	if(scrollToArticleNavigation.highlightTimeout)
		clearTimeout(scrollToArticleNavigation.highlightTimeout);
	scrollToArticleNavigation.highlightTimeout = setTimeout(function() {
		navigation.classList.remove('nav-list-highlight');
	}, 2200);
	return false;
}

function loadCanvasH(e) {
	var target = e.getAttribute('data-target');
	if(target == 'root')
		URLid = '';
	else
		URLid = target;
	recordState(target, e.getAttribute('data-title'));
	loadCanvas(target, e.getAttribute('data-title'));
	if(!(typeof (ga) === 'undefined')) {
		ga('set', 'page', '/'+URLid);
		ga('send', 'pageview');
	}
}

function loadCanvas(target, title) {
	if(typeof target !== 'string' || !target.length)
		return;

	curTab = target;
	var canvas_main = document.getElementById('canvas-main');
	var main_wrapper = document.getElementById('main-wrapper');
	var pathContainer = document.getElementById('path-container');
	var titleContainer = document.getElementById('title-container');
	var pathEl = document.getElementById('path');
	var titleEl = document.getElementById('title');

	if(main_wrapper)
		main_wrapper.classList.add('hide_path_title_updated');
	if(canvas_main)
		canvas_main.classList.add('hide');

	var startTime = new Date().getTime();
	syncScrollReload.startTime = null;
	scrollTop();
	initLoading();
	if(target == 'root') {
		if(pathContainer) pathContainer.classList.add('hide_scale');
		if(titleContainer) titleContainer.classList.add('hide_scale');
	}
	else {
		if(pathContainer) pathContainer.classList.remove('hide_scale');
		if(titleContainer) titleContainer.classList.remove('hide_scale');
	}
	if(pathEl) pathEl.classList.add('hide');
	if(titleEl) titleEl.classList.add('hide');

	var xmlhttp = new XMLHttpRequest();
	if(window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	}
	else { // IE6, IE5
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}
	xmlhttp.requestId = ++curRequestId;
	xmlhttp.onreadystatechange = function() {

		if (xmlhttp.readyState == 4 && xmlhttp.requestId == curRequestId) {
			if(target === gTarget) {
				var canvas_main = document.getElementById('canvas-main');
				var content = document.getElementById('content');
				switch (xmlhttp.status) {
				case 200: {
					endLoading();

					var resp = JSON.parse(xmlhttp.responseText);
					document.title = resp.desc + ' - ' + PROJECT_TITLE;
					// Use server label so a wrong link data-title cannot stick as the H1.
					var pageTitle = (typeof resp.label !== 'undefined') ? resp.label : title;
					if(target == 'root')
						updatePathTitle('', '&nbsp;', resp.prevArticle, resp.nextArticle);
					else {
						updatePathTitle(resp.path, pageTitle, resp.prevArticle, resp.nextArticle);
						if(pageTitle !== title)
							replaceState(target, pageTitle);
					}
					syncScrollReload(startTime, resp, target);
				} break;
				case 404: {
					endLoading();
					if(content)
						content.innerHTML = "Error: 404 - Resource not found!";
					else if(canvas_main)
						canvas_main.innerHTML = "Error: 404 - Resource not found!";
					if(canvas_main)
						canvas_main.classList.remove('hide');
				} break;
				case 408:
				case 501:
				case 502: {
					if(content)
						content.innerHTML = 'Error!';
					else if(canvas_main)
						canvas_main.innerHTML = 'Error!';
					if(canvas_main)
						canvas_main.classList.remove('hide');
					errorLoading();
				}
				}
			}
		}

	}

	gTarget = target;
	xmlhttp.open('GET', getLanguagePrefix()+'/'+target+'.json', true);
	xmlhttp.setRequestHeader('Content-Type', 'text/plain;charset=UTF-8');
	xmlhttp.send();

}

function scrollTop() {
	scrollActive = true;
	var y = document.documentElement.scrollTop;
	if(typeof y === 'undefined')
		y = 0;
	var dy = 100;
	var scrollInterval = setInterval(function() {
		window.scrollTo(0, y);
		if(y <= 0) {
			clearInterval(scrollInterval);
			scrollActive = false;
			syncScrollReload();
		}
		else
			y = y-dy;
	}, 10);
}

var scrollActive;
function syncScrollReload(startTime, resp, target) {
	if(typeof startTime != 'undefined') {
		syncScrollReload.startTime = startTime;
		syncScrollReload.resp = resp;
		syncScrollReload.target = target;
		activateMainFn();
	}
	if(typeof syncScrollReload.startTime != 'undefined' && syncScrollReload.startTime != null && !scrollActive)
		executeReload(syncScrollReload.startTime, syncScrollReload.resp, syncScrollReload.target);
}

function executeReload(startTime, resp, target) {
	if(typeof reloadTimeout != 'undefined')
		clearTimeout(reloadTimeout);
	reloadTimeout = setTimeout( function() {
		var content = document.getElementById('content');
		var canvas_main = document.getElementById('canvas-main');
		var languageSwitcherEl = document.getElementById('language-switcher');
		var main_wrapper = document.getElementById('main-wrapper');
		var nav_menu = document.getElementById('nav-menu');

		if(content)
			content.innerHTML = resp.content;
		if(typeof resp.languageSwitcher !== 'undefined' && languageSwitcherEl) {
			var languageSwitcherResponse = document.createElement('div');
			languageSwitcherResponse.innerHTML = resp.languageSwitcher;
			var languageSwitcher = languageSwitcherResponse.querySelector('#language-switcher');
			languageSwitcherEl.innerHTML = languageSwitcher ? languageSwitcher.innerHTML : '';
		}
		if(canvas_main)
			canvas_main.classList.remove('hide');
		if(!URLid == '' && main_wrapper) {
			main_wrapper.classList.remove('hide_path_title_updated');
		}
		if(canvas_main && nav_menu) {
			nav_menu.style.maxHeight = canvas_main.scrollHeight+'px';
			nav_menu.style.minHeight = canvas_main.scrollHeight+'px';
		}
		if(canvas_main)
			canvas_main.style.maxHeight = null;
		setXURL(document);
		if(resp.async == '1')
			initPageFunction(target);
		fbReload();
	}, getTimeOutDuration(new Date().getTime() - startTime) );
}

function getTimeOutDuration(elapsed) {
	timeout = 380 - elapsed;
	if(timeout < 0)
		return 0;
	else
		return timeout;
}

function getArticleNavigationLabel(kind) {
	var container = document.getElementById('title-container');
	if(!container)
		return '';
	return container.getAttribute('data-' + kind) || '';
}

function updateArticleNavigationLink(linkId, article, label, noneLabel) {
	var link = document.getElementById(linkId);
	if(!link)
		return;
	if(article == null) {
		link.classList.add('article-title-nav-disabled');
		link.removeAttribute('href');
		link.removeAttribute('data-target');
		link.removeAttribute('data-title');
		link.removeAttribute('title');
		link.setAttribute('aria-label', noneLabel);
		link.setAttribute('aria-disabled', 'true');
		link.setAttribute('tabindex', '-1');
		return;
	}

	link.classList.remove('article-title-nav-disabled');
	link.setAttribute('href', article.url);
	link.setAttribute('data-target', article.id);
	link.setAttribute('data-title', article.label);
	link.setAttribute('title', label);
	link.setAttribute('aria-label', label);
	link.removeAttribute('aria-disabled');
	link.setAttribute('tabindex', '0');
}

function updatePathTitle(path, title, prevArticle, nextArticle) {
	setTimeout(function() {
		var pathEl = document.getElementById('path');
		var titleEl = document.getElementById('title');
		if(pathEl)
			pathEl.innerHTML = path;
		if(titleEl)
			titleEl.innerHTML = title;
		updateArticleNavigationLink('article-prev', prevArticle, getArticleNavigationLabel('nav-prev'), getArticleNavigationLabel('nav-prev-none'));
		updateArticleNavigationLink('article-next', nextArticle, getArticleNavigationLabel('nav-next'), getArticleNavigationLabel('nav-next-none'));
		if(pathEl)
			pathEl.classList.remove('hide');
		if(titleEl)
			titleEl.classList.remove('hide');
	}, 300);
}
