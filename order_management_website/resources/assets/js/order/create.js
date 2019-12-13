import Datepicker from 'vuejs-datepicker'


const defaultCase = {
    case_type_id: '',
    cookies: []
};
const defaultCookie = {
    cookie_id: defaultCookieOption,
    pack_id: '',
    amount: 1
}

const OrderCreateApp = new Vue({
    el: '#orderCreateApp',
    data: {
        order: {},
        orderDDL: orderDDL,
        errors: {},
        orderBaseUrl: orderBaseUrl
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
            this.errors = {}
            const that = this

            $.ajax({
                url: that.orderBaseUrl,
                data: JSON.stringify(this.order),
                type: 'POST',
                contentType: 'application/json'
            }).done(function( response ) {
                Vue.swal({
                    type: 'success',
                    title: _.get(window.notificationLang, 'created_order.title'),
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    window.location.href = `${that.orderBaseUrl}/${response.id}`
                })
             })
             .fail(function( xhr) {
               if(xhr.hasOwnProperty('status')) {
                   if(xhr.status == 422){
                        that.errors = xhr.responseJSON.errors
                   }

               }
             })
        }
    }
})

export default OrderCreateApp