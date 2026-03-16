@extends('layouts.app')

@section('content')
<div class="container profile-container">
    <div class="profile-grid">
        
        <div class="profile-card">
            <h2 class="profile-title">Thông tin tài khoản</h2>
            
            @if(session('success'))
                <div style="background: var(--color-success); color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div class="profile-form-group">
                    <label>Email đăng nhập (Không thể thay đổi)</label>
                    <input type="email" value="{{ $user->email }}" disabled class="profile-input">
                </div>

                <div class="profile-form-group">
                    <label for="ho_ten">Họ và tên</label>
                    <input type="text" id="ho_ten" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required class="profile-input">
                    @error('ho_ten')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="so_dien_thoai">Số điện thoại</label>
                    <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}" class="profile-input">
                    @error('so_dien_thoai')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="dia_chi">Địa chỉ</label>
                    <input type="text" id="dia_chi" name="dia_chi" value="{{ old('dia_chi', $user->dia_chi) }}" class="profile-input">
                    @error('dia_chi')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="profile-submit">Lưu thay đổi</button>
            </form>
        </div>

        <div class="profile-card align-start">
            <h2 class="profile-title">Đổi mật khẩu</h2>
            
            <form action="{{ route('profile.change-password') }}" method="POST">
                @csrf
                <div class="profile-form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" id="current_password" name="current_password" required class="profile-input">
                    @error('current_password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
<label for="new_password">Mật khẩu mới</label>
                    <input type="password" id="new_password" name="new_password" required class="profile-input">
                    @error('new_password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="new_password_confirmation">Nhập lại mật khẩu mới</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required class="profile-input">
                </div>

                <button type="submit" class="profile-submit dark">Cập nhật mật khẩu</button>
            </form>
        </div>

    </div>
</div>
@endsection