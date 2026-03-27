@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Quản lý sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">+ Thêm sản phẩm</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>Tác giả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    <img src="{{ $product->hinh_anh ? (Str::startsWith($product->hinh_anh, 'http') ? $product->hinh_anh : asset('storage/'.$product->hinh_anh)) : 'https://via.placeholder.com/40x60' }}" 
                         style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px;">
                </td>
                <td style="font-weight: 600;">{{ $product->ten_sach }}</td>
                <td>{{ number_format($product->gia, 0) }}đ</td>
                <td>{{ $product->tacGias->pluck('ten_tac_gia')->implode(', ') }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.products.edit', ['id' => $product->id, 'page' => $products->currentPage()]) }}" style="color: #4f46e5; text-decoration: none;">Sửa</a>
                        <span style="color: #e2e8f0;">|</span>
                        <a href="#" style="color: #ef4444; text-decoration: none;">Xóa</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $products->onEachSide(1)->links() }}
    </div>
</div>
@endsection