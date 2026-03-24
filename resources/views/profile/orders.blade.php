@extends('layouts.app')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div style="display: flex; gap: 2rem;">
        <!-- Sidebar -->
        <div style="width: 280px; flex-shrink: 0;">
            <div class="profile-card">
                <h3 class="profile-title" style="font-size: 1.25rem;">Tài khoản của tôi</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 0.75rem;">
                        <a href="{{ route('profile.index') }}" style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); transition: var(--transition);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Thông tin cá nhân
                        </a>
                    </li>
                    <li style="margin-bottom: 0.75rem;">
                        <a href="{{ route('profile.orders') }}" style="display: flex; align-items: center; gap: 0.75rem; color: var(--primary-color); font-weight: 600;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            Đơn hàng của tôi
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div style="flex: 1;">
            <div class="profile-card">
                <h2 class="profile-title">Lịch sử đơn hàng</h2>

                @forelse($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span class="order-date">Mã đơn hàng:</span>
                                <span class="order-id">#{{ $order->id }}</span>
                                <span style="margin: 0 0.75rem; color: var(--border-color);">|</span>
                                <span class="order-date">Ngày đặt:</span>
                                <span style="margin-left: 0.5rem; font-size: 0.875rem;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                @php
                                    $statusClass = [
                                        'pending' => 'status-pending',
                                        'processing' => 'status-processing',
                                        'shipped' => 'status-shipped',
                                        'delivered' => 'status-delivered',
                                        'cancelled' => 'status-cancelled'
                                    ];
                                    $statusText = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao hàng',
                                        'delivered' => 'Đã giao hàng',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                @endphp
                                <span class="status-badge {{ $statusClass[$order->trang_thai] ?? '' }}">
                                    {{ $statusText[$order->trang_thai] ?? $order->trang_thai }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="order-body">
                            @foreach($order->chiTietDonHangs as $item)
                                <div class="order-item">
                                    <div class="order-item-img">
                                        @if($item->sach && $item->sach->hinh_anh)
                                            <img src="{{ Str::startsWith($item->sach->hinh_anh, ['http://', 'https://']) ? $item->sach->hinh_anh : asset('storage/' . $item->sach->hinh_anh) }}" 
                                                 alt="{{ $item->sach->ten_sach }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 0.75rem;">No Img</div>
                                        @endif
                                    </div>
                                    <div class="order-item-info">
                                        <h4 class="order-item-title">{{ $item->sach->ten_sach ?? 'Sản phẩm đã bị xóa' }}</h4>
                                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Số lượng: {{ $item->so_luong }} x {{ number_format($item->don_gia, 0) }}đ</div>
                                    </div>
                                    <div style="text-align: right; font-weight: 600;">
                                        {{ number_format($item->so_luong * $item->don_gia, 0) }}đ
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="order-footer">
                                <div>
                                    @if($order->dia_chi_giao_hang)
                                        <div style="font-size: 0.875rem; color: var(--text-secondary);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 4px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            {{ $order->dia_chi_giao_hang }}
                                        </div>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <span class="order-total-label">Tổng thanh toán:</span>
                                    <span class="order-total-value">{{ number_format($order->tong_tien, 0) }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 4rem 2rem; background: #f8fafc; border-radius: 12px; border: 2px dashed var(--border-color);">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1.5rem;"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 1.5rem;">Bạn chưa có đơn hàng nào.</p>
                        <a href="{{ route('home') }}" class="nav-btn">Tiếp tục mua sắm</a>
                    </div>
                @endforelse

                <div class="pagination-container" style="margin-top: 2rem;">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
