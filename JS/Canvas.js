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

	curTab = target;
	var canvas_main = document.getElementById('canvas-main');
	var main_wrapper = document.getElementById('main-wrapper');

	main_wrapper.classList.add('hide_path_title_updated');
	canvas_main.classList.add('hide');

	var startTime = new Date().getTime();
	syncScrollReload.startTime = null;
	scrollTop();
	initLoading();
	if(target == 'root') {
		document.getElementById('path-container').classList.add('hide_scale');
		document.getElementById('title-container').classList.add('hide_scale');
	}
	else {
		document.getElementById('path-container').classList.remove('hide_scale');
		document.getElementById('title-container').classList.remove('hide_scale');
	}
	document.getElementById('path').classList.add('hide');
	document.getElementById('title').classList.add('hide');

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
				switch (xmlhttp.status) {
				case 200: {
					endLoading();

					var resp = JSON.parse(xmlhttp.responseText);
					document.title = resp.desc + ' - ' + PROJECT_TITLE;
					if(target == 'root')
						updatePathTitle('', '&nbsp;', resp.prevArticle, resp.nextArticle);
					else
						updatePathTitle(resp.path, title, resp.prevArticle, resp.nextArticle);
					syncScrollReload(startTime, resp, target);
				} break;
				case 404: {
					canvas_main.innerHTML = "Error: 404 - Resource not found!";
				} break;
				case 408:
				case 501:
				case 502: {
					canvas_main.innerHTML = 'Error!';
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
		document.getElementById('content').innerHTML = resp.content;
		if(typeof resp.languageSwitcher !== 'undefined') {
			var languageSwitcherResponse = document.createElement('div');
			languageSwitcherResponse.innerHTML = resp.languageSwitcher;
			var languageSwitcher = languageSwitcherResponse.querySelector('#language-switcher');
			document.getElementById('language-switcher').innerHTML = languageSwitcher ? languageSwitcher.innerHTML : '';
		}
		document.getElementById('canvas-main').classList.remove('hide');
		if(!URLid == '') {
			document.getElementById('main-wrapper').classList.remove('hide_path_title_updated');
		}
		var height = document.getElementById('canvas-main').scrollHeight;
		document.getElementById('nav-menu').style.maxHeight = height+'px';
		document.getElementById('canvas-main').style.maxHeight = null;
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

function updateArticleNavigationLink(linkId, article, direction) {
	var link = document.getElementById(linkId);
	if(article == null) {
		link.classList.add('article-title-nav-disabled');
		link.removeAttribute('href');
		link.removeAttribute('data-target');
		link.removeAttribute('data-title');
		link.removeAttribute('title');
		link.setAttribute('aria-label', 'No ' + direction.toLowerCase() + ' article');
		link.setAttribute('aria-disabled', 'true');
		link.setAttribute('tabindex', '-1');
		return;
	}

	link.classList.remove('article-title-nav-disabled');
	link.setAttribute('href', article.url);
	link.setAttribute('data-target', article.id);
	link.setAttribute('data-title', article.label);
	link.setAttribute('title', direction + ' article');
	link.setAttribute('aria-label', direction + ' article');
	link.removeAttribute('aria-disabled');
	link.setAttribute('tabindex', '0');
}

function updatePathTitle(path, title, prevArticle, nextArticle) {
	setTimeout(function() {
		document.getElementById('path').innerHTML = path;
		document.getElementById('title').innerHTML = title;
		updateArticleNavigationLink('article-prev', prevArticle, 'Previous');
		updateArticleNavigationLink('article-next', nextArticle, 'Next');
		document.getElementById('path').classList.remove('hide');
		document.getElementById('title').classList.remove('hide');
	}, 300);
}
