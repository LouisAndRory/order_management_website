import VueSweetalert2 from 'vue-sweetalert2'

window.Vue = require('vue')
window.Vue.use(VueSweetalert2)
window._ = require('lodash')
window.Popper = require('popper.js').default
window.moment = require('moment')

try {
	window.$ = window.jQuery = require('jquery')
	require('bootstrap')
	// require('./sidebar')
} catch (e) {}

const orderCreateApp = document.getElementById('orderCreateApp')
const orderEditApp = document.getElementById('orderEditApp')
const orderShowApp = document.getElementById('orderShowApp')
const managementApp = document.getElementById('managementApp')
const packageSearchApp = document.getElementById('packageSearchApp')
const orderSearchApp = document.getElementById('orderSearchApp')

if (orderCreateApp) {
	require('./order/create')
} else if (orderEditApp) {
	require('./order/edit')
} else if (orderShowApp) {
	require('./order/show')
	require('./package/package')
} else if (managementApp) {
	require('./management.js')
} else if (packageSearchApp) {
	require('./package/search')
} else if (orderSearchApp) {
	require('./order/search')
}

const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': token.content
		},
		statusCode: {
			419: function() {
				Vue.swal({
					allowOutsideClick: false,
					type: 'error',
					title: _.get(window.notificationLang, '412_error.title'),
					text: _.get(window.notificationLang, '412_error.text'),
					confirmButtonText: _.get(window.notificationLang, '412_error.confirm_button')
				}).then(() => {
					location.reload()
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
			401: function() {
				Vue.swal({
					allowOutsideClick: false,
					type: 'error',
					title: _.get(window.notificationLang, '401_error.title'),
					text: _.get(window.notificationLang, '401_error.text'),
					confirmButtonText: _.get(window.notificationLang, '401_error.confirm_button')
				}).then(() => {
					location.reload()
				})
			},
			500: function() {
				Vue.swal({
					allowOutsideClick: false,
					type: 'error',
					title: _.get(window.notificationLang, 'unexpect_error.title'),
					text: _.get(window.notificationLang, 'unexpect_error.text'),
					confirmButtonText: _.get(window.notificationLang, 'unexpect_error.confirm_button')
				}).then(() => {
					location.reload()
				})
			}
		}
	})
} else {
	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}
