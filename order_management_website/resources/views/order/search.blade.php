@extends('master')

@section('content')
<div class="col-12" id="orderSearchApp" style="min-height: 100vh" v-cloak>
    <div class="row bgc-white p-15 mB-20 mx-0 base-box-shadow align-items-center">
        <div class="col-3 pl-md-0">
            <input type="text" class="form-control" placeholder="{{ __('order.placeholder.name')}}" v-model="filter.name">
        </div>
        <div class="col-3">
            <input type="phone" class="form-control" placeholder="{{ __('order.placeholder.phone')}}" v-model="filter.phone">
        </div>
        <div class="col-4">
            <datepicker
                format="yyyy-MM-dd"
                v-model="marriedDate"
                input-class="bg-white"
                id="marriedDate"
                calendar-button-icon="fa fa-calendar"
                :calendar-button="true"
                :clear-button="true"
                :bootstrap-styling="true"
                placeholder="{{ __('order.placeholder.married_date')}}"></datepicker>
        </div>
        <div class="col-atuo">
            <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                <input type="checkbox" id="filterFinalPaid" class="peer" v-model="filter.final_paid" true-value="1" false-value="0">
                <label for="filterFinalPaid" class="peers peer-greed js-sb ai-c cur-p">
                    <span class="peer peer-greed">{{ __('order.fields.final_paid').__('order.replace_string.paid.yes')}}</span>
                </label>
            </div>
        </div>
        <div class="col-auto ml-auto text-center pr-md-0">
            <button type="button" class="btn btn-primary" v-on:click="fetchSearchApi">
                    <i class="ti ti-search"></i>
                    {{ __('order.functional.search') }}
            </button>
        </div>
    </div>
    <h4 class="mb-0">{{ __('navigation.order.search') }}</h4>
    <div class="row bgc-white p-15 bd mB-20 mx-0" v-if="list.length">
        <div class="table-responsive">
            <table id="dataTable" class="table mb-0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap text-center" style="width: 20px">{{ __('order.section.final_paid') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('order.fields.name') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('order.fields.phone') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-center">{{ __('order.fields.married_date') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="pt-1 pb-0 align-middle text-center">{{ __('order.section.final_paid') }}</th>
                        <th class="pt-1 pb-0 align-middle">{{ __('order.fields.name') }}</th>
                        <th class="pt-1 pb-0 align-middle">{{ __('order.fields.phone') }}</th>
                        <th class="pt-1 pb-0 align-middle text-center">{{ __('order.fields.married_date') }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    <template v-for="(order, orderIndex) in list" >
                        <tr :key="orderIndex">
                            <td class="text-nowrap va-m" :class="{'text-success': order.final_paid, 'text-danger': !order.final_paid}">
                                <span class="ml-2 fa" :class="{'fa-check-circle': order.final_paid, 'fa-exclamation-circle': !order.final_paid}"></span>
                                <span v-if="order.final_paid">{{__('order.replace_string.paid.yes') }}</span>
                                <span v-else>{{__('order.replace_string.paid.no')}}</span>
                            </td>
                            <td class="text-nowrap va-m">
                                <a :href="`${orderBaseUrl}/${order.id}`">@{{order.name}}</a>
                            </td>
                            <td class="text-nowrap va-m" v-text="order.phone"></td>
                            <td class="text-nowrap va-m text-center" v-text="order.married_date"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row p-15" v-else-if="loading">
        <div class="col-auto">
            <h5>Loading</h5>
        </div>
    </div>
    <div class="row p-15" v-else>
        <div class="col-auto">
            <h5>Empty</h5>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
    const orderSearchApiUrl = '{{ route('order.search.api')}}';
    const orderBaseUrl = '{{ route('order.index')}}';
@endsection