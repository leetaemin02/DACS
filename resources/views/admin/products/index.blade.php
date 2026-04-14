@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Quản lý sản phẩm</h1>
    <a href="{{ route('admin.products.create') }}" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">+ Thêm sản phẩm</a>
</div>

<!-- Ô tìm kiếm sản phẩm -->
<div style="margin-bottom: 1.5rem;">
    <form action="{{ route('admin.products') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.3-4.3"></path>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên sách hoặc tác giả..."
                style="width: 100%; padding: 0.7rem 0.75rem 0.7rem 2.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; outline: none; transition: border-color 0.2s; background: white;"
                onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e2e8f0'">
        </div>
        <button type="submit" style="padding: 0.7rem 1.25rem; background: #4f46e5; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.9rem; transition: background 0.2s;"
            onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">Tìm kiếm</button>
        @if(request('search'))
        <a href="{{ route('admin.products') }}" style="padding: 0.7rem 1rem; background: #f1f5f9; color: #64748b; border-radius: 0.5rem; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: background 0.2s;"
            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Xóa lọc</a>
        @endif
    </form>
    @if(request('search'))
    <p style="margin-top: 0.5rem; color: #64748b; font-size: 0.85rem;">
        Kết quả tìm kiếm cho "<strong>{{ request('search') }}</strong>" — {{ $products->total() }} sản phẩm
    </p>
    @endif
</div>
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sách</th>
                <th>Giá</th>
                <th>Tồn kho</th>
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
                <td>{{ $product->so_luong }}</td>
                <td>{{ $product->tacGias->pluck('ten_tac_gia')->unique()->implode(', ') }}</td>
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