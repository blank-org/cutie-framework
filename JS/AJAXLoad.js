function beginLoading() {
	document.getElementById("wait_loader").classList.remove('hide');
}

function endLoading() {
	document.getElementById("wait_loader").classList.add('hide');
}

function fadeLoading() {
	setTimeout(function() {
		endLoading();
	}, 3000);
}

function errorLoading() {
	document.getElementById("notification").classList.remove('hide');
	clearTimeout(intrvl); // ensure single timer
	intrvl = setInterval(function() {
		document.getElementById("notification").classList.add('hide');
	},3000);
}

function fbReload() {
	try{
		FB.XFBML.parse();
	}catch(ex){}
}
