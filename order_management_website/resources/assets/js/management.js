import ManagementModal from './components/management_modal'

const ManagementApp = new Vue({
    el: '#managementApp',
    data: {
        list: list,
        baseUrl: baseUrl,
        langs: langs,
        category: category,
        managementModal: {
            data: {
                create: {},
                edit: {}
            },
            show: {
                create: false,
                edit: false
            }
        }
    },
    components: {
        'management-modal': ManagementModal
    },
    methods: {
        fetchCreateApi: function(data) {
            const that = this
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: `${that.baseUrl}`,
                    data: data,
                    type: 'POST',
                    dataType : 'json',
                }).done(function( response ) {
                    resolve(_.get(window.notificationLang, 'created_item.title'))
                 })
                 .fail(function( errorResponse) {
                    if(errorResponse.hasOwnProperty('status')) {
                        if(errorResponse.status == 422){
                            reject(errorResponse.responseJSON.errors)
                        }
                    }
                })
            })
        },
        fetchUpdateApi: function(data, needKeep=true) {
            const that = this
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: `${that.baseUrl}/${data.id}`,
                    data: data,
                    type: 'PUT',
                    dataType : 'json',
                }).done(function( response ) {
                    that.packages = response.packages
                    resolve(_.get(window.notificationLang, 'edited_item.title'))
                 })
                 .fail(function( errorResponse) {
                    if(errorResponse.hasOwnProperty('status')) {
                        if(errorResponse.status == 422){
                            reject(errorResponse.responseJSON.errors)
                        }else if(errorResponse.status == 400){
                            reject(errorResponse)
                        }
                        else{
                            if(!needKeep) { return }
                        }
                    }
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
            this.$set(this.managementModal.data, 'edit', data)
            this.$set(this.managementModal.show, 'edit', true)
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
        cleanModalData: function(type) {
            this.$set(this.managementModal.data, type, {})
            this.$set(this.managementModal.show, type, false)
        },
        itemEnabledHandler: function(item, index) {
            this.fetchUpdateApi({id: item.id, enabled: item.enabled}, false).then(()=>{
                Vue.swal({
                    type: 'success',
                    title: _.get(window.notificationLang, 'updated_item_status.title'),
                    showConfirmButton: false,
                    timer: 1000
                })
            }).catch((error) => {
                this.$set(this.list[index], 'enabled',  Number(item.enabled)? 0:1)
                Vue.swal({
                    type: 'error',
					title: _.get(window.notificationLang, 'updat_item_status_error.title'),
                    showConfirmButton: false,
                    timer: 1000
                })
            })
        }
    }
})

export default ManagementApp