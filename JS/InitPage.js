var initPageFunction = function(path) {
	if(typeof path != 'undefined') {
		var pageFunction = path.replace('/', '__');
		if (typeof window[pageFunction] === 'function')
			window[pageFunction]();
	}
}
