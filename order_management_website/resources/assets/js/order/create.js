import Datepicker from 'vuejs-datepicker'
import Thumbnail from '../components/thumbnail'


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
        order: {
            img_urls: []
        },
        orderDDL: orderDDL,
        errors: {},
        orderBaseUrl: orderBaseUrl
    },
    components: {
        Datepicker,
        Thumbnail
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
        getDuplicateError: function (caseIndex, cookieIndex) {
            return _.get(this.errors, `cases.${caseIndex}.cookies.${cookieIndex}.duplicate`)
        },
        getCookieError: function(caseIndex, cookieIndex, key) {
            return _.get(this.errors, `cases.${caseIndex}.cookies.${cookieIndex}.${key}`, [])
        },
        validDuplicate: function (cookieId, caseIndex, cookieIndex) {
            if (this.hasCookieError(caseIndex, cookieIndex, 'duplicate')) {
                this.$delete(this.errors.cases[caseIndex].cookies[cookieIndex], 'duplicate');
            }

            const result = _.filter(this.order.cases[caseIndex].cookies, { 'cookie_id': cookieId });
            if (result.length > 1) {
                const msg = _.get(window.notificationLang, 'duplicate_cookie_item.title');
                _.set(this.errors, `cases.${caseIndex}.cookies.${cookieIndex}.duplicate`, [msg]);

                return false
            }
            return true
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
        },
        uploadImages: function(e) {
            e.preventDefault()
            e.stopPropagation()

            const selectedFiles = event.target.files;

            this.errors = {}
            const that = this;
            const uploadFails = [];
            const fileInput = $('#image-file-upload')[0];

            for (let i = 0; i < selectedFiles.length; i++) {
                var fd = new FormData();
                fd.append('file', selectedFiles[i]);
                fd.append('type', 'orders');

                $.ajax({
                    url: fileUploadUrl,
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        const url = response.data.url;
                        that.order.img_urls.push(url);
                    },
                    error: function () {
                        uploadFails.push(selectedFiles[i].name)
                    }
                });
            }
            if (uploadFails.length) {
                Vue.swal({
                    allowOutsideClick: true,
                    type: 'error',
                    title: _.get(window.notificationLang, 'upload_image_fail.title'),
                    text: `${_.get(window.notificationLang, upload_image_fail.text)} ${uploadFails.join(', ')}`
                })
            }

            fileInput.value = null;
        },
        deleteImage: function (idx) {
            this.order.img_urls.splice(idx, 1);
        }
    }
})

export default OrderCreateApp
