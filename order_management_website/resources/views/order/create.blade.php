@extends('master')

@section('content')
<form id="orderCreateApp" v-on:submit.prevent="onSubmit">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderName">{{ __('order.fields.name')}}<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="orderName" placeholder="{{ __('order.placeholder.name')}}" v-model="order.name" :class="{'is-invalid': errors.name}" required>
            <div class="invalid-feedback">
                <div v-for="msg in errors.name">@{{errors.name}}</div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="orderName">{{ __('order.fields.phone')}}</label>
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

    <div class="form-group">
        <label for="orderEmail">{{ __('order.fields.email')}}</label>
        <input type="email" class="form-control" id="orderEmail" placeholder="{{ __('order.placeholder.email')}}" v-model="order.email">
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderDeposit">{{ __('order.fields.deposit')}}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="fa fa-dollar input-group-text"></span>
                </div>
                <input type="number" class="form-control" id="orderDeposit" placeholder="{{ __('order.placeholder.deposit')}}" v-model="order.deposit">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="orderExtraFee">{{ __('order.fields.extra_fee')}}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="fa fa-dollar input-group-text"></span>
                </div>
                <input type="number" class="form-control" id="orderExtraFee" placeholder="{{ __('order.placeholder.extra_fee')}}" v-model="order.extra_fee">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderEngagedDate">{{ __('order.fields.engaged_date')}}</label>
            <div class="input-group">
                <div class="input-group-prepend" for="orderEngagedDate">
                    <span class="fa fa-calendar input-group-text"></span>
                </div>
                <datepicker
                    format="yyyy-MM-dd"
                    v-model="order.engaged_date"
                    class="form-control"
                    input-class="w-100 bg-white border-0"
                    id="orderEngagedDate"
                    placeholder="{{ __('order.placeholder.engaged_date')}}"></datepicker>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="orderMarriedDate">{{ __('order.fields.married_date')}}</label>
            <div class="input-group">
                <div class="input-group-prepend" for="orderMarriedDate">
                    <span class="fa fa-calendar input-group-text"></span>
                </div>
                <datepicker
                    format="yyyy-MM-dd"
                    v-model="order.married_date"
                    class="form-control"
                    input-class="w-100 bg-white border-0"
                    id="orderMarriedDate"
                    placeholder="{{ __('order.placeholder.married_date')}}"></datepicker>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderCardRequired">{{ __('order.fields.card_required')}}</label>
            <div class="material-switch mt-2">
                <input type="checkbox" id="orderCardRequired" class="d-none" v-model="order.card_required" true-value="1" false-value="0">
                <label for="orderCardRequired" class="bg-success"></label>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="orderWoodRequired">{{ __('order.fields.wood_required')}}</label>
            <div class="material-switch mt-2">
                <input type="checkbox" id="orderWoodRequired" class="d-none" v-model="order.wood_required" true-value="1" false-value="0">
                <label for="orderWoodRequired" class="bg-success"></label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="orderRemark">{{ __('order.fields.remark')}}</label>
        <textarea name="Remark" class="form-control" rows="3" placeholder="{{ __('order.placeholder.remark')}}" v-model="order.remark"></textarea>
    </div>

    <div class="row">
        <div class="col-12 col-md-12 mb-3" v-for="(caseItem, caseIndex) in order.cases">
            <div class="card p-3 position-relative">
                <button type="button" class="close position-absolute right-0" v-on:click="delCase(caseIndex)">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row mb-3">
                    <div class="col text-secondary font-weight-bold title-line">
                        {{ __('case.section.info') }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <select class="form-control" v-model="caseItem.case_type">
                            <option value="" hidden>{{ __('case.placeholder.case_type')}}</option>
                            <option v-for="option in orderDDL.cases" :value="option.id">
                                @{{ option.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="fa fa-dollar input-group-text"></span>
                            </div>
                            <input type="number" class="form-control" placeholder="{{ __('case.placeholder.price')}}" v-model.number="caseItem.price">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <input type="number" class="form-control" placeholder="{{ __('case.placeholder.amount')}}" v-model.number="caseItem.amount">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col text-secondary font-weight-bold title-line">
                        {{ __('case.section.content') }}
                    </div>
                </div>
                <div class="form-row py-2" :class="{'bg-light-primary': cookieIndex%2==0 }" v-for="(cookieItem, cookieIndex) in caseItem.cookies">
                    <div class="col-md-6">
                        <select class="form-control" v-model="cookieItem.cookie_id">
                            <option value="" hidden>{{ __('cookie.placeholder.cookie_type')}}</option>
                            <option v-for="option in orderDDL.cookies" :value="option.id">
                                @{{ option.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" v-model="cookieItem.pack_id">
                            <option value="" hidden>{{ __('cookie.placeholder.pack_type')}}</option>
                            <option v-for="option in orderDDL.packs" :value="option.id">
                                @{{ option.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <div class="input-group-prepend cursor-pointer">
                                <button class="btn btn-secondary" type="button" v-on:click="cookieItem.amount+=1"><span class="fa fa-plus"></span></button>
                            </div>
                            <input type="number" class="form-control text-center" min="0" v-model.number="cookieItem.amount" value="1">
                            <div class="input-group-append cursor-pointer">
                                <button class="btn btn-secondary" type="button" v-on:click="cookieItem.amount-=1" :disabled="cookieItem.amount==1"><span class="fa fa-minus"></span></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button v-on:click="delCookie(caseIndex, cookieIndex)" type="button" class="col btn btn-secondary">{{ __('cookie.functional.del') }}</button>
                    </div>
                </div>
                <div class="form-row bg-primary">
                    <div class="col-12">
                        <button v-on:click="addCookie(caseIndex)" type="button" class="col btn btn-primary rounded-0">+ {{ __('cookie.functional.add') }}</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 mb-3">
            <button v-on:click="addCase" type="button" class="btn btn-primary rounded-0"><span class="fa fa-gift font-size-50"></span><div>{{ __('case.functional.add') }}</div></button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary float-right">{{ __('order.functional.save') }}</button>
</form>
@endsection

@section('custom-js')
    const orderDDL = {
        cases: @json($caseTypes),
        cookies: @json($cookies),
        packs: @json($packs)
    }
@endsection

