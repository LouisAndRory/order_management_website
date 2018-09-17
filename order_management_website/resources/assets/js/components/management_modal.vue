<template>
    <!-- Modal -->
    <div class="modal fade" :ref="modalId" :id="modalId" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-text="modalTitle"></h5>
                <button type="button" class="close" v-on:click="closeModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form v-on:submit.prevent="onSubmit">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label :for="`${modalId}-name`">{{ langs.fields.name }}<span class="text-danger">*</span></label>
                            <input
                                v-model="management.name"
                                type="text"
                                class="form-control"
                                :id="`${modalId}-name`"
                                :placeholder="langs.placeholder.name"
                                :class="{'is-invalid': errors.name}" required>
                            <div class="invalid-feedback">
                                <div v-for="(msg, index) in errors.name" :key="index">{{msg}}</div>
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <label :for="`${modalId}-slug`">{{ langs.fields.slug }}</label>
                            <input
                                v-model="management.slug"
                                type="tel"
                                class="form-control"
                                :id="`${modalId}-slug`"
                                :placeholder="langs.placeholder.slug"
                                :class="{'is-invalid': errors.slug}">
                            <div class="invalid-feedback">
                                <div v-for="(msg, index) in errors.slug" :key="index">{{msg}}</div>
                            </div>
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
export default {
    props: {
        show: {
            type: Boolean,
            default: false,
            require: true
        },
        modalId: {
            type: String,
            default: 'managementModal'
        },
        modalTitle: {
            type: String
        },
        langs: {
            type: Object,
            require: true
        },
        fetchApi: {
            type: Function,
            require: true
        },
        initialData: {
            type: Object,
            default: ()=>{
                return {}
            }
        }
    },
    data: function() {
        return {
            is_visible: false,
            management: {},
            errors: {}
        }
    },
    watch: {
        show: function(newVal, oldVal) {
            if (newVal === oldVal) {
                return
            }
            this.management = JSON.parse(JSON.stringify(this.initialData))
            this[newVal ? 'openModal' : 'closeModal']()
        }
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
            localStorage.removeItem('management')
            this.management = {}
            this.errors = {}
        },
        onSubmit: function() {
            localStorage.removeItem('management')
            this.errors = {}

            this.fetchApi(this.management).then((response)=> {
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
        this.management = JSON.parse(JSON.stringify(this.initialData))
        if(this.show) {
            this.openModal()
        }
    },
}
</script>
