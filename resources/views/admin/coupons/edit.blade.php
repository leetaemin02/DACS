@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Sửa mã giảm giá</h1>
</div>

<div class="table-card" style="max-width: 700px;">
    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf

        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.9rem;">Mã code <span style="color: #ef4444;">*</span></label>
            <input type="text" name="ma_code" value="{{ old('ma_code', $coupon->ma_code) }}" required
                style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.65rem; font-size: 0.95rem; outline: none; transition: 0.2s; text-transform: uppercase; font-family: monospace; letter-spacing: 0.05em; box-sizing: border-box;"
                onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
            @error('ma_code') <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.9rem;">Giảm tiền (đ)</label>
                <input type="number" name="so_tien_giam" value="{{ old('so_tien_giam', $coupon->so_tien_giam) }}" min="0" step="1000"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.65rem; font-size: 0.95rem; outline: none; transition: 0.2s; box-sizing: border-box;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
                @error('so_tien_giam') <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.9rem;">Giảm phần trăm (%)</label>
                <input type="number" name="phan_tram_giam" value="{{ old('phan_tram_giam', $coupon->phan_tram_giam) }}" min="0" max="100"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.65rem; font-size: 0.95rem; outline: none; transition: 0.2s; box-sizing: border-box;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
                @error('phan_tram_giam') <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.9rem;">Số lượng <span style="color: #ef4444;">*</span></label>
                <input type="number" name="so_luong" value="{{ old('so_luong', $coupon->so_luong) }}" required min="0"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.65rem; font-size: 0.95rem; outline: none; transition: 0.2s; box-sizing: border-box;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
                @error('so_luong') <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155; font-size: 0.9rem;">Ngày hết hạn</label>
                <input type="date" name="ngay_het_han" value="{{ old('ngay_het_han', $coupon->ngay_het_han) }}"
                    style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 0.65rem; font-size: 0.95rem; outline: none; transition: 0.2s; box-sizing: border-box;"
                    onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
                @error('ngay_het_han') <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Usage stats --}}
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.65rem; padding: 1rem 1.25rem; margin-bottom: 1.5rem;">
            <div style="font-weight: 600; font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">📊 Thống kê sử dụng</div>
            <div style="font-size: 0.9rem; color: #334155;">Đã được sử dụng trong <strong>{{ $coupon->donHangs()->count() }}</strong> đơn hàng</div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="background: var(--primary); color: white; padding: 0.75rem 2rem; border: none; border-radius: 0.65rem; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s;"
                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Cập nhật</button>
            <a href="{{ route('admin.coupons') }}" style="padding: 0.75rem 1.5rem; border: 1.5px solid #e2e8f0; border-radius: 0.65rem; color: #64748b; text-decoration: none; font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center;">Hủy bỏ</a>
        </div>
    </form>
</div>
@endsection
