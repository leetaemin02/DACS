@extends('layouts.app')

@section('content')

<script src="{{ asset('js/checkout.js') }}"></script>

<div class="checkout-container">
    <div class="row">

        <div class="col-md-7">
            <div class="card checkout-card">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: bottom; color: #4f46e5;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Thông tin đặt hàng
                </h3>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="name" placeholder="Nhập họ tên của bạn" required value="{{ Auth::check() ? Auth::user()->ho_ten : '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" placeholder="Nhập số điện thoại liên hệ" required value="{{ Auth::check() ? Auth::user()->so_dien_thoai : '' }}">
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ giao hàng</label>
                        <textarea name="address" placeholder="Nhập địa chỉ nhận hàng chi tiết" required>{{ Auth::check() ? Auth::user()->dia_chi : '' }}</textarea>
                    </div>

                    <div class="payment-section-title">Phương thức thanh toán</div>
                    <div class="payment-box">
                        <label>
                            <input type="radio" name="payment" value="cod" checked> 
                            <span>Thanh toán khi nhận hàng (COD)</span>
                        </label>
                        <label>
                            <input type="radio" name="payment" value="vnpay"> 
                            <span>Thanh toán qua VNPAY</span>
                            <img src="https://vnpay.vn/s1/vnpay/uploads/vnpay-logo-2.png" alt="VNPAY" height="20" style="margin-left: -5px;">
                        </label>
                    </div>

                    <input type="hidden" name="ma_giam_gia_id" id="ma_giam_gia_id" value="">
                    <button class="btn-order">Xác nhận đặt hàng</button>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card checkout-card">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; vertical-align: bottom; color: #ec4899;">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Tóm tắt đơn hàng
                </h3>

                @foreach($cartItems as $item)
                <div class="item">
                    <span>
                        <span style="font-weight: 700; color: #4f46e5;">{{ $item->so_luong }}x</span> 
                        {{ $item->sach->ten_sach }}
                    </span>
                    <span>{{ number_format($item->sach->gia * $item->so_luong, 0, ',', '.') }}đ</span>
                </div>
                @endforeach

                <div class="coupon-box">
                    <input type="text" id="coupon" placeholder="Nhập mã giảm giá...">
                    <button type="button" onclick="applyCoupon()">Áp dụng</button>
                </div>

                <hr>

                <div class="price">
                    <span>Tạm tính</span>
                    <span><span id="subtotal" style="display:none;">{{ $total }}</span>{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>

                <div class="price discount">
                    <span>Giảm giá</span>
                    <span>- <span id="discount">0</span>đ</span>
                </div>

                <div class="price total">
                    <span>Tổng cộng</span>
                    <span><span id="total-text">{{ number_format($total, 0, ',', '.') }}</span>đ</span>
                    <input type="hidden" id="raw-total" value="{{ $total }}">
                </div>
            </div>
        </div>

    </div>
</div>

@endsection