<div class="coupon-wrap" x-data="{ 
    showCouponModal: false, 
    couponCode: '', 
    searchCoupon: '', 
    selectedCoupon: '' 
}">
    <!-- Ô nhập mã giảm giá -->
    <div class="d-flex">
        <input
            type="text"
            placeholder="{{ trans('storefront::checkout.enter_coupon_code') }}"
            class="form-control"
            @keyup.enter="applyCoupon"
            @input="couponError = null"
            x-model="couponCode"
        >

        <button
            type="button"
            class="btn btn-default btn-apply-coupon"
            @click.prevent="applyCoupon"
        >
            {{ trans('storefront::checkout.apply') }}
        </button>
    </div>

    <!-- Tiêu đề (Nhấn để mở modal) -->
    <h3 @click="showCouponModal = true" class="cursor-pointer text-primary">
        Danh sách mã giảm giá
    </h3>

    <!-- Modal danh sách mã giảm giá -->
    <template x-if="showCouponModal">
        <div class="modal-overlay" @click.self="showCouponModal = false">
            <div class="modal-content">
                <h2>Danh sách mã giảm giá</h2>

                <!-- Ô tìm kiếm mã giảm giá -->
                <input 
                    type="text" 
                    class="form-control search-box" 
                    placeholder="Tìm kiếm mã giảm giá..."
                    x-model="searchCoupon"
                >

                <ul class="coupon-list">
    <template x-for="coupon in cart.couponAll.filter(c => c.code.toLowerCase().includes(searchCoupon.toLowerCase()))">
        <li class="coupon-item">
            
            <div class="coupon-info">
                <strong class="coupon-code" x-text="coupon.code"></strong>
                <span class="coupon-value" x-text="'Giảm: ' + formatCurrency(coupon.value.amount)"></span>
                <span class="coupon-end-date" x-text="'Hạn: ' + formatDate(coupon.end_date)"></span>

            </div>
            <input 
                type="radio" 
                :value="coupon.code" 
                x-model="selectedCoupon"
                class="coupon-radio"
            >
        </li>
    </template>
</ul>


                <button 
                    type="button"
                    class="btn btn-primary mt-2"
                    :disabled="!selectedCoupon"
                    @click="couponCode = selectedCoupon; applyCoupon(); showCouponModal = false"
                >
                    Áp dụng mã giảm giá
                </button>

                <button 
                    class="btn btn-secondary mt-3" 
                    @click="showCouponModal = false"
                >
                    Đóng
                </button>
                <!-- Hiển thị giảm giá dự kiến -->
<!-- Hiển thị giảm giá dự kiến -->
<div class="discount-info" x-show="selectedCoupon">
    <span>Giảm giá dự kiến:</span>
    <strong x-text="'-' + formatCurrency(cart.couponAll.find(c => c.code === selectedCoupon)?.value.amount || 0)"></strong>
</div>

            </div>
        </div>
    </template>

    <span class="error-message" x-text="couponError"></span>
</div>
<script>
function formatDate(dateString) {
    let date = new Date(dateString);
    return date.toLocaleDateString("vi-VN", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
    }).replace(/\//g, '.');
}
</script>
<!-- CSS -->
<style>
    .discount-info {
    margin-top: 12px;
    padding: 10px;
    background: #fff6f6;
    color: #d70018;
    font-size: 16px;
    font-weight: 600;
    border: 1px solid #ffd4d4;
    border-radius: 6px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: fadeIn 0.3s ease-in-out;
}

/* Hiệu ứng hiển thị mượt mà */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease-in-out;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    max-width: 420px;
    width: 90%;
    text-align: center;
    position: relative;
    transform: scale(0.95);
    opacity: 0;
    animation: zoomIn 0.3s forwards;
}

/* Animation cho modal */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes zoomIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Nút bấm */
button {
    padding: 10px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
}

/* Input tìm kiếm */
.search-box {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: border 0.2s ease-in-out;
}
/* Danh sách mã giảm giá */
.coupon-list {
    list-style-type: none;
    padding-left: 0;
    margin: 0;
}

/* Mỗi coupon */
.coupon-item {
    display: flex;
    align-items: center;
    background: #fff6f6;
    padding: 12px;
    border: 1px solid #ffd4d4;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: transform 0.2s ease-in-out;
}

.coupon-item:hover {
    transform: scale(1.02);
}

/* Radio button */
.coupon-radio {
    margin-right: 10px;
}

/* Thông tin coupon */
.coupon-info {
    display: flex;
    flex-direction: column;
}

/* Mã coupon */
.coupon-code {
    font-size: 16px;
    font-weight: 600;
    color: #d70018;
}

/* Giá trị giảm */
.coupon-value {
    font-size: 14px;
    color: #333;
}

/* Ngày hết hạn */
.coupon-end-date {
    font-size: 13px;
    color: #888;
    margin-top: 2px;
}
.coupon-radio {
    margin-left: 50px;
    transform: scale(1.2);
    cursor: pointer;
}
.search-box:focus {
    border-color: #007bff;
    outline: none;
}

/* Ẩn dấu chấm trong danh sách */
ul {
    list-style-type: none;
    padding-left: 0;
}

ul li {
    padding: 8px;
    border-bottom: 1px solid #eee;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 10px;
}

ul li:last-child {
    border-bottom: none;
}

</style>
