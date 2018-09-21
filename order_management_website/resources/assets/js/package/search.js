const PackageApp = new Vue({
    el: '#packageSearchApp',
    data: {
        list: {},
        filter: {},
        packageSearchApiUrl: packageSearchApiUrl
    },
    computed: {
    },
    methods: {
        fetchSearchApi: function() {
            const that = this
            $.ajax({
                url: `${that.packageSearchApiUrl}`,
                data: this.filter,
                type: 'GET',
                dataType : 'json',
            }).done(function( response ) {
                that.list = response.packages
            })
        }
    },
    created: function(){
        // this.fetchSearchApi()
    }

})

export default PackageApp