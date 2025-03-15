@extends('storefront::public.account.layout')

@section('title', trans('storefront::account.pages.my_addresses'))

@section('account_breadcrumb')
<li class="active">{{ trans('storefront::account.pages.my_addresses') }}</li>
@endsection

@section('panel')
<div
    x-data="Addresses({
            initialAddresses: {{ $addresses }},
            initialDefaultAddress: {{ $defaultAddress }},
            countries: {{ json_encode($countries) }}
        })"
    class="panel">
    <div class="panel-header">
        <h4>{{ trans('storefront::account.pages.my_addresses') }}</h4>
    </div>

    <div x-cloak class="panel-body">
        <div class="my-addresses">
            <template x-if="hasAddress && !formOpen">
                <div class="address-card-wrap">
                    <div class="row">
                        <template x-for="address in addresses" :key="address.id">
                            <div class="col-xl-6 col-lg-9 d-flex">
                                <address
                                    class="address-card d-flex flex-column justify-content-between"
                                    :class="{
                                            active: defaultAddress.address_id === address.id
                                        }"
                                    @click="changeDefaultAddress(address)">
                                    <svg class="address-card-selected-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16.78 9.7L11.11 15.37C10.97 15.51 10.78 15.59 10.58 15.59C10.38 15.59 10.19 15.51 10.05 15.37L7.22 12.54C6.93 12.25 6.93 11.77 7.22 11.48C7.51 11.19 7.99 11.19 8.28 11.48L10.58 13.78L15.72 8.64C16.01 8.35 16.49 8.35 16.78 8.64C17.07 8.93 17.07 9.4 16.78 9.7Z" fill="#292D32" />
                                    </svg>

                                    <template x-if="defaultAddress.address_id === address.id">
                                        <span class="badge">
                                            {{ trans('storefront::account.addresses.default') }}
                                        </span>
                                    </template>

                                    <div class="address-card-data">
                                        <span x-text="`${address.full_name} - ${address.phone}`"></span>
                                        <span x-text="address.address"></span>

                                        <span x-html="`${address.city}, ${address.state_name ?? address.district} ${address.ward}`"></span>
                                    </div>

                                    <div class="address-card-actions">
                                        <button type="button" class="btn btn-edit-address" @click.stop="edit(address)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14.91 4.1499C15.58 6.5399 17.45 8.4099 19.85 9.0899" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>

                                            {{ trans('storefront::account.addresses.edit') }}
                                        </button>

                                        <button type="button" class="btn btn-delete-address" @click.stop="remove(address)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.85 9.14001L18.2 19.21C18.09 20.78 18 22 15.21 22H8.79C6 22 5.91 20.78 5.8 19.21L5.15 9.14001" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10.33 16.5H13.66" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9.5 12.5H14.5" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>

                                            {{ trans('storefront::account.addresses.delete') }}
                                        </button>
                                    </div>
                                </address>
                            </div>
                        </template>

                        <div class="col-md-18">
                            <button
                                type="button"
                                class="btn btn-lg btn-default btn-add-new-address"
                                @click="formOpen = true">
                                {{ trans('storefront::account.addresses.add_new_address') }}
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="!(hasAddress && !formOpen)">
                <form @submit.prevent="save" @input="errors.clear($event.target.name)">
                    <div class="add-new-address-form">
                        <h5 class="section-title">
                            {{ trans('storefront::account.addresses.new_address') }}
                        </h5>

                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping-name">
                                        {{ trans('checkout::attributes.shipping.name') }}<span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="full_name"
                                        id="shipping-name"
                                        class="form-control"
                                        x-model="form.full_name"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.name') }}">

                                    <template x-if="errors.has('name')">
                                        <span class="error-message" x-text="errors.get('name')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping-phone">
                                        {{ trans('checkout::attributes.shipping.phone') }}<span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="phone"
                                        id="shipping.phone"
                                        class="form-control"
                                        x-model="form.phone"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.phone') }}">

                                    <template x-if="errors.has('phone')">
                                        <span class="error-message" x-text="errors.get('phone')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-18">
                                <div class="form-group street-address">
                                    <label for="shipping-address">
                                        {{ trans('checkout::attributes.street_address') }}<span>*</span>
                                    </label>

                                    <textarea
                                        cols="30"
                                        rows="4"
                                        name="address"
                                        id="shipping.address"
                                        class="form-control"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.detail_address') }}"
                                        x-model="form.address"></textarea>

                                    <template x-if="errors.has('address')">
                                        <span class="error-message" x-text="errors.get('address')"></span>
                                    </template>
                                </div>
                            </div>
                        
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping_city">
                                        {{ trans('checkout::attributes.shipping.city') }}<span>*</span>
                                    </label>

                                    <select
                                        x-model="form.city"
                                        :value="form.city"
                                        name="city"
                                        id="shipping.city"
                                        class="form-control arrow-black"
                                        @change="changeCity($event.target.value)"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.city') }}"
                                    >
                                        <option value="">{{ trans('checkout::attributes.shipping_ph.city') }}</option>
                                        <template x-for="data in city">
                                            <option :value="data.PROVINCE_ID" x-text="data.PROVINCE_NAME"></option>
                                        </template>
                                    </select>

                                    <template x-if="errors.has('city')">
                                        <span class="error-message" x-text="errors.get('city')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping_district">
                                        {{ trans('checkout::attributes.shipping.district') }}<span>*</span>
                                    </label>
                                    <select
                                        x-model="form.district"
                                        name="district"
                                        id="shipping_district"
                                        class="form-control arrow-black"
                                        @change="changeDistrict($event.target.value)"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.district') }}"
                                    >
                                        <option value="">{{ trans('checkout::attributes.shipping_ph.district') }}</option>
                                        <template x-for="data in district" :key="data.DISTRICT_ID">
                                            <option :value="data.DISTRICT_ID" x-text="data.DISTRICT_NAME"></option>
                                        </template>
                                    </select>

                                    <template x-if="errors.has('district')">
                                        <span class="error-message" x-text="errors.get('district')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping_ward">
                                        {{ trans('checkout::attributes.shipping.ward') }}<span>*</span>
                                    </label>
                                    <select
                                        :value="form.ward"
                                        name="ward"
                                        id="shipping_ward"
                                        class="form-control arrow-black"
                                        @change="changeWard($event.target.value)"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.ward') }}"
                                    >
                                        <option value="">{{ trans('checkout::attributes.shipping_ph.ward') }}</option>
                                        <template x-for="data in ward" :key="data.WARDS_ID">
                                            <option :value="data.WARDS_ID" x-text="data.WARDS_NAME"></option>
                                        </template>
                                    </select>

                                    <template x-if="errors.has('ward')">
                                        <span class="error-message" x-text="errors.get('ward')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-18">
                                <button
                                    type="button"
                                    class="btn btn-lg btn-default btn-cancel"
                                    v-if="hasAddress"
                                    @click="cancel">
                                    {{ trans('storefront::account.addresses.cancel') }}
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-lg btn-primary btn-save-address"
                                    :class="{ 'btn-loading': loading }">
                                    {{ trans('storefront::account.addresses.save_address') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection

@push('globals')
<script>
    FleetCart.langs['storefront::account.addresses.confirm'] = '{{ trans('
    storefront::account.addresses.confirm ') }}';
</script>

@vite([
'modules/Storefront/Resources/assets/public/sass/pages/account/addresses/main.scss',
'modules/Storefront/Resources/assets/public/js/pages/account/addresses/main.js',
])
@endpush