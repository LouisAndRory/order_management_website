@extends('master')

@section('content')
<div class="col-12" id="orderSearchApp" style="min-height: 100vh" v-cloak>
    <form action="{{ route('order.search') }}" method="get" role="form">
        <div class="row p-3 mb-3 mx-0 base-box-shadow align-items-center">
            <div class="col-12 col-md-6 col-lg-3 pr-md-0">
                <input type="text" name="name" class="form-control" placeholder="{{ __('order.placeholder.name')}}">
            </div>
            <div class="col-12 col-md-6 col-lg-3 pr-lg-0">
                <input type="phone" name="phone" class="form-control" placeholder="{{ __('order.placeholder.phone')}}">
            </div>
            <div class="col-12 col-lg-3 col-xl-4 pr-lg-0">
                <div class="input-group d-md-none">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    <input type="date" class="form-control" name="married_date" v-model="marriedDate">
                </div>
                <datepicker
                    class="d-none d-md-block"
                    format="yyyy-MM-dd"
                    v-model="marriedDate"
                    input-class="bg-white"
                    id="marriedDate"
                    calendar-button-icon="fa fa-calendar"
                    :calendar-button="true"
                    :clear-button="true"
                    :bootstrap-styling="true"
                    name="married_date"
                    placeholder="{{ __('order.placeholder.married_date')}}"></datepicker>
            </div>
            <div class="col-12 col-lg-auto col-xl-1 pr-lg-0">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                    <input type="checkbox" id="filterFinalPaid" class="peer d-none" name="final_paid" v-model="filter.final_paid" true-value="1" false-value="0">
                    <label for="filterFinalPaid" class="peers peer-greed js-sb ai-c cur-p">
                        <span class="peer peer-greed">{{ __('order.fields.final_paid').__('order.replace_string.paid.yes')}}</span>
                    </label>
                </div>
            </div>
            <div class="col-12 col-lg-auto col-xl-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-search d-lg-none d-xl-inline-block"></i>
                    {{ __('order.functional.search') }}
                </button>
            </div>
        </div>
        <h4 class="mb-0">{{ __('navigation.order.search') }}</h4>
        @if(isset($orders) && count($orders))
        <div class="row bg-white p-3 mt-3 border mb-5 mx-0">
            <div class="table-responsive">
                <table id="dataTable" class="table mb-0" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="border-top-0 pt-0 pb-2 align-middle text-nowrap text-center" style="width: 20px">{{ __('order.section.final_paid') }}</th>
                        <th class="border-top-0 pt-0 pb-2 align-middle text-nowrap">{{ __('order.fields.name') }}</th>
                        <th class="border-top-0 pt-0 pb-2 align-middle text-nowrap">{{ __('order.fields.phone') }}</th>
                        <th class="border-top-0 pt-0 pb-2 align-middle text-nowrap text-center">{{ __('order.fields.married_date') }}</th>
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
                        @foreach($orders as $index => $order)
                            <tr :key="{{ $index }}">
                                <td class="text-nowrap va-m {{ $order->final_paid ? 'text-success' : 'text-danger' }}">
                                    <span class="ml-2 fa {{ $order->final_paid ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></span>
                                    <span>{{ $order->final_paid ? __('order.replace_string.paid.yes') : __('order.replace_string.paid.no') }}</span>
                                </td>
                                <td class="text-nowrap va-m">
                                    <a href="{{ route('order.show', ['id' => $order->id]) }}">{{ $order->name }}</a>
                                </td>
                                <td class="text-nowrap va-m">{{ $order->phone }}</td>
                                <td class="text-nowrap va-m text-center">{{ $order->married_date ? $order->married_date->format('Y-m-d') : '-'  }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            @include('layouts.empty')
        @endif
    </form>
</div>
@endsection

@section('custom-js')

@endsection