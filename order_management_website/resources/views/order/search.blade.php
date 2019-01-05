@extends('master')

@section('content')
<div class="col-12" id="orderSearchApp" style="min-height: 100vh" v-cloak>
    <form action="{{ route('order.search') }}" method="get" role="form">
        <div class="row bgc-white p-15 mB-20 mx-0 base-box-shadow align-items-center">
            <div class="col-3 pl-md-0">
                <input type="text" name="name" class="form-control" placeholder="{{ __('order.placeholder.name')}}">
            </div>
            <div class="col-3">
                <input type="phone" name="phone" class="form-control" placeholder="{{ __('order.placeholder.phone')}}">
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
                        name="married_date"
                        placeholder="{{ __('order.placeholder.married_date')}}"></datepicker>
            </div>
            <div class="col-auto">
                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                    <input type="checkbox" id="filterFinalPaid" class="peer" name="final_paid" v-model="filter.final_paid" true-value="1" false-value="0">
                    <label for="filterFinalPaid" class="peers peer-greed js-sb ai-c cur-p">
                        <span class="peer peer-greed">{{ __('order.fields.final_paid').__('order.replace_string.paid.yes')}}</span>
                    </label>
                </div>
            </div>
            <div class="col-auto ml-auto text-center pr-md-0">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search"></i>
                    {{ __('order.functional.search') }}
                </button>
            </div>
        </div>
        <h4 class="mb-0">{{ __('navigation.order.search') }}</h4>
        <div class="row bgc-white p-15 bd mB-20 mx-0">
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
                    @if(isset($orders))
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
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
@endsection

@section('custom-js')

@endsection