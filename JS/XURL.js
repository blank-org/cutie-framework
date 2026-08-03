function getURLid() {
	var loc = window.location.pathname;
	var prefix = getLanguagePrefix();
	if(prefix && loc.indexOf(prefix + '/') === 0)
		loc = loc.substring(prefix.length);
	else if(loc === prefix)
		loc = '/';
	if(loc == '/')
		return '';
	else
		return loc.substring(1);
}

function getLanguagePrefix() {
	var lang = document.documentElement.getAttribute('lang');
	return lang && lang !== 'en' ? '/' + lang : '';
}

function getHashID() {
	var hash = window.location.hash;
	if(hash.length == 0)
		return '';
	else
		return hash.substring(2);
}

function setXURL(node) {
	var arClassElement = getElementsByClassName(node, 'XURL');
	var n = arClassElement.length;
	for(i = 0; i < n; i++) {
		if(arClassElement[i].getAttribute('data-target') == 'menu')
			arClassElement[i].onclick = activateMenu;
		else
			arClassElement[i].onclick = loadCanvasI;
	}
}
