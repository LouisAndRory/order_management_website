@extends('master')

@section('content')
<div id="managementApp" class="col-12 col-md-8" v-cloak>
    <management-modal
        modal-id="managementCreateModal"
        modal-title="{{ __('pack.functional.add')}}"
        :show="managementModal.show.create"
        :langs="langs"
        :fetch-api="fetchCreateApi"
        :initial-data="managementModal.data.create"
        v-on:open="managementModal.show.create=true"
        v-on:close="cleanModalData('create')">
    </management-modal>

    <management-modal
        modal-id="managementEditModal"
        modal-title="{{ __('pack.functional.edit')}}"
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
                <th scope="col">{{ __('pack.fields.name') }}</th>
                <th scope="col">{{ __('pack.fields.slug') }}</th>
                <th scope="col">{{ __('pack.fields.enabled') }}</th>
                <th scope="col">
                    <button type="button" class="btn btn-primary" v-on:click="managementModal.show.create=true">{{ __('pack.functional.add')}}</button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(pack, index) in list" :key="index">
                <th v-text="pack.name"></th>
                <td v-text="pack.slug"></td>
                <td>
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input type="checkbox" :id="'packEnabled_'+index" class="peer" v-model="pack.enabled" true-value="1" false-value="0" v-on:change="itemEnabledHandler(pack, index)">
                        <label :for="'packEnabled_'+index" class="peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">{{ __('pack.replace_string.enabled')}}</span>
                        </label>
                    </div>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" v-on:click="onClickEditItem(pack)">{{ __('pack.functional.edit')}}</button>
                        <button type="button" class="btn btn-secondary" v-on:click="onClickDeleteItem(pack.id)">{{ __('pack.functional.del')}}</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

</div>

@endsection

@section('custom-js')
    const list = @json($packs);
    const baseUrl = '{{ route('pack.index')}}';
    const langs = @json(__('pack'));
    const category = 'pack';
@endsection
