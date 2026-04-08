@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Quản lý đơn hàng</h1>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Mã ĐH</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $order->nguoiDung->ho_ten ?? 'N/A' }}</div>
                    <div style="font-size: 0.75rem; color: var(--secondary);">{{ $order->nguoiDung->email ?? '' }}</div>
                </td>
                <td style="font-weight: 700;">{{ number_format($order->tong_tien, 0) }}đ</td>
                <td>{{ $order->thanhToan->phuong_thuc ?? 'N/A' }}</td>
                <td>
                    <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; 
                        @if($order->trang_thai == 'Chờ duyệt') background: #fef3c7; color: #92400e;
                        @elseif($order->trang_thai == 'Đã giao hàng') background: #dcfce7; color: #166534;
                        @else background: #f1f5f9; color: #475569; @endif">
                        {{ $order->trang_thai }}
                    </span>
                </td>
                <td style="display: flex; gap: 0.5rem; align-items: center;">
                    @if(in_array($order->trang_thai, ['Đã giao hàng', 'Đã hủy']))
                        <div style="padding: 0.4rem 0.6rem; border-radius: 0.4rem; border: 1px solid #e2e8f0; background: #f8fafc; color: #94a3b8; font-size: 0.825rem; cursor: not-allowed; display: flex; align-items: center; gap: 0.3rem;" title="Trạng thái cuối cùng, không thể thay đổi">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Khóa sửa
                        </div>
                    @else
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <select name="trang_thai" onchange="this.form.submit()" style="padding: 0.4rem; border-radius: 0.4rem; border: 1px solid #cbd5e1; font-size: 0.875rem; background: white; cursor: pointer; outline: none; transition: 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#cbd5e1'">
<option value="Chờ duyệt" {{ $order->trang_thai == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="Đang xử lý" {{ $order->trang_thai == 'Đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="Đang giao hàng" {{ $order->trang_thai == 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="Đã giao hàng" {{ $order->trang_thai == 'Đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="Đã hủy" {{ $order->trang_thai == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </form>
                    @endif
                    <button onclick="document.getElementById('modal-order-{{ $order->id }}').style.display='flex'" style="padding: 0.4rem 0.75rem; border: none; background: var(--primary); color: white; border-radius: 0.4rem; cursor: pointer; font-size: 0.875rem;">Chi tiết</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $orders->onEachSide(1)->links() }}
    </div>
</div>

@foreach($orders as $order)
<div id="modal-order-{{ $order->id }}" class="modal-overlay" style="display: none;" onclick="if(event.target === this) this.style.display='none'">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Chi tiết đơn hàng #{{ $order->id }}</h3>
            <button onclick="document.getElementById('modal-order-{{ $order->id }}').style.display='none'" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p><strong>Khách hàng:</strong> {{ $order->nguoiDung->ho_ten ?? 'N/A' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->dia_chi_giao_hang ?? 'N/A' }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->nguoiDung->so_dien_thoai ?? 'N/A' }}</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <hr style="margin: 1rem 0; border: none; border-top: 1px solid #e2e8f0;">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="padding: 0.5rem; border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 0.875rem;">Sản phẩm</th>
                        <th style="padding: 0.5rem; border-bottom: 2px solid #e2e8f0; text-align: center; color: #64748b; font-size: 0.875rem;">SL</th>
                        <th style="padding: 0.5rem; border-bottom: 2px solid #e2e8f0; text-align: right; color: #64748b; font-size: 0.875rem;">Đơn giá</th>
                        <th style="padding: 0.5rem; border-bottom: 2px solid #e2e8f0; text-align: right; color: #64748b; font-size: 0.875rem;">Thành tiền</th>
</tr>
                </thead>
                <tbody>
                    @foreach($order->chiTietDonHangs as $chitiet)
                    <tr>
                        <td style="padding: 0.5rem; border-bottom: 1px solid #f1f5f9;">
                            <div style="font-weight: 500;">{{ $chitiet->sach->ten_sach ?? 'Sản phẩm không tồn tại' }}</div>
                        </td>
                        <td style="padding: 0.5rem; border-bottom: 1px solid #f1f5f9; text-align: center;">{{ $chitiet->so_luong }}</td>
                        <td style="padding: 0.5rem; border-bottom: 1px solid #f1f5f9; text-align: right;">{{ number_format($chitiet->don_gia, 0) }}đ</td>
                        <td style="padding: 0.5rem; border-bottom: 1px solid #f1f5f9; text-align: right; font-weight: 500;">{{ number_format($chitiet->so_luong * $chitiet->don_gia, 0) }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="text-align: right; margin-top: 1rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                @if($order->maGiamGia)
                <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; color: #64748b;">Mã giảm giá <span style="font-family:monospace">({{ $order->maGiamGia->ma_code }})</span>: <span style="color: #ef4444;">-{{ $order->maGiamGia->so_tien_giam ? number_format($order->maGiamGia->so_tien_giam,0).'đ' : $order->maGiamGia->phan_tram_giam.'%' }}</span></h4>
                @endif
                <h4 style="margin: 0; font-size: 1.25rem;">Tổng cộng: <span style="color: var(--primary);">{{ number_format($order->tong_tien, 0) }}đ</span></h4>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(2px);
}
.modal-content {
    background: white;
    width: 600px;
    max-width: 95%;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    animation: modalSlideIn 0.3s ease;
    display: flex;
    flex-direction: column;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}
.modal-header h3 { margin: 0; font-size: 1.25rem; color: #1e293b; }
.close-btn {
    background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;
    padding: 0; line-height: 1; display: flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 50%; transition: 0.2s;
}
.close-btn:hover { background: #f1f5f9; color: #ef4444; }
.modal-body {
    padding: 1.5rem;
    max-height: 70vh;
    overflow-y: auto;
}
.modal-body p { margin: 0.5rem 0; color: #334155; }
@keyframes modalSlideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
@endsection