<div class="shipping-details">
    <div class="row">
        <div class="col-md-18">
            <div class="ship-to-different-address-form">
                <h4 class="section-title">{{ trans('storefront::checkout.shipping_details') }}</h4>

                <template x-if="hasAddress">
                    <div class="address-card-wrap">
                        <div class="row">
                            <template x-for="address in addresses" :key="address.id">
                                <div class="col d-flex">
                                    <address
                                        class="address-card"
                                        :class="{
                                            active: form.shippingAddressId === address.id && !form.newShippingAddress,
                                            'cursor-default': form.newShippingAddress
                                        }"
                                        @click="changeShippingAddress(address)"
                                    >
                                        <svg class="address-card-selected-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16.78 9.7L11.11 15.37C10.97 15.51 10.78 15.59 10.58 15.59C10.38 15.59 10.19 15.51 10.05 15.37L7.22 12.54C6.93 12.25 6.93 11.77 7.22 11.48C7.51 11.19 7.99 11.19 8.28 11.48L10.58 13.78L15.72 8.64C16.01 8.35 16.49 8.35 16.78 8.64C17.07 8.93 17.07 9.4 16.78 9.7Z" fill="#292D32"/>
                                        </svg>  
                                        
                                        <template x-if="defaultAddress.address_id === address.id">
                                            <span class="badge">
                                                {{ trans('storefront::checkout.default') }}
                                            </span>
                                        </template>
                                        
                                        <div class="address-card-data">
                                            <span x-text="address.full_name"></span>
                                            <span x-text="address.address"></span>

                                            <template x-if="address.address_2">
                                                <span x-text="address.address_2"></span>
                                            </template>

                                            <span x-html="`${address.city}, ${address.state_name ?? address.state} ${address.district}`"></span>
                                            <span x-text="address.ward_name"></span>
                                        </div>
                                    </address>
                                </div>
                            </template>
                        </div>

                        <template x-if="!form.newShippingAddress && !form.shippingAddressId">
                            <span class="error-message">
                                {{ trans('storefront::checkout.you_must_select_an_address') }}
                            </span>
                        </template>
                    </div>
                </template>

                <div class="add-new-address-wrap">
                    <template x-if="hasAddress">
                        <button
                            type="button"
                            class="btn btn-add-new-address"
                            @click="addNewShippingAddress"
                        >
                            <span x-text="form.newShippingAddress ? '-' : '+'"></span>
                            
                            {{ trans('storefront::checkout.add_new_address') }}
                        </button>
                    </template>

                    <div class="add-new-address-form" x-show="!hasAddress || form.newShippingAddress">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping-name">
                                        {{ trans('checkout::attributes.shipping.name') }}<span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="shipping[name]"
                                        id="shipping-name"
                                        class="form-control"
                                        x-model="form.shipping.name"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.name') }}"
                                    >

                                    <template x-if="errors.has('shipping.name')">
                                        <span class="error-message" x-text="errors.get('shipping.name')"></span>
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
                                        name="shipping[phone]"
                                        id="shipping-phone"
                                        class="form-control"
                                        x-model="form.shipping.phone"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.phone') }}"
                                    >

                                    <template x-if="errors.has('shipping.phone')">
                                        <span class="error-message" x-text="errors.get('shipping.phone')"></span>
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
                                        name="shipping[address]"
                                        id="shipping-address"
                                        class="form-control"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.detail_address') }}"
                                        x-model="form.shipping.address"
                                    ></textarea>

                                    <template x-if="errors.has('shipping.address')">
                                        <span class="error-message" x-text="errors.get('shipping.address')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping_city">
                                        {{ trans('checkout::attributes.shipping.city') }}<span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="shipping[city]"
                                        :value="form.shipping.city"
                                        id="shipping_city"
                                        class="form-control"
                                        @change="changeShippingCity($event.target.value)"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.city') }}"
                                    >

                                    <template x-if="errors.has('shipping.city')">
                                        <span class="error-message" x-text="errors.get('shipping.city')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping_district">
                                        {{ trans('checkout::attributes.shipping.district') }}<span>*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="shipping[district]"
                                        :value="form.shipping.district"
                                        id="shipping_district"
                                        class="form-control"
                                        @change="changeShipping-district($event.target.value)"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.district') }}"
                                    >

                                    <template x-if="errors.has('shipping.district')">
                                        <span class="error-message" x-text="errors.get('shipping.district')"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="shipping_ward">
                                        {{ trans('checkout::attributes.shipping.ward') }}<span>*</span>
                                    </label>

                                    <select
                                        name="shipping[ward]"
                                        id="shipping_ward"
                                        class="form-control arrow-black"
                                        @change="changeShippingward($event.target.value)"
                                        placeholder="{{ trans('checkout::attributes.shipping_ph.ward') }}"
                                    >
                                        <option value="">{{ trans('storefront::checkout.please_select') }}</option>

                                        <template x-for="(name, code) in countries" :key="code">
                                            <option :value="code" x-text="name"></option>
                                        </template>
                                    </select>

                                    <template x-if="errors.has('shipping.ward')">
                                        <span class="error-message" x-text="errors.get('shipping.ward')"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .street-address textarea{
        height: 50px;
    }
</style>