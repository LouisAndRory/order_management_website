@extends('master')

@section('content')
<div id="managementApp" class="col-12 col-md-8">
    <management-modal
        modal-id="managementCreateModal"
        modal-title="{{ __('cookie.functional.add')}}"
        :show="managementModal.show.create"
        :langs="langs"
        :fetch-api="fetchCreateApi"
        :initial-data="managementModal.data.create"
        v-on:open="managementModal.show.create=true"
        v-on:close="cleanModalData('create')">
    </management-modal>

    <management-modal
        modal-id="managementEditModal"
        modal-title="{{ __('cookie.functional.edit')}}"
        :show="managementModal.show.edit"
        :langs="langs"
        :fetch-api="fetchUpdateApi"
        :initial-data="managementModal.data.edit"
        v-on:open="managementModal.show.edit=true"
        v-on:close="cleanModalData('edit')">
    </management-modal>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">{{ __('cookie.fields.name') }}</th>
                <th scope="col">{{ __('cookie.fields.slug') }}</th>
                <th scope="col">{{ __('cookie.fields.enabled') }}</th>
                <th scope="col">
                    <button type="button" class="btn btn-primary" v-on:click="managementModal.show.create=true">{{ __('cookie.functional.add')}}</button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(cookie, index) in list" :key="index">
                <th v-text="cookie.name"></th>
                <td v-text="cookie.slug"></td>
                <td>
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input type="checkbox" :id="'cookieEnabled_'+index" class="peer" v-model="cookie.enabled" true-value="1" false-value="0" v-on:change="itemEnabledHandler(cookie, index)">
                        <label :for="'cookieEnabled_'+index" class="peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">{{ __('cookie.replace_string.enabled')}}</span>
                        </label>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" v-on:click="onClickEditItem(cookie)">{{ __('cookie.functional.edit')}}</button>
                        <button type="button" class="btn btn-secondary" v-on:click="onClickDeleteItem(cookie.id)">{{ __('cookie.functional.del')}}</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>

@endsection

@section('custom-js')
    const list = @json($cookies);
    const baseUrl = '{{ route('cookie.index')}}';
    const langs = @json(__('cookie'));
    const category = 'cookie';
@endsection
