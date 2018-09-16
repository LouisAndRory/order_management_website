<template>
    <!-- Modal -->
    <div class="modal fade" :ref="modalId" :id="modalId" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-text="modalTitle"></h5>
                <button type="button" class="close" v-on:click="closeModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form v-on:submit.prevent="onSubmit">
                    <div class="row mb-3">
                        <div class="col text-secondary font-weight-bold title-line">
                            {{ langs.section.info }}
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label :for="`${modalId}-name`">{{ langs.fields.name }}<span class="text-danger">*</span></label>
                            <input
                                v-model="package.name"
                                type="text"
                                class="form-control"
                                :id="`${modalId}-name`"
                                :placeholder="langs.placeholder.name"
                                :class="{'is-invalid': errors.name}" required>
                            <div class="invalid-feedback">
                                <div v-for="(msg, index) in errors.name" :key="index">{{msg}}</div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label :for="`${modalId}-phone`">{{ langs.fields.phone }}</label>
                            <input
                                v-model="package.phone"
                                type="tel"
                                class="form-control"
                                :id="`${modalId}-phone`"
                                :placeholder="langs.placeholder.phone"
                                :class="{'is-invalid': errors.phone}">
                            <div class="invalid-feedback">
                                <div v-for="(msg, index) in errors.phone" :key="index">{{msg}}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label :for="`${modalId}-arrived-at`">{{ langs.fields.arrived_at }}<span class="text-danger">*</span></label>
                            <datepicker
                                format="yyyy-MM-dd"
                                v-model="arrivedAt"
                                :input-class="`bg-white ${errors.arrived_at? 'is-invalid':''}`"
                                :id="`${modalId}-arrived-at`"
                                calendar-button-icon="fa fa-calendar"
                                :calendar-button="true"
                                :clear-button="true"
                                :bootstrap-styling="true"
                                :placeholder="langs.fields.arrived_at"></datepicker>
                            <div class="invalid-feedback" :class="{'d-block': errors.arrived_at}">
                                <div v-for="(msg, index) in errors.arrived_at" :key="index">{{msg}}</div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label :for="`${modalId}-address`">{{ langs.fields.address }}<span class="text-danger">*</span></label>
                            <input
                                v-model="package.address"
                                type="tel"
                                class="form-control"
                                :id="`${modalId}-address`"
                                :placeholder="langs.placeholder.address"
                                :class="{'is-invalid': errors.address}">
                            <div class="invalid-feedback">
                                <div v-for="(msg, index) in errors.address" :key="index">{{msg}}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label :for="`${modalId}-remark`">{{ langs.fields.remark }}</label>
                        <textarea :id="`${modalId}-remark`"
                            class="form-control"
                            rows="3"
                            :placeholder="langs.placeholder.remark"
                            v-model="package.remark"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col text-secondary font-weight-bold title-line">
                            {{ langs.section.content }}
                        </div>
                    </div>

                    <div class="form-row py-2" :class="{'bg-light-primary': caseIndex%2==0 }" v-for="(caseItem, caseIndex) in package.cases" :key="caseIndex">
                        <div class="col-md-6">
                            <select class="form-control" v-model="caseItem.case_id" :class="{'is-invalid': hasCaseError(caseIndex, 'case_id')}" required>
                                <option value="" hidden>{{langs.placeholder.cases}}*</option>
                                <option v-for="(option, optionIndex) in caseList" :key="optionIndex" :value="option.id">
                                    {{ option.case_type_name }}
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                <div v-for="(msg, msgIndex) in getCaseError(caseIndex, 'case_id')" :key="msgIndex">{{msg}}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend cursor-pointer">
                                    <button class="btn btn-secondary" type="button" v-on:click="caseItem.amount+=1"><span class="fa fa-plus"></span></button>
                                </div>
                                <input type="number" class="form-control text-center" min="0" v-model.number="caseItem.amount" value="1">
                                <div class="input-group-append cursor-pointer">
                                    <button class="btn btn-secondary" type="button" v-on:click="caseItem.amount-=1" :disabled="caseItem.amount==1"><span class="fa fa-minus"></span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button v-on:click="delCase(caseIndex)" type="button" class="col btn btn-secondary">{{ langs.functional.del_content }}</button>
                        </div>
                    </div>
                    <div class="form-row bg-primary">
                        <div class="col-12">
                            <button v-on:click="addCase()" type="button" class="col btn btn-primary rounded-0">+ {{ langs.functional.add_content }}</button>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" v-on:click="closeModal">{{langs.functional.cancel}}</button>
                <button type="button" class="btn btn-primary" v-on:click="onSubmit">{{langs.functional.save}}</button>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'

const defaultCase = {
    case_id: '',
    amount: 1
}
export default {
    props: {
        show: {
            type: Boolean,
            default: false,
            require: true
        },
        modalId: {
            type: String,
            default: 'packageModal'
        },
        modalTitle: {
            type: String
        },
        langs: {
            type: Object,
            require: true
        },
        caseList: {
            type: Array
        },
        fetchApi: {
            type: Function,
            require: true
        },
        initialPackage: {
            type: Object,
            default: ()=>{
                return {}
            }
        }
    },
    data: function() {
        return {
            is_visible: false,
            package: {},
            errors: {}
        }
    },
    watch: {
        show: function(newVal, oldVal) {
            if (newVal === oldVal) {
                return
            }
            this.package = JSON.parse(JSON.stringify(this.initialPackage))
            this[newVal ? 'openModal' : 'closeModal']()
        }
    },
    computed: {
        arrivedAt: {
            get: function () {
                return _.get(this.package, 'arrived_at', null)
            },
            set: function (newValue) {
                if(newValue){
                    return _.set(this.package, 'arrived_at', moment(newValue).format('YYYY-MM-DD'))
                }
                _.set(this.package, 'arrived_at', null)
            }
        },
    },
    methods: {
        openModal: function() {
            if(this.is_visible) {
                return
            }

            this.is_visible = true
            this.$emit('open')

            $(this.$refs[this.modalId]).modal({
                backdrop: 'static',
                keyboard: false
            })
        },
        closeModal: function() {
            if(!this.is_visible) {
                return
            }

            this.is_visible = false
            this.$emit('close')
            $(`#${this.modalId}`).modal('hide')
            localStorage.removeItem('package')
            this.package = {}
            this.errors = {}
        },
        hasCaseError: function(caseIndex, key) {
            return _.has(this.errors, `cases.${caseIndex}.${key}`)
        },
        getCaseError: function(caseIndex, key) {
            return _.get(this.errors, `cases.${caseIndex}.${key}`, [])
        },
        addCase: function(caseIndex) {
            if(!this.package.cases) this.$set(this.package, 'cases', [])
            this.package.cases.push(JSON.parse(JSON.stringify(defaultCase)))
        },
        delCase: function(caseIndex) {
            this.$delete( this.package.cases, caseIndex )
        },
        onSubmit: function() {
            localStorage.removeItem('package')
            this.errors = {}

            this.fetchApi(this.package).then((response)=> {
                Vue.swal({
                    type: 'success',
                    title: response,
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    window.location.reload()
                })
            }).catch((errorResponse)=> {
                this.errors = errorResponse
            })
        }
    },
    mounted: function(){
        this.package = JSON.parse(JSON.stringify(this.initialPackage))
        if(this.show) {
            this.openModal()
        }
    },
    components: {
        Datepicker
    },
}
</script>
