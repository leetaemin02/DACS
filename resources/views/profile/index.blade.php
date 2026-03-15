@extends('layouts.app')

@section('content')
<div class="container" style="padding: 2rem 0;">
    <div class="row" style="display: flex; gap: 2rem; max-width: 1000px; margin: 0 auto;">
        
        <div style="flex: 1; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">Thông tin tài khoản</h2>
            
            @if(session('success'))
                <div style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Email đăng nhập (Không thể thay đổi)</label>
                    <input type="email" value="{{ $user->email }}" disabled style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc; color: #94a3b8; outline: none; cursor: not-allowed;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="ho_ten" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Họ và tên</label>
                    <input type="text" id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; transition: border-color 0.2s;">
                    @error('ho_ten')
                        <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="so_dien_thoai" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Số điện thoại</label>
                    <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                    @error('so_dien_thoai')
                        <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="dia_chi" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Địa chỉ</label>
                    <input type="text" id="dia_chi" name="dia_chi" value="{{ old('dia_chi', $user->dia_chi) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                    @error('dia_chi')
                        <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" style="background: #6366f1; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; width: 100%;">Lưu thay đổi</button>
            </form>
        </div>

        <div style="flex: 1; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); align-self: flex-start;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem; color: #1e293b; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">Đổi mật khẩu</h2>
            
            <form action="{{ route('profile.change-password') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label for="current_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Mật khẩu hiện tại</label>
                    <input type="password" id="current_password" name="current_password" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                    @error('current_password')
                        <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="new_password" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Mật khẩu mới</label>
                    <input type="password" id="new_password" name="new_password" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                    @error('new_password')
                        <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="new_password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569;">Nhập lại mật khẩu mới</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; outline: none;">
                </div>

                <button type="submit" style="background: #1e293b; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; width: 100%;">Cập nhật mật khẩu</button>
            </form>
        </div>

    </div>
</div>
@endsection
