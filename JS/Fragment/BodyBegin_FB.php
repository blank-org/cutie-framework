<div id="fb-root"></div>
<script>
	window.fbAsyncInit = function() {
		FB.init({
			appId            : '<?php echo $config['fb_appId'] ?>',
			autoLogAppEvents : true,
			xfbml            : true,
			version          : 'v<?php echo $config['fb_version'] ?>'
		});
	};
	(function(d, s, id) {
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "https://connect.facebook.net/en_US/sdk.js";
		 fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
