import Datepicker from 'vuejs-datepicker'

const OrderApp = new Vue({
	el: '#orderSearchApp',
	data: {
		list: {},
		filter: {},
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

	}
})

export default OrderApp
