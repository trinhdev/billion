<div x-data="{
    showCouponModal: false
}" x-init="console.log(cart.availableCoupon)">
    <div class="coupon-wrap container">
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
        </div>
        <div class="d-flex m-t-5 find-button">
                <button
                    type="button"
                    class="btn btn-default"
                    @click="showCouponModal = true">
                    {{ __('Chọn mã giảm giá khả dụng') }}
                </button>
            </div>

        <!-- Modal danh sách mã giảm giá -->
        <template x-if="showCouponModal">
            <div 
                class="modal-overlay" 
                x-show="showCouponModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.self="showCouponModal = false"
            >
                <div class="modal-content">
                    <h4 class="title">Mã giảm giá khả dụng</h4>
                    <ul>
                        <template x-for="availableCoupon in cart.availableCoupon">
                            <li>
                                <label
                                    :for="'coupon-' + availableCoupon.code"
                                    x-text="'[' + availableCoupon.code + '] Giảm ' + (availableCoupon.is_percent === 1 ? parseInt(availableCoupon.value) + '%' : formatCurrency(availableCoupon.value.amount)) + (availableCoupon.free_shipping ? ' + Freeship' : '')"></label>
                                <input
                                    type="radio"
                                    :id="'coupon-' + availableCoupon.code"
                                    name="coupon_select"
                                    class="form-control"
                                    @input="couponError = null"
                                    x-model="couponCode"
                                    :value="availableCoupon.code"
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
                            @click.prevent="applyCoupon(); showCouponModal = false"
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
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 160 ; /* Cao hơn tất cả các phần tử khác */
            opacity: 0; /* Mặc định ẩn */
            visibility: hidden; /* Ẩn hoàn toàn khi không active */
            transition: opacity 0.3s ease-out, visibility 0s linear 1s; /* Hiệu ứng opacity */
        }

        /* Khi modal active (do Alpine.js xử lý qua x-show) */
        .modal-overlay[x-show="showCouponModal"] {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease-out, visibility 0s linear; /* Hiển thị ngay lập tức */
        }

        /* Modal Content */
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .modal-content h4 {
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
        .find-button {
            padding-top: 10px;
        }
        /* ẩn css đè */
        .category-nav {
            z-index: 0;
        }
        .navigation-inner {
            position: initial;
        }
        .header-wrap {
            position: unset;
        }
        .header-search-wrap {
            z-index: 0;
        }
        .header-search-wrap-parent .header-search-wrap-overlay.active {
            visibility: hidden; 
            opacity: 0;
        }
    </style>
</div>