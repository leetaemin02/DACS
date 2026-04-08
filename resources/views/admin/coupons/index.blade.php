@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Quản lý mã giảm giá</h1>
    <a href="{{ route('admin.coupons.create') }}" style="background: var(--primary); color: white; padding: 0.6rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.9rem;">+ Thêm mã mới</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Mã code</th>
                <th>Giảm tiền</th>
                <th>Giảm %</th>
                <th>Số lượng</th>
                <th>Ngày hết hạn</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($coupons as $coupon)
            <tr>
                <td>
                    <span style="background: #f0fdf4; border: 1px solid #86efac; color: #166534; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-weight: 700; font-family: monospace; font-size: 0.9rem; letter-spacing: 0.05em;">{{ $coupon->ma_code }}</span>
                </td>
                <td>{{ $coupon->so_tien_giam ? number_format($coupon->so_tien_giam, 0) . 'đ' : '—' }}</td>
                <td>{{ $coupon->phan_tram_giam ? $coupon->phan_tram_giam . '%' : '—' }}</td>
                <td>
                    <span style="font-weight: 600; {{ $coupon->so_luong <= 0 ? 'color: #ef4444;' : '' }}">{{ $coupon->so_luong }}</span>
                </td>
                <td>{{ $coupon->ngay_het_han ? \Carbon\Carbon::parse($coupon->ngay_het_han)->format('d/m/Y') : 'Không giới hạn' }}</td>
                <td>
                    @php
                        $isExpired = $coupon->ngay_het_han && \Carbon\Carbon::parse($coupon->ngay_het_han)->isPast();
                        $isOutOfStock = $coupon->so_luong <= 0;
                    @endphp
                    @if($isExpired)
                        <span style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">Hết hạn</span>
                    @elseif($isOutOfStock)
                        <span style="background: #f1f5f9; color: #64748b; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">Hết lượt</span>
                    @else
                        <span style="background: #dcfce7; color: #166534; padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">Hoạt động</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" style="padding: 0.35rem 0.75rem; background: var(--primary); color: white; border-radius: 0.4rem; text-decoration: none; font-size: 0.8rem; font-weight: 500;">Sửa</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa mã này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding: 0.35rem 0.75rem; background: #ef4444; color: white; border: none; border-radius: 0.4rem; cursor: pointer; font-size: 0.8rem; font-weight: 500;">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: var(--secondary);">Chưa có mã giảm giá nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
