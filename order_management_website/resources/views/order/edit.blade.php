@extends('master')

@section('content')
<div class="row" id="orderEditApp" v-cloak>
    <form class="px-2 col-12 px-md-3" v-on:submit.prevent="onSubmit">
        <div class="bgc-white p-3 border mb-4">
            <h6 class="c-grey-900 mb-3  font-weight-bold">{{ __('order.section.info') }}</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderName">{{ __('order.fields.name')}}<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="orderName" placeholder="{{ __('order.placeholder.name')}}" v-model="order.name" :class="{'is-invalid': errors.name}" required>
                    <div class="invalid-feedback">
                        <div v-for="msg in errors.name">@{{msg}}</div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="orderPhone">{{ __('order.fields.phone')}}</label>
                    <input type="tel" class="form-control" id="orderPhone" placeholder="{{ __('order.placeholder.phone')}}" v-model="order.phone">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderNameBackup">{{ __('order.fields.name_backup')}}</label>
                    <input type="text" class="form-control" id="orderNameBackup" placeholder="{{ __('order.placeholder.name_backup')}}" v-model="order.name_backup">
                </div>
                <div class="form-group col-md-6">
                    <label for="orderPhoneBackup">{{ __('order.fields.phone_backup')}}</label>
                    <input type="tel" class="form-control" id="orderPhoneBackup" placeholder="{{ __('order.placeholder.phone_backup')}}" v-model="order.phone_backup">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderEmail">{{ __('order.fields.email')}}</label>
                    <input type="email" class="form-control" id="orderEmail" placeholder="{{ __('order.placeholder.email')}}" v-model="order.email" :class="{'is-invalid': errors.email}">
                    <div class="invalid-feedback">
                        <div v-for="msg in errors.email">@{{msg}}</div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="orderFinalPaid">{{ __('order.fields.final_paid')}}</label>
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input type="checkbox" id="orderFinalPaid" class="peer" v-model="order.final_paid" true-value="1" false-value="0">
                        <label for="orderFinalPaid" class="peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">{{ __('order.replace_string.paid.yes')}}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderDeposit">{{ __('order.fields.deposit')}}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-dollar"></i>
                            </span>
                        </div>
                        <input type="number" class="form-control" id="orderDeposit" placeholder="{{ __('order.placeholder.deposit')}}" v-model="order.deposit">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="orderExtraFee">{{ __('order.fields.extra_fee')}}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-dollar"></i>
                            </span>
                        </div>
                        <input type="number" class="form-control" id="orderExtraFee" placeholder="{{ __('order.placeholder.extra_fee')}}" v-model="order.extra_fee">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderEngagedDate">{{ __('order.fields.engaged_date')}}</label>
                    <datepicker
                        format="yyyy-MM-dd"
                        v-model="engagedDate"
                        input-class="bg-white"
                        id="orderEngagedDate"
                        calendar-button-icon="fa fa-calendar"
                        :calendar-button="true"
                        :clear-button="true"
                        :bootstrap-styling="true"
                        placeholder="{{ __('order.placeholder.engaged_date')}}"></datepicker>
                </div>
                <div class="form-group col-md-6">
                    <label for="orderMarriedDate">{{ __('order.fields.married_date')}}</label>
                    <datepicker
                        format="yyyy-MM-dd"
                        v-model="marriedDate"
                        input-class="bg-white"
                        id="orderMarriedDate"
                        calendar-button-icon="fa fa-calendar"
                        :calendar-button="true"
                        :clear-button="true"
                        :bootstrap-styling="true"
                        placeholder="{{ __('order.placeholder.married_date')}}"></datepicker>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderCardRequired">{{ __('order.fields.card_required')}}</label>
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input type="checkbox" id="orderCardRequired" class="peer" v-model="order.card_required" true-value="1" false-value="0">
                        <label for="orderCardRequired" class="peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">{{ __('order.replace_string.required.yes')}}</span>
                        </label>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="orderWoodRequired">{{ __('order.fields.wood_required')}}</label>
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input type="checkbox" id="orderWoodRequired" class="peer" v-model="order.wood_required" true-value="1" false-value="0">
                        <label for="orderWoodRequired" class="peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">{{ __('order.replace_string.required.yes')}}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="orderFb">{{ __('order.fields.fb')}}</label>
                    <input type="text" class="form-control" id="orderFb" placeholder="{{ __('order.placeholder.fb')}}" v-model="order.fb">
                </div>
            </div>

            <div class="form-group">
                <label for="orderRemark">{{ __('order.fields.remark')}}</label>
                <textarea name="Remark" class="form-control" rows="3" placeholder="{{ __('order.placeholder.remark')}}" v-model="order.remark"></textarea>
            </div>

            <div class="row mx-0">
                <div class="col-12 mb-4" v-for="(caseItem, caseIndex) in order.cases">
                    <div class="row">
                        <div class="col-12 bgc-grey-100 py-3 base-box-shadow">
                            <div class="row mB-15">
                                <div class="col-12">
                                    <h6 class="c-grey-900 d-inline-block font-weight-bold">{{ __('case.section.info') }}</h6>
                                    <button type="button" class="close ml-auto" v-on:click="delCase(caseIndex)">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <select class="form-control" v-model="caseItem.case_type_id" :class="{'is-invalid': hasCaseError(caseIndex, 'case_type_id')}">
                                        <option value="" hidden>{{ __('case.placeholder.case_type')}}*</option>
                                        <option v-for="option in orderDDL.cases" :value="option.id">
                                            @{{ option.name }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <div v-for="msg in getCaseError(caseIndex, 'case_type_id')">@{{msg}}</div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-dollar"></i>
                                            </span>
                                        </div>
                                        <input type="number" class="form-control" placeholder="{{ __('case.placeholder.price')}}" v-model.number="caseItem.price">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="number" class="form-control" placeholder="{{ __('case.placeholder.amount')}}" v-model.number="caseItem.amount">
                                </div>
                            </div>

                            <div class="row mB-15">
                                <h6 class="c-grey-900 mT-15 col-12 font-weight-bold">{{ __('case.section.content') }}</h6>
                            </div>
                            <div class="form-row mb-3 mb-md-0" :class="{'bg-light-primary': cookieIndex%2==0 }" v-for="(cookieItem, cookieIndex) in caseItem.cookies">
                                <div class="form-group col-md-4 col-lg-5 col-xl-6">
                                    <select class="form-control" v-model="cookieItem.cookie_id" :class="{'is-invalid': hasCookieError(caseIndex, cookieIndex, 'cookie_id')}" required>
                                        <option value="" hidden>{{ __('cookie.placeholder.cookie_type')}}*</option>
                                        <option v-for="option in orderDDL.cookies" :value="option.id">
                                            @{{ option.name }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <div v-for="msg in getCookieError(caseIndex, cookieIndex, 'cookie_id')">@{{msg}}</div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <select class="form-control" v-model="cookieItem.pack_id" :class="{'is-invalid': hasCookieError(caseIndex, cookieIndex, 'pack_id')}" required>
                                        <option value="" hidden>{{ __('cookie.placeholder.pack_type')}}*</option>
                                        <option v-for="option in orderDDL.packs" :value="option.id">
                                            @{{ option.name }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <div v-for="msg in getCookieError(caseIndex, cookieIndex, 'pack_id')">@{{msg}}</div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3 col-lg-2">
                                    <div class="input-group">
                                        <div class="input-group-prepend cursor-pointer">
                                            <button class="btn btn-primary" type="button" v-on:click="cookieItem.amount-=1" :disabled="cookieItem.amount==1"><span class="fa fa-minus"></span></button>
                                        </div>
                                        <input type="number" class="form-control text-center" min="0" v-model.number="cookieItem.amount" value="1">
                                        <div class="input-group-append cursor-pointer">
                                            <button class="btn btn-primary" type="button" v-on:click="cookieItem.amount+=1"><span class="fa fa-plus"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xl-1">
                                    <button v-on:click="delCookie(caseIndex, cookieIndex)" type="button" class="col btn btn-secondary text-nowrap">{{ __('cookie.functional.del') }}</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button v-on:click="addCookie(caseIndex)" type="button" class="w-100 btn btn-primary rounded-0">+ {{ __('cookie.functional.add') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-right">
                <button v-on:click="addCase" type="button" class="mb-3 btn btn-primary rounded-0 "><span class="font-size-30"><i class="ti ti-gift"></i></span><div>{{ __('case.functional.add') }}</div></button>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mb-3">{{ __('order.functional.save') }}</button>
        <a href="{{ route('order.show', ['id'=> $order->id])}}" class="btn btn-secondary float-right mb-3">{{ __('order.functional.cancel') }}</a>
    </form>
</div>
@endsection

@section('custom-js')
    const editOrder = @json($order);
    const orderUpdateUrl = '{{ route('order.update', ['id'=> $order->id])}}';
    const orderShowUrl = '{{ route('order.show', ['id'=> $order->id])}}';
    const defaultCookieOption = '{{ env('DEFAULT_COOKIE_OPTION', '') }}'
    const orderDDL = {
        cases: @json($caseTypes),
        cookies: @json($cookies),
        packs: @json($packs)
    };
@endsection