const ManagementApp = new Vue({
    el: '#managementApp',
    data: {
        list: list,
        baseUrl: baseUrl
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
        },
        fetchUpdateApi: function(data) {
            const that = this
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: `${that.baseUrl}/${data.id}`,
                    data: data,
                    type: 'PUT',
                    dataType : 'json',
                }).done(function( response ) {
                    that.packages = response.packages
                    resolve(_.get(window.notificationLang, 'edited_package.title'))
                 })
                 .fail(function( errorResponse) {
                    if(errorResponse.hasOwnProperty('status')) {
                        if(errorResponse.status == 422){
                            reject(errorResponse.responseJSON.errors)
                        }else{
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
                    url: `${that.baseUrl}/${id}`,
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
        onClickEditItem: function(data) {

        },
        onClickDeleteItem: function(id) {
            this.$swal({
                title: _.get(window.notificationLang, 'del_item.title'),
                text: _.get(window.notificationLang, 'del_item.text'),
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: _.get(window.notificationLang, 'del_item.confirm_button'),
                cancelButtonText: _.get(window.notificationLang, 'del_item.cancel_button')
            }).then((result) => {
                if (result.value) {
                    this.fetchDeleteApi(id).then(()=>{
                        Vue.swal({
                            type: 'success',
                            title: _.get(window.notificationLang, 'deleted_item.title'),
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            location.reload();
                        })
                    }).catch((error)=> {
                        console.log(error)
                    })
                }
            })

        },
        onClickAddItem: function() {


        }
    }
})

export default ManagementApp