@extends('master')

@section('content')
<div class="row mt-lg-5 justify-content-center">
    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <a class="d-flex flex-column justify-content-center align-items-center bg-white" href="{{ route('cookie.index')}}">
            <img src="{{ asset('images/cookie.png') }}" alt="" class="h-auto w-50 py-3">
            <div class="bg-main py-2 text-center text-white w-100">{{ __('navigation.options.cookie') }}</div>
        </a>
    </div>

    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <a class="d-flex flex-column justify-content-center align-items-center bg-white" href="{{ route('caseType.index')}}">
            <img src="{{ asset('images/case.png') }}" alt="" class="h-auto w-50 py-3">
            <div class="bg-main py-2 text-center text-white w-100">{{ __('navigation.options.case') }}</div>
        </a>
    </div>

    <div class="col-12 col-md-4 col-lg-3 mb-3">
        <a class="d-flex flex-column justify-content-center align-items-center bg-white" href="{{ route('pack.index')}}">
            <img src="{{ asset('images/packing.png') }}" alt="" class="h-auto w-50 py-3">
            <div class="bg-main py-2 text-center text-white w-100">{{ __('navigation.options.pack') }}</div>
        </a>
    </div>
</div>
@endsection
