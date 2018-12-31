import Datepicker from 'vuejs-datepicker'

const OrderApp = new Vue({
	el: '#orderSearchApp',
	data: {
		list: {},
		filter: {},
		orderSearchApiUrl: orderSearchApiUrl,
		orderBaseUrl: orderBaseUrl,
		loading: false
	},
	computed: {
		marriedDate: {
			get: function() {
				return _.get(this.filter, 'married_date', null)
			},
			set: function(newValue) {
				if (newValue) {
					return _.set(this.filter, 'married_date', moment(newValue).format('YYYY-MM-DD'))
				}
				this.$delete(this.filter, 'married_date')
			}
		}
	},
	components: {
		Datepicker
	},
	methods: {
		fetchSearchApi: function() {
			this.list = []
			this.loading = true
			const that = this
			this.selected = {}
			$.ajax({
				url: `${that.orderSearchApiUrl}`,
				data: this.filter,
				type: 'GET',
				dataType: 'json'
			}).done(function(response) {
				that.list = response.orders
				that.loading = false
			})
		}
	}
})

export default OrderApp
