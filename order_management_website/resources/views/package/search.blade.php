@extends('master')

@section('content')
<div class="col-12" id="packageSearchApp" v-cloak>
    <div class="row bgc-white p-15 mB-20 mx-0 base-box-shadow align-items-center">
        <div class="col-3 pl-md-0">
            <input type="text" class="form-control" placeholder="{{ __('order.placeholder.name')}}" v-model="filter.name">
        </div>
        <div class="col-3">
            <input type="phone" class="form-control" placeholder="{{ __('order.placeholder.phone')}}" v-model="filter.phone">
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-12 col-md-6 pr-md-1">
                    <datepicker
                        format="yyyy-MM-dd"
                        v-model="arrivedAtStartDate"
                        input-class="bg-white"
                        id="arrivedAtStartDate"
                        calendar-button-icon="fa fa-calendar"
                        :calendar-button="true"
                        :clear-button="true"
                        :bootstrap-styling="true"
                        placeholder="{{ __('package.placeholder.arrived_at_start')}}"></datepicker>
                </div>
                <div class="col-12 col-md-6 pl-md-1">
                    <datepicker
                        format="yyyy-MM-dd"
                        v-model="arrivedAtEndDate"
                        input-class="bg-white"
                        id="arrivedAtEndDate"
                        calendar-button-icon="fa fa-calendar"
                        :calendar-button="true"
                        :clear-button="true"
                        :bootstrap-styling="true"
                        placeholder="{{ __('package.placeholder.arrived_at_end')}}"></datepicker>
                </div>
            </div>
        </div>
        <div class="col-atuo">
            <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                <input type="checkbox" id="filterShipped" class="peer" v-model="filter.shipped" true-value="1" false-value="0">
                <label for="filterShipped" class="peers peer-greed js-sb ai-c cur-p">
                    <span class="peer peer-greed">{{ __('package.filter.sent')}}</span>
                </label>
            </div>
        </div>
        <div class="col-auto ml-auto text-center pr-md-0">
            <button type="button" class="btn btn-primary" v-on:click="fetchSearchApi">
                    <i class="ti ti-search"></i>
                    {{ __('package.functional.search') }}
            </button>
        </div>
    </div>
    <div class="row bgc-white p-15 bd mB-20 mx-0" v-if="list.length">
        <div class="table-responsive">
            <table id="dataTable" class="table mb-0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 text-center">{{ __('package.section.sent_status') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2">{{ __('order.fields.name') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2">{{ __('order.fields.phone') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 text-center">{{ __('order.fields.married_date') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 text-center">{{ __('package.fields.arrived_at') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2">{{ __('package.section.content') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="pt-1 pb-0 text-center">{{ __('package.section.sent_status') }}</th>
                        <th class="pt-1 pb-0">{{ __('order.fields.name') }}</th>
                        <th class="pt-1 pb-0">{{ __('order.fields.phone') }}</th>
                        <th class="pt-1 pb-0 text-center">{{ __('order.fields.married_date') }}</th>
                        <th class="pt-1 pb-0 text-center">{{ __('package.fields.arrived_at') }}</th>
                        <th class="pt-1 pb-0">{{ __('package.section.content') }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    <template v-for="(package, packageIndex) in list" >
                        <tr :key="packageIndex" :class="{'bgc-green-50': package.checked}">
                            <td class="text-nowrap va-m" :rowspan="package.cases.length+1">
                                <div class="rounded-circle
                                    pos-r
                                    h-2r
                                    w-2r
                                    font-size-20
                                    mr-auto
                                    ml-auto"
                                    :class="{'bg-primary': package.sent_at, 'bg-secondary': !package.sent_at }">
                                    <i class="ti ti-truck text-white pos-a tl-50p centerXY"></i>
                                </div>
                            </td>
                            <td class="text-nowrap va-m" :rowspan="package.cases.length+1" v-text="package.package_name"></td>
                            <td class="text-nowrap va-m" :rowspan="package.cases.length+1" v-text="package.package_phone"></td>
                            <td class="text-nowrap va-m text-center" :rowspan="package.cases.length+1" v-text="package.married_date"></td>
                            <td class="text-nowrap va-m text-center" :rowspan="package.cases.length+1" v-text="package.arrived_at"></td>
                            <td class="p-0 bdwT-0 bdwB-0"></td>
                        </tr>
                        <tr :class="{'bgc-green-50': package.checked}" v-for="(caseItem, caseIndex) in package.cases" :key="`package-${packageIndex}-case-${caseIndex}`">
                            <td class="bdwB-0 va-m" :class="{'bdwT-0': packageIndex==0 || caseIndex>0}" v-text="caseItem.name"></td>
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
    const packageSearchApiUrl = '{{ route('package.search.api')}}';
@endsection