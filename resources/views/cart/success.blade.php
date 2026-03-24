@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 5rem; padding-bottom: 5rem;">
    <div style="max-width: 800px; margin: 0 auto; background: white; border-radius: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); overflow: hidden;">

        <!-- Header Success -->
        <div style="background: #4f46e5; padding: 3rem 2rem; text-align: center; color: white;">
            <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem;">Thanh toán thành công!</h1>
            <p style="font-size: 1.125rem; opacity: 0.9;">Mã đơn hàng: #{{ $donHang->id }}</p>
        </div>

        <div style="padding: 2.5rem;">
            <!-- Order Notification -->
            <div style="background: #f0fdf4; border-left: 4px solid #4f46e5; padding: 1.25rem; border-radius: 0.5rem; margin-bottom: 2.5rem;">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="color: #4f46e5;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div>
                        <strong style="display: block; color: #064e3b; margin-bottom: 0.25rem;">Đang chờ duyệt đơn từ Shop</strong>
                        <span style="color: #065f46; font-size: 0.9375rem;">Hệ thống đã ghi nhận đơn hàng của bạn. Nhân viên sẽ sớm kiểm tra và xác nhận đơn hàng trong thời gian ngắn nhất.</span>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div style="margin-bottom: 2.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.75rem;">Sản phẩm đã mua</h3>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @foreach($donHang->chiTietDonHangs as $chiTiet)
                    <div style="display: flex; align-items: center; gap: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                        <div style="width: 70px; height: 90px; background: #f8fafc; border-radius: 0.5rem; overflow: hidden; flex-shrink: 0; border: 1px solid #e2e8f0;">
                            @if($chiTiet->sach->hinh_anh)
                            <img src="{{ Str::startsWith($chiTiet->sach->hinh_anh, ['http://', 'https://']) ? $chiTiet->sach->hinh_anh : asset('storage/' . $chiTiet->sach->hinh_anh) }}"
                                alt="{{ $chiTiet->sach->ten_sach }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #94a3b8; font-size: 0.7rem;">No Image</div>
                            @endif
                        </div>
                        <div style="flex-grow: 1;">
                            <h4 style="font-weight: 600; font-size: 1rem; margin-bottom: 0.25rem; color: #1e293b;">{{ $chiTiet->sach->ten_sach }}</h4>
                            <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 0;">Số lượng: {{ $chiTiet->so_luong }}</p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-weight: 700; color: #0f172a;">{{ number_format($chiTiet->don_gia * $chiTiet->so_luong, 0) }}đ</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Summary -->
            <div style="background: #f8fafc; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #64748b;">Phương thức thanh toán:</span>
                    <span style="font-weight: 600; color: #1e293b;">{{ $donHang->thanhToan->phuong_thuc }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #64748b;">Trạng thái đơn hàng:</span>
                    <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700;">{{ $donHang->trang_thai }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #cbd5e1;">
                    <span style="font-size: 1.125rem; font-weight: 700; color: #1e293b;">Tổng số tiền:</span>
                    <span style="font-size: 1.25rem; font-weight: 800; color: #4f46e5;">{{ number_format($donHang->tong_tien, 0) }}đ</span>
                </div>
            </div>

            <!-- Actions -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <a href="{{ route('home') }}" style="display: block; text-align: center; padding: 1rem; background: #f1f5f9; color: #475569; border-radius: 0.75rem; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                    Quay lại trang chủ
                </a>
                <a href="#" style="display: block; text-align: center; padding: 1rem; background: #4f46e5; color: white; border-radius: 0.75rem; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); transition: transform 0.2s;">
                    Kiểm tra đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    a:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endsection