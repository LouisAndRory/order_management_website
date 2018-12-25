@extends('master')

@section('content')
<div class="col-12">
    <div class="row mb-3" id="orderShowApp">
        <div class="btn-group col-12" role="group">
            <button type="button" class="btn btn-secondary" v-on:click="onClickDeleteOrder">{{ __('order.functional.delete')}}</button>
            <a href="{{ route('order.pdf', ['id'=> $order->id])}}" class="btn btn-primary ml-auto" >{{ __('order.functional.pdf')}}</a>
            <a href="{{ route('order.edit', ['id'=> $order->id])}}" class="btn btn-primary" >{{ __('order.functional.update')}}</a>
        </div>
    </div>
    <div class="row bgc-white pY-15 bd mB-20 mx-0">
        <h6 class="c-grey-900 mB-20 col-12">{{ __('order.section.info') }}</h6>
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

        <h6 class="c-grey-900 mB-20 col-12">{{ __('order.section.case') }}</h6>
        <div class="col-12">
            <div class="row">
                @foreach($order->cases as $case)
                    <div class="col-12 col-md-6 mB-30">
                        <div class="bgc-grey-300 p-15 d-flex flex-column">
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

        <div class="col-12 col-md-9">
            <h5 class="c-grey-900">{{ __('order.section.package') }}</h5>
            <div class="peers ai-c">
                <i class="ti ti-filter text-primary font-size-20 mr-2"></i>
                <div class="peer mr-2 cur-p" v-on:click="filter='all'">
                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15" :class="{'bgc-blue-50 c-blue-500': filter=='all', 'bgc-blue-grey-50 c-blue-grey-500':filter!='all'}">
                        {{ __('package.filter.all') }}
                    </span>
                </div>
                <div class="peer mr-2 cur-p" v-on:click="filter='unsent'">
                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15" :class="{'bgc-blue-50 c-blue-500': filter=='unsent', 'bgc-blue-grey-50 c-blue-grey-500':filter!='unsent'}">
                        {{ __('package.filter.unsent') }}
                    </span>
                </div>
                <div class="peer cur-p" v-on:click="filter='sent'">
                    <span class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15" :class="{'bgc-blue-50 c-blue-500': filter=='sent', 'bgc-blue-grey-50 c-blue-grey-500':filter!='sent'}">
                        {{ __('package.filter.sent') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3 text-right">
            <button type="button" class="btn btn-primary rounded-0 ml-auto" v-on:click="packageModal.show.create=true">
                <span class="ti ti-truck font-size-30"></span>
                <div>{{ __('package.functional.add')}}</div>
            </button>
        </div>

        <div class="col-12 col-md-6 my-3 mb-md-4" v-for="(package, index) in filterPackage" :key="index">
            <div class="card h-100 package-card rounded-0 border-0 base-box-shadow-with-hover" :class="{'border-success': package.checked, 'border-secondary': !package.checked }">
                <span class="check-icon position-absolute font-size-25 text-white cur-p"v-on:click="onClickUpdatePackageStatus(package, 'checked')" >
                    <i class="ti" :class="{'ti-face-smile': package.checked, 'ti-face-sad': !package.checked}"></i>
                </span>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <h5 class="card-title">
                            @{{ package.arrived_at }}
                            <small class="text-muted">{{ __('package.fields.arrived_at')}}</small>
                            <span v-if="package.sent_at" class="text-primary">
                                <i class="ti ti-truck font-size-20 text-primary"></i>
                                @{{ package.sent_at }}
                                <small class="text-primary">{{ __('package.fields.sent_at')}}</small>
                            </span>
                            <span v-else>
                                <i class="ti ti-truck font-size-20"></i>
                                <small class="text-muted font-weight-bold">{{ __('package.filter.unsent') }}</small>
                            </span>
                            </h5>
                    </div>

                    <dl class="row">
                        <dt class="col-md-3">{{ __('package.fields.name')}}</dt>
                        <dd class="col-md-9">@{{ package.name }}</dd>

                        <dt class="col-md-3">{{ __('package.fields.phone')}}</dt>
                        <dd class="col-md-9">@{{ package.phone }}</dd>

                        <dt class="col-md-3">{{ __('package.fields.address')}}</dt>
                        <dd class="col-md-9">@{{ package.address }}</dd>

                        <dt class="col-md-3">{{ __('package.fields.remark')}}</dt>
                        <dd class="col-md-9 whs-p">@{{ package.remark }}</dd>
                    </dl>
                    <div class="row border-top bdc-grey-400 mb-3">
                        <div class="col-12 mt-2 text-center text-secondary mt-auto mb-auto" v-if="!package.cases.length">
                            <span class="fa fa-exclamation-triangle font-size-60"></span>
                            <div class="card-subtitle">{{ __('order.notification.empty_case') }}</div>
                        </div>
                        <div :class="`col-12 py-1 font-size-20 font-weigh-bold ${ (index%2 == 0)?'bgc-grey-200': ''}`" v-for="(caseItem, index) in package.cases">
                            <span>@{{caseItem.case_type_name}}</span>
                            <span class="float-right mr-2">X<span>
                            @{{caseItem.amount}}</span>
                        </div>
                    </div>
                    <div class="row mt-auto">
                        <div class="btn-group col-12" role="group">
                            <button type="button" class="btn btn-secondary" v-on:click="onClickDeletePackage(package.id)">{{ __('package.functional.del')}}</button>
                            <button type="button" class="btn btn-primary ml-auto" v-on:click="onClickEditPackage(package)">{{ __('package.functional.edit')}}</button>
                            <button type="button" class="btn" :class="{'btn-primary': !package.sent_at, 'btn-secondary': package.sent_at}" v-on:click="onClickUpdatePackageStatus(package, 'sent_at')">
                                @{{ package.sent_at? langs.functional.cancel_sent:langs.functional.sent }}
                            </button>
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