var initPageFunction = function(path) {
	if(typeof path === 'string' && path.length) {
		var pageFunction = path.replace('/', '__');
		if (typeof window[pageFunction] === 'function')
			window[pageFunction]();
	}
}
