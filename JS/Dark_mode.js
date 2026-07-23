var isDarkMode = false;
var currentTheme = 'system';
var themeMediaQuery = null;

function normalizeTheme(theme) {
	if (theme === 'true') return 'dark';
	if (theme === 'false') return 'light';
	return theme === 'light' || theme === 'dark' || theme === 'system' ? theme : 'system';
}

function getSystemTheme() {
	return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function updateThemeControls(theme, resolvedTheme) {
	var button = document.querySelector('#darkmode-button');
	var options = document.querySelectorAll('.theme-option');
	var preferenceLabel = theme.charAt(0).toUpperCase() + theme.slice(1);
	var resolvedLabel = resolvedTheme.charAt(0).toUpperCase() + resolvedTheme.slice(1);
	var label = theme === 'system' ? preferenceLabel + ' (' + resolvedLabel + ')' : resolvedLabel;
	var i;

	document.documentElement.setAttribute('data-theme-preference', theme);
	document.documentElement.setAttribute('data-theme-resolved', resolvedTheme);
	if (button) {
		button.setAttribute('aria-label', 'Theme: ' + label);
		button.setAttribute('title', 'Theme: ' + label);
	}

	for (i = 0; i < options.length; i++) {
		var selected = options[i].getAttribute('data-theme') === theme;
		options[i].classList.toggle('theme-option-active', selected);
		options[i].setAttribute('aria-checked', selected ? 'true' : 'false');
	}
}

function applyTheme(theme, animate, persist) {
	theme = normalizeTheme(theme);
	var resolvedTheme = theme === 'system' ? getSystemTheme() : theme;
	var metaThemeColor = document.querySelector("meta[name='theme-color']");

	if (animate)
		document.documentElement.style.transition = 'background-color 0.3s, color 0.3s';

	if (resolvedTheme === 'dark')
		document.documentElement.classList.add('dark-mode');
	else
		document.documentElement.classList.remove('dark-mode');

	document.documentElement.style.colorScheme = resolvedTheme;
	if (metaThemeColor)
		metaThemeColor.setAttribute('content', resolvedTheme === 'dark' ? '#1a1a2e' : '#ffffff');

	currentTheme = theme;
	isDarkMode = resolvedTheme === 'dark';
	updateThemeControls(theme, resolvedTheme);

	if (persist !== false) {
		try {
			localStorage.setItem('cutie-dark-mode', theme);
		} catch (e) {}
	}
}

function setThemeMenuOpen(open) {
	var button = document.querySelector('#darkmode-button');
	var menu = document.querySelector('#theme-menu');
	if (!button || !menu) return;

	menu.hidden = !open;
	button.setAttribute('aria-expanded', open ? 'true' : 'false');
}

function initDarkMode() {
	var saved = null;
	var selector = document.querySelector('#theme-selector');
	var button = document.querySelector('#darkmode-button');
	var menu = document.querySelector('#theme-menu');
	var options = document.querySelectorAll('.theme-option');
	var i;

	try {
		saved = localStorage.getItem('cutie-dark-mode');
	} catch (e) {}
	applyTheme(normalizeTheme(saved), false, true);

	if (window.matchMedia) {
		themeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
		var handleSystemThemeChange = function() {
			if (currentTheme === 'system') applyTheme('system', true, false);
		};
		if (themeMediaQuery.addEventListener)
			themeMediaQuery.addEventListener('change', handleSystemThemeChange);
		else if (themeMediaQuery.addListener)
			themeMediaQuery.addListener(handleSystemThemeChange);
	}

	if (button && menu) {
		button.addEventListener('click', function() {
			setThemeMenuOpen(menu.hidden);
		});
		button.addEventListener('keydown', function(event) {
			if (event.key === 'ArrowDown') {
				event.preventDefault();
				setThemeMenuOpen(true);
				if (options.length) options[0].focus();
			}
		});
		menu.addEventListener('keydown', function(event) {
			if (event.key === 'Escape') {
				setThemeMenuOpen(false);
				button.focus();
			}
		});
	}

	for (i = 0; i < options.length; i++) {
		options[i].addEventListener('click', function() {
			applyTheme(this.getAttribute('data-theme'), true, true);
			setThemeMenuOpen(false);
			if (button) button.focus();
		});
	}

	document.addEventListener('click', function(event) {
		if (selector && !selector.contains(event.target)) setThemeMenuOpen(false);
	});
}

function enableDarkMode(animate) {
	applyTheme('dark', animate, true);
}

function disableDarkMode() {
	applyTheme('light', true, true);
}
