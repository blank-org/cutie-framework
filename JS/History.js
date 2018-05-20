window.onpopstate = function(e) {
	if(!!e.state)
		if(e.state.id == 'menu')
			activateMenuFn();
		else {
			loadCanvas(e.state.id, e.state.title == null? '' : e.state.title);
		}
}

function recordState(tab, title) {
	var path;
	if(tab != 'root')
		path = tab;
	else
		path = '';
	window.history.pushState({'id':tab, 'title':title}, '', '/'+path);
}

function replaceState(tab, title) {
	var path;
	if(tab != 'root')
		path = tab;
	else
		path = '';
	window.history.replaceState({'id':tab, 'title':title}, '', '/'+path);
}
