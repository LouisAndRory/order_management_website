@extends('master')

@section('content')
<div id="managementApp" class="row">
    <management-modal
        modal-id="managementCreateModal"
        modal-title="{{ __('case.functional.add')}}"
        :show="managementModal.show.create"
        :langs="langs"
        :fetch-api="fetchCreateApi"
        :initial-data="managementModal.data.create"
        v-on:open="managementModal.show.create=true"
        v-on:close="cleanModalData('create')">
    </management-modal>

    <management-modal
        modal-id="managementEditModal"
        modal-title="{{ __('case.functional.edit')}}"
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
                <th scope="col">{{ __('case.fields.name') }}</th>
                <th scope="col">{{ __('case.fields.slug') }}</th>
                <th scope="col">{{ __('case.fields.enabled') }}</th>
                <th scope="col">
                    <button type="button" class="btn btn-primary" v-on:click="managementModal.show.create=true">{{ __('case.functional.add')}}</button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(caseItem, index) in list" :key="index">
                <th v-text="caseItem.name"></th>
                <td v-text="caseItem.slug"></td>
                <td>
                    <div class="material-switch mt-2">
                        <input type="checkbox" :id="'caseEnabled_'+index" class="d-none" v-model="caseItem.enabled" true-value="1" false-value="0" v-on:change="itemEnabledHandler(caseItem, index)">
                        <label :for="'caseEnabled_'+index" class="bg-success"></label>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" v-on:click="onClickEditItem(caseItem)">{{ __('case.functional.edit')}}</button>
                        <button type="button" class="btn btn-primary" v-on:click="onClickDeleteItem(caseItem.id)">{{ __('case.functional.del')}}</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>

@endsection

@section('custom-js')
    const list = @json($cases);
    const baseUrl = '{{ route('case.index')}}';
    const langs = @json(__('case'));
    const category = 'case';
@endsection
