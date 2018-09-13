@extends('master')

@section('content')
<dl class="row">
    <dt class="col-md-3">{{ __('order.fields.name')}}</dt>
    <dd class="col-md-3">{{ $order->name  }}</dd>

    <dt class="col-md-3">{{ __('order.fields.phone')}}</dt>
    <dd class="col-md-3">{{ $order->phone? $order->phone:'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.name_backup')}}</dt>
    <dd class="col-md-3">{{ $order->name_backup? $order->name_backup:'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.phone_backup')}}</dt>
    <dd class="col-md-3">{{ $order->phone_backup? $order->phone_backup:'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.email')}}</dt>
    <dd class="col-md-3">{{ $order->email? $order->email:'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.extra_fee')}}</dt>
    <dd class="col-md-3">{{ $order->extra_fee? $order->extra_fee.' '.__('order.unit.dollar'):'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.deposit')}}</dt>
    <dd class="col-md-3">{{ $order->deposit? $order->deposit.' '.__('order.unit.dollar'):'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.final_paid')}}</dt>
    <dd class="col-md-3">{{ $order->final_paid? __('order.replace_string.paid.yes'):__('order.replace_string.paid.no') }}</dd>

    <dt class="col-md-3">{{ __('order.fields.engaged_date')}}</dt>
    <dd class="col-md-3">{{ $order->engaged_date? $order->engaged_date:'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.married_date')}}</dt>
    <dd class="col-md-3">{{ $order->married_date? $order->married_date:'-' }}</dd>

    <dt class="col-md-3">{{ __('order.fields.card_required')}}</dt>
    <dd class="col-md-3">{{ $order->card_required? __('order.replace_string.required.yes'):__('order.replace_string.required.no') }}</dd>

    <dt class="col-md-3">{{ __('order.fields.wood_required')}}</dt>
    <dd class="col-md-3">{{ $order->wood_required? __('order.replace_string.required.yes'):__('order.replace_string.required.no') }}</dd>

    <dt class="col-md-3">{{ __('order.fields.remark')}}</dt>
    <dd class="col-md-9">{{ $order->remark? $order->remark:'-' }}</dd>
</dl>
@endsection

@section('custom-js')
    const showOrder = @json($order);
    const orderDDL = {
        cases: @json($caseTypes)
    };
@endsection