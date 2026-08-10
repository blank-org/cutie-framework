window.onpopstate = function(e) {
	var state = resolveHistoryState(e.state);
	if(state.id == 'menu')
		activateMenuFn();
	else {
		// Same-tab hash restore (e.g. /#portfolio) is handled by root.js; avoid a redundant reload.
		if(typeof curTab !== 'undefined' && curTab === state.id)
			return;
		loadCanvas(state.id, state.title == null ? '' : state.title);
	}
}

function resolveHistoryState(state) {
	if(state && state.id)
		return state;
	var urlid = typeof getURLid === 'function' ? getURLid() : '';
	if(urlid == 'menu')
		return {'id': 'menu', 'title': ''};
	if(urlid)
		return {'id': urlid, 'title': ''};
	return {'id': 'root', 'title': ''};
}

function historyUrlForTab(tab) {
	var path;
	if(tab != 'root')
		path = '/'+tab;
	else
		path = '';
	return getLanguagePrefix()+path || '/';
}

function recordState(tab, title) {
	window.history.pushState({'id':tab, 'title':title}, '', historyUrlForTab(tab));
}

function replaceState(tab, title) {
	var url = historyUrlForTab(tab);
	// Keep in-page section hashes when staying on home (e.g. /#portfolio).
	if(tab == 'root' && window.location.hash && window.location.hash.charAt(1) !== '/') {
		var homePath = getLanguagePrefix() || '/';
		var path = (window.location.pathname || '/').replace(/\/+$/, '') || '/';
		if(path === homePath || path === '/')
			url = homePath + window.location.hash;
	}
	window.history.replaceState({'id':tab, 'title':title}, '', url);
}

// Update the URL (path/hash) without wiping the SPA history state used by popstate.
function replaceHistoryUrl(url) {
	var state = resolveHistoryState(window.history.state);
	window.history.replaceState(state, '', url);
}

// Ensure the current history entry has SPA state without changing the URL.
function ensureHistoryState(tab, title) {
	var state = window.history.state;
	if(state && state.id === tab)
		return;
	window.history.replaceState(
		{'id': tab, 'title': title == null ? '' : title},
		'',
		window.location.pathname + window.location.search + window.location.hash
	);
}
