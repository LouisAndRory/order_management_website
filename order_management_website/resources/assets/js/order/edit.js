import Datepicker from 'vuejs-datepicker'


const defaultCase = {
    case_type_id: '',
    cookies: []
};
const defaultCookie = {
    cookie_id: '',
    pack_id: '',
    amount: 1
}

const OrderEditApp = new Vue({
    el: '#orderEditApp',
    data: {
        order: editOrder,
        orderDDL: orderDDL,
        errors: {},
        orderShowUrl: orderShowUrl,
        orderUpdateUrl: orderUpdateUrl
    },
    components: {
        Datepicker
    },
    computed: {
        engagedDate: {
            get: function () {
                return _.get(this.order, 'engaged_date', null)
            },
            set: function (newValue) {
                if(newValue){
                    return _.set(this.order, 'engaged_date', moment(newValue).format('YYYY-MM-DD'))
                }
                _.set(this.order, 'engaged_date', null)
            }
        },
        marriedDate: {
            get: function () {
                return _.get(this.order, 'married_date', null)
            },
            set: function (newValue) {
                if(newValue){
                    return _.set(this.order, 'married_date', moment(newValue).format('YYYY-MM-DD'))
                }
                _.set(this.order, 'married_date', null)
            }
        }
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
        hasCaseError: function(caseIndex, key) {
            return _.has(this.errors, `cases.${caseIndex}.${key}`)
        },
        getCaseError: function(caseIndex, key) {
            return _.get(this.errors, `cases.${caseIndex}.${key}`, [])
        },
        hasCookieError: function(caseIndex, cookieIndex, key) {
            return _.has(this.errors, `cases.${caseIndex}.cookies.${cookieIndex}.${key}`)
        },
        getCookieError: function(caseIndex, cookieIndex, key) {
            return _.get(this.errors, `cases.${caseIndex}.cookies.${cookieIndex}.${key}`, [])
        },
        onSubmit: function() {
            localStorage.removeItem('orderEdit')
            this.errors = {}
            const that = this

            $.ajax({
                url: orderUpdateUrl,
                data: this.order,
                type: 'PUT',
                dataType : 'json',
            }).done(function( response ) {
                Vue.swal({
                    type: 'success',
                    title: _.get(window.notificationLang, 'edited_order.title'),
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    window.location.href = that.orderShowUrl
                })
             })
             .fail(function( xhr) {
               if(xhr.hasOwnProperty('status')) {
                   if(xhr.status == 422){
                        that.errors = xhr.responseJSON.errors
                   }else{
                        localStorage.setItem('orderEdit', JSON.stringify(that.order))
                   }

               }
             })
        }
    },
    created: function() {
        const tempOrder = localStorage.getItem('orderEdit')
        if(tempOrder){
            const parseTempOrderJSON = JSON.parse(tempOrder)
            if(parseTempOrderJSON.id == this.order.id) {
                _.set(this.order, parseTempOrderJSON)
            }
        }
    }
})

export default OrderEditApp