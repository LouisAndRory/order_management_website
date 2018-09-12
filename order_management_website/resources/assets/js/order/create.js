import Datepicker from 'vuejs-datepicker'


const defaultCase = {
    case_type: '',
    cookies: []
};
const defaultCookie = {
    cookie_id: '',
    pack_id: '',
    amount: 1
}

const OrderCreateApp = new Vue({
    el: '#orderCreateApp',
    data: {
        order: {},
        orderDDL: orderDDL,
        errors: {}
    },
    components: {
        Datepicker
    },
    methods: {
        addCase: function() {
            if(!this.order.cases) this.$set(this.order, 'cases', [])
            this.order.cases.push(JSON.parse(JSON.stringify(defaultCase)))
        },
        delCase: function(caseIndex) {
            this.$swal({
                title: _.get(window.notificationLang, 'del_case.title'),
                text: _.get(window.notificationLang, 'del_case.text'),
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: _.get(window.notificationLang, 'del_case.confirm_button'),
                cancelButtonText: _.get(window.notificationLang, 'del_case.cancel_button')
            }).then((result) => {
                if (result.value) {
                    this.$delete( this.order.cases, caseIndex )
                }
            })
        },
        addCookie: function(caseIndex) {
            this.order.cases[caseIndex].cookies.push(JSON.parse(JSON.stringify(defaultCookie)))
        },
        delCookie: function(caseIndex, cookieIndex) {
            this.$delete( this.order.cases[caseIndex].cookies, cookieIndex )
        },
        onSubmit: function() {
            localStorage.removeItem('orderCreate')
            this.errors = {}
            const that = this

            $.ajax({
                url: './',
                data: this.order,
                type: 'POST',
                dataType : 'json',
            }).done(function( response ) {
                Vue.swal({
                    type: 'success',
                    title: _.get(window.notificationLang, 'created_order.title'),
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    console.log('YES, go to show')
                })
             })
             .fail(function( xhr) {
               if(xhr.hasOwnProperty('status')) {
                   if(xhr.status == 422){
                        that.errors = xhr.responseJSON.errors
                   }else{
                        localStorage.setItem('orderCreate', JSON.stringify(that.order))
                   }

               }
             })
        }
    },
    created: function() {
        const tempOrder = localStorage.getItem('orderCreate')
        if(tempOrder){
            this.order = JSON.parse(tempOrder)
        }
    }
})

export default OrderCreateApp