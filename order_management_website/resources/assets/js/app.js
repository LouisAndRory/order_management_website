window._ = require('lodash');
window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

jQuery(document).ready(function($) {
	$('#menuToggle').on('click', function(event) {
		$('body').toggleClass('open');
	});
});