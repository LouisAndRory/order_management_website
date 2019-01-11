@extends('master')

@section('content')
<div class="col-12">
    <h5 class="mb-3 text-center text-md-left">{{ __('index.title') }}</h5>
    @if(count($packages))
    <div class="row bgc-white p-15 bd mB-20 mx-0">
        <div class="table-responsive">
            <table id="dataTable" class="table mb-0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('package.fields.name') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('package.fields.arrived_at') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('order.fields.remark') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-center" style="width: 20px">{{ __('package.section.sent_status') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap text-center" style="width: 20px">{{ __('order.section.final_paid') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('package.fields.name') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('package.fields.arrived_at') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle">{{ __('order.fields.remark') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap text-center" style="width: 20px">{{ __('package.section.sent_status') }}</th>
                        <th class="bdwT-0 bdwB-1 pt-0 pb-2 align-middle text-nowrap text-center" style="width: 20px">{{ __('order.section.final_paid') }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($packages as $item)
                        <tr>
                            <td class="text-nowrap va-m">
                                <a href="{{ route('order.show', ['id' => $item->order_id]) }}">{{$item->package_name}}</a>
                            </td>
                            <td class="text-nowrap va-m">{{ date('Y-m-d', strtotime($item->arrived_at))}}</td>
                            <td class="text-nowrap va-m">{{$item->remark}}</td>
                            <td>
                                <div class="rounded-circle
                                pos-r
                                h-2r
                                w-2r
                                font-size-20
                                mr-auto
                                ml-auto
                                {{$item->sent_at? 'bg-primary' : 'bg-secondary'}}">
                                    <i class="ti ti-truck text-white pos-a tl-50p centerXY"></i>
                                </div>
                            </td>
                            <td class="text-nowrap va-m {{ $item->final_paid ? 'text-success':'text-danger'}}">
                                @if($item->final_paid)
                                    <span class="ml-2 fa fa-check-circle"></span>
                                    <span>{{__('order.replace_string.paid.yes') }}</span>
                                @else
                                    <span class="ml-2 fa fa-exclamation-circle"></span>
                                    <span v-else>{{__('order.replace_string.paid.no')}}</span>

                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
        @include('layouts.empty')
    @endif
</div>
@endsection