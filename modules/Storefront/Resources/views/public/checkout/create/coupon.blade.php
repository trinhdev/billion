<div class="coupon-wrap" x-data="{ showCouponModal: false, couponCode: '' }">
    <!-- Ô nhập mã giảm giá -->
    <div class="d-flex">
        <input
            type="text"
            placeholder="{{ trans('storefront::checkout.enter_coupon_code') }}"
            class="form-control"
            @keyup.enter="applyCoupon"
            @input="couponError = null"
            x-model="couponCode">

        <button
            type="button"
            class="btn btn-default btn-apply-coupon"
            @click.prevent="applyCoupon">
            {{ trans('storefront::checkout.apply') }}
        </button>
        <button
            type="button"
            class="btn btn-primary btn-apply-coupon"
            @click="showCouponModal = true">
            {{ __('Tìm') }}
        </button>
    </div>

    <!-- Modal danh sách mã giảm giá -->
    <template x-if="showCouponModal">
        <div class="modal-overlay" @click.self="showCouponModal = false">
            <div class="modal-content">
                <h4 class="title">Mã giảm giá khả dụng</h4>
                <ul>
                    <template x-for="coupon in cart.couponAll">
                        <li>
                            <label
                                :for="'coupon-' + coupon.code"
                                x-text="coupon.code + ' - ' + formatCurrency(coupon.value.amount)"></label>
                            <input
                                type="radio"
                                :id="'coupon-' + coupon.code"
                                name="coupon_select"
                                class="form-control"
                                @input="couponError = null"
                                x-model="couponCode"
                                :value="coupon.code"
                                hidden />

                        </li>
                    </template>
                </ul>
                <div class="modal-buttons">
                    <button
                        type="button"
                        class="btn btn-default btn-apply-coupon"
                        @click="showCouponModal = false">
                        Đóng
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary btn-apply-coupon"
                        @click.prevent="applyCoupon, showCouponModal = false"
                        :disabled="!couponCode">
                        {{ trans('storefront::checkout.apply') }}
                    </button>
                </div>
            </div>
        </div>
    </template>

    <span class="error-message" x-text="couponError"></span>
</div>

<!-- CSS trực tiếp trong file -->
<style>
    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        /* Màu nền mờ */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    /* Modal Content */
    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        /* Điều chỉnh kích thước modal */
        max-width: 90%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .modal-content h2 {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: #333;
    }

    /* Danh sách mã giảm giá */
    .modal-content ul {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
        text-align: left;
    }

    .modal-content ul li {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
        transition: background 0.2s;
    }

    .modal-content ul li:last-child {
        border-bottom: none;
    }

    .modal-content ul li:hover {
        background: #f5f5f5;
    }

    /* Radio Button */
    .modal-content input[type="radio"] {
        margin-right: 10px;
        cursor: pointer;
    }

    .modal-content label {
        flex: 1;
        font-size: 1rem;
        color: #333;
        cursor: pointer;
    }
    
</style>