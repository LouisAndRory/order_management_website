import VueSweetalert2 from 'vue-sweetalert2';

window.Vue = require('vue');
window.Vue.use(VueSweetalert2);
window._ = require('lodash');
window.Popper = require('popper.js').default;
window.moment = require('moment');


const orderCreateApp = document.getElementById("orderCreateApp");
const orderEditApp = document.getElementById("orderEditApp");

if(orderCreateApp){
	require('./order/create');
}else if(orderEditApp) {
	require('./order/edit');
}



try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': token.content
		},
		statusCode: {
			419: function() {
				Vue.swal({
					type: 'error',
					title: _.get(window.notificationLang, '412_error.title'),
					text: _.get(window.notificationLang, '412_error.text'),
					confirmButtonText: _.get(window.notificationLang, '412_error.confirm_button'),
				}).then(() => {
					location.reload();
				})
			},
			422: function() {
				Vue.swal({
                    type: 'error',
					title: _.get(window.notificationLang, '422_error.title'),
					text: _.get(window.notificationLang, '422_error.text'),
                    showConfirmButton: false,
                    timer: 1000
                })
			},
			500: function() {
				Vue.swal({
					type: 'error',
					title: _.get(window.notificationLang, 'unexpect_error.title'),
					text: _.get(window.notificationLang, 'unexpect_error.text'),
					confirmButtonText: _.get(window.notificationLang, 'unexpect_error.confirm_button'),
				}).then(() => {
					location.reload();
				})
			}
		}
	});
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


jQuery(document).ready(function($) {
	$('#menuToggle').on('click', function(event) {
		$('body').toggleClass('open');
	});
});