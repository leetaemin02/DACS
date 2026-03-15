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
                <td style="font-weight: 700;">{{ number_format($order->tong_tien, 3) }}đ</td>
                <td>{{ $order->thanhToan->phuong_thuc ?? 'N/A' }}</td>
                <td>
                    <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; 
                        @if($order->trang_thai == 'Chờ duyệt') background: #fef3c7; color: #92400e;
                        @elseif($order->trang_thai == 'Đã giao hàng') background: #dcfce7; color: #166534;
                        @else background: #f1f5f9; color: #475569; @endif">
                        {{ $order->trang_thai }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        <select name="trang_thai" onchange="this.form.submit()" style="padding: 0.4rem; border-radius: 0.4rem; border: 1px solid #e2e8f0;">
                            <option value="Chờ duyệt" {{ $order->trang_thai == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="Đang xử lý" {{ $order->trang_thai == 'Đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="Đang giao hàng" {{ $order->trang_thai == 'Đang giao hàng' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="Đã giao hàng" {{ $order->trang_thai == 'Đã giao hàng' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="Đã hủy" {{ $order->trang_thai == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
