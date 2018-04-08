var notification_intrvl;
var init_loading_intrvl;
var fade_loading_intrvl;

function beginLoading() {
	document.getElementById("wait_loader").classList.remove('hide');
}

function stopLoading() {
	document.getElementById("wait_loader").classList.add('hide');
}

function initLoading() {
	clearTimeout(init_loading_intrvl);
	init_loading_intrvl = setTimeout(function() {
		beginLoading();
	}, 3000);	
}

function endLoading() {
	clearTimeout(init_loading_intrvl);
	clearTimeout(fade_loading_intrvl);
	stopLoading();
}

function fadeLoading() {
	clearTimeout(fade_loading_intrvl);
	fade_loading_intrvl = setTimeout(function() {
		stopLoading();
	}, 3000);
}

function errorLoading() {
	document.getElementById("notification").classList.remove('hide');
	clearTimeout(notification_intrvl); // ensure single timer
	notification_intrvl = setInterval(function() {
		document.getElementById("notification").classList.add('hide');
	}, 3000);
}

function fbReload() {
	try{
		FB.XFBML.parse();
	}catch(ex) {};
}
