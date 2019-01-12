@extends('master')

@section('content')
<div class="col-12">
    <div class="row mb-3" id="orderShowApp">
        <div class="col-12 d-flex">
                <button type="button" class="btn btn-secondary" v-on:click="onClickDeleteOrder">{{ __('order.functional.delete')}}</button>
            <div class="btn-group ml-auto" role="group">
                <a href="{{ route('order.pdf', ['id'=> $order->id])}}" class="btn btn-primary ml-auto" >{{ __('order.functional.pdf')}}</a>
                <a href="{{ route('order.edit', ['id'=> $order->id])}}" class="btn btn-primary" >{{ __('order.functional.update')}}</a>
            </div>
        </div>
    </div>
    <div class="row bgc-white py-3 border mb-3 mx-0">
        <div class="col-12 mb-3">
            <div class="position-relative tag">
                <img src="{{ asset('/images/tag.png') }}" alt="">
                <span>{{ __('order.section.info') }}</span>
            </div>
        </div>
        <div class="col-12">
            <dl class="row">
                <dt class="col-sm-4 col-md-2">{{ __('order.fields.name')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->name  }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.phone')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->phone? $order->phone:'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.name_backup')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->name_backup? $order->name_backup:'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.phone_backup')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->phone_backup? $order->phone_backup:'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.email')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->email? $order->email:'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.extra_fee')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->extra_fee? $order->extra_fee.' '.__('order.unit.dollar'):'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.deposit')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->deposit? $order->deposit.' '.__('order.unit.dollar'):'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.final_paid')}}</dt>
                <dd class="col-sm-8 col-md-4">
                    {{ $order->total_fee.' '.__('order.unit.dollar') }}
                    <span class="ml-2 fa {{ $order->final_paid? 'fa-check-circle text-success':'fa-exclamation-circle text-danger'}}"></span>
                    <span class="{{ $order->final_paid? 'text-success':'text-danger'}}">{{ $order->final_paid? __('order.replace_string.paid.yes'):__('order.replace_string.paid.no') }}</span>
                </dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.engaged_date')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->engaged_date? $order->engaged_date->format('Y-m-d'):'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.married_date')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->married_date? $order->married_date->format('Y-m-d'):'-' }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.card_required')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->card_required? __('order.replace_string.required.yes'):__('order.replace_string.required.no') }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.wood_required')}}</dt>
                <dd class="col-sm-8 col-md-4">{{ $order->wood_required? __('order.replace_string.required.yes'):__('order.replace_string.required.no') }}</dd>

                <dt class="col-sm-4 col-md-2">{{ __('order.fields.remark')}}</dt>
                <dd class="col-sm-8 col-md-10 whs-p">{{ $order->remark? $order->remark:'-' }}</dd>
            </dl>
        </div>

        <div class="col-12 mb-3">
            <div class="position-relative tag">
                <img src="{{ asset('/images/tag.png') }}" alt="">
                <span>{{ __('order.section.case') }}</span>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                @foreach($order->cases as $case)
                    <div class="col-12 col-md-6">
                        <div class="bgc-grey-300 p-3 d-flex flex-column">
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <h4 class="card-title">{{$case->case_type_name}} X <span class="fa fa-archive mr-1"></span>{{$case->amount? $case->amount:0}}</h4>
                                <h5 class="card-subtitle mb-2 text-muted ml-md-auto">
                                    <span class="fa fa-dollar mr-1"></span>{{$case->price? $case->price:0 }}
                                </h5>
                            </div>
                            @if(!count($case->cookies))
                                <div class="mt-2 text-center text-secondary mt-auto mb-auto">
                                    <span class="font-size-60">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </span>
                                    <div class="card-subtitle">{{ __('order.notification.empty_case') }}</div>
                                </div>
                            @else
                                @foreach($case->cookies as $cookie)
                                    <div class="py-2 px-3 {{ ($loop->index%2 == 0)?'bgc-grey-100': ''}}">
                                        <span>{{$cookie->cookie_name}}</span>
                                        <span class="float-right">
                                        {{$cookie->pack_name}}
                                        <span class="mx-2">X</span>
                                        {{$cookie->amount?$cookie->amount:0}}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>



    <div class="row" id="packageApp" v-cloak>
        <package-modal
            modal-id="packageCreateModal"
            modal-title="{{ __('package.functional.add')}}"
            :show="packageModal.show.create"
            :case-list="packageDDL.cases"
            :langs="langs"
            :fetch-api="fetchCreateApi"
            :initial-package="packageModal.data.create"
            v-on:open="packageModal.show.create=true"
            v-on:close="cleanModalData('create')">
        </package-modal>

        <package-modal
            modal-id="packagEditeModal"
            modal-title="{{ __('package.functional.edit')}}"
            :show="packageModal.show.edit"
            :case-list="packageDDL.cases"
            :langs="langs"
            :fetch-api="fetchUpdateApi"
            :initial-package="packageModal.data.edit"
            v-on:open="packageModal.show.edit=true"
            v-on:close="cleanModalData('edit')">
        </package-modal>

        <div class="col-12 mb-3">
            <div class="position-relative tag">
                <img src="{{ asset('/images/tag.png') }}" alt="">
                <span>{{ __('order.section.package') }}</span>
            </div>
        </div>

        <div class="col-12 mb-3 text-right">
            <button class="btn py-1 font-size-12" v-on:click="filter='unsent'">{{ __('package.filter.unsent') }}</button>
            <button class="btn py-1 font-size-12" v-on:click="filter='sent'">{{ __('package.filter.sent') }}</button>
            <button class="btn py-1 font-size-12" v-on:click="packageModal.show.create=true">{{ __('package.functional.add') }}</button>
        </div>

        <div class="col-12 my-3 mb-md-4" v-for="(package, index) in filterPackage" :key="index">
            <div class="card h-100 package-card rounded-0">
                <div class="card-header d-flex flex-column flex-md-row text-center" :class="{'bgc-grey-300': !package.checked, 'success': package.checked}">
                @{{ convertDateStr(package.arrived_at) }} {{__('package.unit.arrived')}}
                    <div class="ml-md-auto d-flex flex-column flex-lg-row mt-2 mt-md-0">
                        <button class="btn
                            btn-outline-light
                            bg-white
                            text-dark
                            py-0
                            d-flex
                            align-items-center
                            radius
                            mr-auto
                            ml-auto
                            mr-lg-2
                            mb-2
                            mb-lg-0"
                            v-if="package.checked"
                            v-on:click="onClickUpdatePackageStatus(package, 'checked')">
                            <i class="fa fa-times"></i>
                            <span class="ml-2">{{ __('package.functional.cancel_check')}}</span>
                        </button>

                        <button class="btn
                            btn-outline-light
                            bg-white
                            text-dark
                            py-0
                            d-flex
                            align-items-center
                            radius
                            mr-auto
                            ml-auto
                            mr-lg-2
                            mb-2
                            mb-lg-0"
                            v-if="!package.checked"
                            v-on:click="onClickUpdatePackageStatus(package, 'checked')">
                            <i class="fa fa-check"></i>
                            <span class="ml-2">{{ __('package.functional.check')}}</span>
                        </button>

                        <button class="btn
                            btn-outline-light
                            bg-white
                            text-dark
                            py-0
                            d-flex
                            align-items-center
                            radius
                            mr-auto
                            ml-auto
                            mr-lg-2
                            mb-2
                            mb-lg-0"

                            v-on:click="onClickDeletePackage(package.id)">
                            <i class="fa fa-trash-o"></i>
                            <span class="ml-2">{{ __('package.functional.del')}}</span>
                        </button>

                        <button v-on:click="onClickEditPackage(package)"
                            class="btn btn-outline-light bg-white text-dark py-0 d-flex align-items-center radius m-auto">
                            <i class="fa fa-pencil"></i>
                            <span class="ml-2">{{ __('package.functional.edit')}}</span>
                        </button>

                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <dl class="row">
                        <dt class="col-md-2">{{ __('package.fields.name')}}：</dt>
                        <dd class="col-md-4">@{{ package.name }}</dd>

                        <dt class="col-md-2">{{ __('package.fields.phone')}}：</dt>
                        <dd class="col-md-4">@{{ package.phone }}</dd>

                        <dt class="col-md-2">{{ __('package.fields.arrived_at')}}：</dt>
                        <dd class="col-md-4">@{{ package.arrived_at }}</dd>

                        <dt class="col-md-2">{{ __('package.fields.sent_at')}}：</dt>
                        <dd class="col-md-4">
                            <span v-if="package.sent_at" class="text-primary">
                                <button type="button" class="btn btn-secondary py-0" v-on:click="onClickUpdatePackageStatus(package, 'sent_at')">
                                    @{{ package.sent_at }}
                                </button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary py-0" v-on:click="onClickUpdatePackageStatus(package, 'sent_at')">
                                    @{{ langs.functional.sent }}
                                </button>
                            </span>
                        </dd>

                        <dt class="col-md-2">{{ __('package.fields.address')}}：</dt>
                        <dd class="col-md-10">@{{ package.address }}</dd>

                        <dt class="col-md-2">{{ __('package.fields.remark')}}：</dt>
                        <dd class="col-md-10 whs-p">@{{ package.remark }}</dd>
                    </dl>
                    <div class="row border-top bdc-grey-400">
                        <div class="col-12 mt-2 text-center text-secondary mt-auto mb-auto" v-if="!package.cases.length">
                            <span class="fa fa-exclamation-triangle font-size-60"></span>
                            <div class="card-subtitle">{{ __('order.notification.empty_case') }}</div>
                        </div>
                        <div :class="`col-12 py-1 font-size-20 font-weigh-bold ${ (index%2 == 0)?'bgc-grey-200': ''}`" v-for="(caseItem, index) in package.cases">
                            <span>@{{caseItem.case_type_name}}</span>
                            <span class="float-right mr-2"><span>
                            @{{caseItem.amount}}
                            <span class="ml-2">{{__('case.unit')}}<span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('custom-js')
    const packageLangs = @json(__('package'));
    const packages = @json($order->packages);
    const packageBaseUrl = '{{ route('package.store')}}';
    const orderBaseUrl = '{{ route('order.index')}}';
    const orderId = '{{$order->id}}'
    const packageDDL = {
        cases: @json($order->cases)
    };
@endsection