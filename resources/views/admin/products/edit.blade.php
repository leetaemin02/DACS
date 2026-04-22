@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Chỉnh sửa sản phẩm</h1>
    <a href="{{ route('admin.products', ['page' => $currentPage]) }}" style="color: var(--secondary); text-decoration: none;">&larr; Quay lại danh sách</a>
</div>

<div class="table-card" style="max-width: 800px;">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        <input type="hidden" name="page" value="{{ $currentPage }}">
        <div style="display: grid; gap: 1.5rem;">
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tên sách</label>
                <input type="text" name="ten_sach" value="{{ $product->ten_sach }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Thể loại</label>
                <input type="text" name="the_loai" value="{{ $product->the_loai }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Giá (VND)</label>
                    <input type="number" name="gia" step="0.001" value="{{ $product->gia }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                </div>
                <div class="form-group">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Số lượng kho</label>
                    <input type="number" name="so_luong" value="{{ $product->so_luong }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                </div>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tác giả (Chọn nhiều)</label>
                <select name="authors[]" multiple style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; min-height: 120px;">
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ $product->tacGias->contains($author->id) ? 'selected' : '' }}>
                            {{ $author->ten_tac_gia }}
                        </option>
                    @endforeach
                </select>
                <small style="color: var(--secondary);">Giữ phím Ctrl để chọn nhiều tác giả</small>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Mô tả</label>
                <textarea name="mo_ta" rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">{{ $product->mo_ta }}</textarea>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">URL Hình ảnh</label>
                <input type="text" name="hinh_anh" value="{{ $product->hinh_anh }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
            </div>

            <button type="submit" style="background: var(--primary); color: white; padding: 1rem; border: none; border-radius: 0.5rem; font-weight: 700; cursor: pointer;">Cập nhật sản phẩm</button>
        </div>
    </form>
</div>
@endsection
