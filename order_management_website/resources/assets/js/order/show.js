const OrderShowApp = new Vue({
    el: '#orderShowApp',
    data: {
        orderId: orderId,
        orderBaseUrl: orderBaseUrl
    },
    methods: {
        fetchDeleteApi: function() {
            const that = this
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: `${that.orderBaseUrl}/${that.orderId}`,
                    type: 'DELETE',
                    dataType : 'json',
                }).done(function( response ) {
                    resolve(response)
                 })
                 .fail(function( errorResponse) {
                    reject(errorResponse)
                })
            })
        },
        onClickDeleteOrder: function() {
            this.$swal({
                title: _.get(window.notificationLang, 'del_order.title'),
                text: _.get(window.notificationLang, 'del_order.text'),
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: _.get(window.notificationLang, 'del_order.confirm_button'),
                cancelButtonText: _.get(window.notificationLang, 'del_order.cancel_button')
            }).then((result) => {
                if (result.value) {
                    this.fetchDeleteApi().then(()=>{
                        Vue.swal({
                            type: 'success',
                            title: _.get(window.notificationLang, 'deleted_package.title'),
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            //location.reload();
                        })
                    })
                }
            })

        }
    }
})

export default OrderShowApp