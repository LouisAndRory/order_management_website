@extends('master')

@section('content')
<div id="managementApp" class="row">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">{{ __('cookie.fields.name') }}</th>
                <th scope="col">{{ __('cookie.fields.slug') }}</th>
                <th scope="col">{{ __('cookie.fields.enabled') }}</th>
                <th scope="col">
                    <button type="button" class="btn btn-primary" v-on:click="onClickEditItem">{{ __('cookie.functional.add')}}</button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(cookie, index) in list" :key="index">
                <th v-text="cookie.name"></th>
                <td v-text="cookie.slug"></td>
                <td>
                    <div class="material-switch mt-2">
                        <input type="checkbox" id="cookieEnabled" class="d-none" v-model="cookie.enabled" true-value="1" false-value="0">
                        <label for="cookieEnabled" class="bg-success"></label>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" v-on:click="onClickEditItem">{{ __('cookie.functional.edit')}}</button>
                        <button type="button" class="btn btn-primary" v-on:click="onClickDeleteItem(cookie.id)">{{ __('cookie.functional.del')}}</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>

@endsection

@section('custom-js')
    const list = @json($cookies);
    const baseUrl = '{{ route('cookie')}}';
@endsection
