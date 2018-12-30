import Datepicker from 'vuejs-datepicker'
import Checkbox from '../components/checkbox'

const PackageApp = new Vue({
	el: '#packageSearchApp',
	data: {
		list: {},
		filter: {},
		packageSearchApiUrl: packageSearchApiUrl,
		packageExcelApiUrl: packageExcelApiUrl,
		orderBaseUrl: orderBaseUrl,
		loading: false,
		selected: {}
	},
	computed: {
		arrivedAtStartDate: {
			get: function() {
				return _.get(this.filter, 'arrived_at_min', null)
			},
			set: function(newValue) {
				if (newValue) {
					return _.set(this.filter, 'arrived_at_min', moment(newValue).format('YYYY-MM-DD'))
				}
				this.$delete(this.filter, 'arrived_at_min')
			}
		},
		arrivedAtEndDate: {
			get: function() {
				return _.get(this.filter, 'arrived_at_max', null)
			},
			set: function(newValue) {
				if (newValue) {
					return _.set(this.filter, 'arrived_at_max', moment(newValue).format('YYYY-MM-DD'))
				}
				this.$delete(this.filter, 'arrived_at_max')
			}
		}
	},
	components: {
		Datepicker,
		Checkbox
	},
	methods: {
		fetchSearchApi: function() {
			this.list = []
			this.loading = true
			const that = this
			this.selected = {}
			$.ajax({
				url: `${that.packageSearchApiUrl}`,
				data: this.filter,
				type: 'GET',
				dataType: 'json'
			}).done(function(response) {
				that.list = response.packages
				that.loading = false
			})
		},
		handleCheckCase(isChecked, params) {
			if (isChecked) {
				this.selected[params.pakage_id] = this.selected[params.pakage_id]
					? [ ...this.selected[params.pakage_id], params.case_id ]
					: [ params.case_id ]
			} else {
				if (this.selected[params.pakage_id].length > 1) {
					const targertIndex = this.selected[params.pakage_id].indexOf(params.case_id)
					this.selected[params.pakage_id].splice(targertIndex, 1)
				} else {
					delete this.selected[params.pakage_id]
				}
			}
		},
		handleExportReport() {
			let selectedArray = []
			const that = this
			Object.keys(this.selected).forEach(function(pakage_id) {
				that.selected[pakage_id]
				selectedArray.push(that.selected[pakage_id])
			})

			$.ajax({
				url: `${this.packageExcelApiUrl}`,
				data: { data: selectedArray },
				type: 'POST',
				dataType: 'json'
			}).done(function(response) {})
		}
	}
})

export default PackageApp
