import PackageModal from '../components/package_modal'

const PackageApp = new Vue({
	el: '#packageApp',
	data: {
		orderId: orderId,
		packages: packages,
		langs: packageLangs,
		packageDDL: packageDDL,
		packageBaseUrl: packageBaseUrl,
		packageModal: {
			data: {
				create: {},
				edit: {}
			},
			show: {
				create: false,
				edit: false
			}
		},
		filter: 'unsent'
	},
	computed: {
		filterPackage: function() {
			const that = this
			return _.filter(this.packages, item => {
				let result = true
				if (that.filter == 'sent') {
					result = item.sent_at ? true : false
				} else if (that.filter == 'unsent') {
					result = !item.sent_at ? true : false
				}

				if (result) return item
			})
		}
	},
	methods: {
		fetchCreateApi: function(data) {
			const that = this
			return new Promise(function(resolve, reject) {
				$.ajax({
					url: `${that.packageBaseUrl}`,
					data: JSON.stringify({ order_id: that.orderId, ...data }),
					type: 'POST',
					contentType: 'application/json'
				})
					.done(function(response) {
						resolve(_.get(window.notificationLang, 'created_package.title'))
					})
					.fail(function(errorResponse) {
						if (errorResponse.hasOwnProperty('status')) {
							if (errorResponse.status == 422) {
								reject(errorResponse.responseJSON.errors)
							} else {
								localStorage.setItem(
									'package',
									JSON.stringify({
										type: 'create',
										orderId: that.orderId,
										data
									})
								)
							}
						}
					})
			})
		},
		fetchUpdateApi: function(data) {
			const that = this
			return new Promise(function(resolve, reject) {
				$.ajax({
					url: `${that.packageBaseUrl}/${data.id}`,
					data: JSON.stringify(data),
					type: 'PUT',
					contentType: 'application/json'
				})
					.done(function(response) {
						that.packages = response.packages
						resolve(_.get(window.notificationLang, 'edited_package.title'))
					})
					.fail(function(errorResponse) {
						if (errorResponse.hasOwnProperty('status')) {
							if (errorResponse.status == 422) {
								reject(errorResponse.responseJSON.errors)
							} else {
								localStorage.setItem(
									'package',
									JSON.stringify({
										type: 'edit',
										orderId: that.orderId,
										data
									})
								)
							}
						}
						reject(errorResponse)
					})
			})
		},
		fetchDeleteApi: function(id) {
			const that = this
			return new Promise(function(resolve, reject) {
				$.ajax({
					url: `${that.packageBaseUrl}/${id}`,
					type: 'DELETE',
					dataType: 'json'
				})
					.done(function(response) {
						resolve(response)
					})
					.fail(function(errorResponse) {
						reject(errorResponse)
					})
			})
		},
		onClickEditPackage: function(data) {
			this.$set(this.packageModal.data, 'edit', data)
			this.$set(this.packageModal.show, 'edit', true)
		},
		onClickDeletePackage: function(id) {
			this.$swal({
				title: _.get(window.notificationLang, 'del_package.title'),
				text: _.get(window.notificationLang, 'del_package.text'),
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: _.get(window.notificationLang, 'del_package.confirm_button'),
				cancelButtonText: _.get(window.notificationLang, 'del_package.cancel_button')
			}).then(result => {
				if (result.value) {
					this.fetchDeleteApi(id).then(() => {
						Vue.swal({
							type: 'success',
							title: _.get(window.notificationLang, 'deleted_package.title'),
							showConfirmButton: false,
							timer: 1000
						}).then(() => {
							location.reload()
						})
					})
				}
			})
		},
		onClickUpdatePackageStatus: function(packageData, key) {
			const that = this
			let postData = {
				id: packageData.id
			}
			let value = null
			let notificationTitle = ''
			if (key == 'checked') {
				notificationTitle = _.get(window.notificationLang, 'update_package_check_status.title')
				value = Number(!packageData.checked)
			} else if (key == 'sent_at') {
				notificationTitle = _.get(window.notificationLang, 'update_package_sent_status.title')
				value = packageData.sent_at ? null : moment().format('YYYY-MM-DD')
			}
			postData[key] = value

			this.fetchUpdateApi(postData)
				.then(() => {
					Vue.swal({
						type: 'success',
						title: notificationTitle,
						showConfirmButton: false,
						timer: 1000
					})
				})
				.catch(error => {
					console.log(error)
				})
		},
		cleanModalData: function(type) {
			this.$set(this.packageModal.data, type, {})
			this.$set(this.packageModal.show, type, false)
		},
		convertDateStr: function(date) {
			const dateFormate = 'YYYY-MM-DD'
			if (!moment(date, dateFormate).isValid()) return '-'

			const targetDate = moment(date, dateFormate)
			return targetDate.format(`${dateFormate} (dddd)`)
		},
		showSentBtn: function (data) {
			const target = moment(data)
			const today = moment()
			return today.diff(target, 'day') >= 0
		}
	},
	components: {
		'package-modal': PackageModal
	},
	created: function() {
		let tempPackage = localStorage.getItem('package')
		tempPackage = JSON.parse(tempPackage)

		if (tempPackage && tempPackage.orderId == this.orderId) {
			if (tempPackage.type == 'edit' && !_.find(this.packages, { id: tempPackage.data.id })) {
				return
			}
			this.$set(this.packageModal.data, tempPackage.type, tempPackage.data)
			this.$set(this.packageModal.show, tempPackage.type, true)
		}
	}
})

export default PackageApp
