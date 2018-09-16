import PackageModal from './components/package_modal'

const PackageApp = new Vue({
    el: '#packageApp',
    data: {
        orderId: orderId,
        packages: packages,
        langs: packageLangs,
        packageDDL: packageDDL,
        packageBaseUrl: packageBaseUrl,
        packageModal: {
            data: {},
            show: {
                create: false,
                edit: false
            }
        }
    },
    methods: {
        fetchCreateApi: function(data) {
            const that = this
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: `${that.packageBaseUrl}`,
                    data: {order_id: that.orderId, ...data},
                    type: 'POST',
                    dataType : 'json',
                }).done(function( response ) {
                    resolve(_.get(window.notificationLang, 'created_package.title'))
                 })
                 .fail(function( errorResponse) {
                    if(errorResponse.hasOwnProperty('status')) {
                        if(errorResponse.status == 422){
                            reject(errorResponse.responseJSON.errors)
                        }else{
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
        }
    },
    components: {
        'package-modal': PackageModal
    },
    created: function(){
        let tempPackage = localStorage.getItem('package')
        tempPackage = JSON.parse(tempPackage)

        if(tempPackage && tempPackage.type=='create' && tempPackage.orderId == this.orderId){
            this.$set(this.packageModal, 'data', tempPackage.data)
            this.$set(this.packageModal.show, 'create', true)
        }
    }

})

export default PackageApp