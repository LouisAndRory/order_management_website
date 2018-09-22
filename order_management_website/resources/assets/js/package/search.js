import Datepicker from 'vuejs-datepicker'

const PackageApp = new Vue({
    el: '#packageSearchApp',
    data: {
        list: {},
        filter: {},
        packageSearchApiUrl: packageSearchApiUrl,
        loading: false
    },
    computed: {
        arrivedAtStartDate: {
            get: function () {
                return _.get(this.filter, 'arrived_at_min', null)
            },
            set: function (newValue) {
                if(newValue){
                    return _.set(this.filter, 'arrived_at_min', moment(newValue).format('YYYY-MM-DD'))
                }
                this.$delete(this.filter, 'arrived_at_min')
            }
        },
        arrivedAtEndDate: {
            get: function () {
                return _.get(this.filter, 'arrived_at_max', null)
            },
            set: function (newValue) {
                if(newValue){
                    return _.set(this.filter, 'arrived_at_max', moment(newValue).format('YYYY-MM-DD'))
                }
                this.$delete(this.filter, 'arrived_at_max')
            }
        },
    },
    components: {
        Datepicker
    },
    methods: {
        fetchSearchApi: function() {
            this.list = []
            this.loading = true
            const that = this
            $.ajax({
                url: `${that.packageSearchApiUrl}`,
                data: this.filter,
                type: 'GET',
                dataType : 'json',
            }).done(function( response ) {
                that.list = response.packages
                that.loading = false
            })
        }
    }

})

export default PackageApp