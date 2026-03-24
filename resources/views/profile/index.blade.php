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
                        <a href="{{ route('profile.index') }}" style="display: flex; align-items: center; gap: 0.75rem; color: var(--primary-color); font-weight: 600;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Thông tin cá nhân
                        </a>
                    </li>
                    <li style="margin-bottom: 0.75rem;">
                        <a href="{{ route('profile.orders') }}" style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); transition: var(--transition);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            Đơn hàng của tôi
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div style="flex: 1; display: flex; flex-direction: column; gap: 2rem;">
            @if(session('success'))
                <div style="background: var(--color-success); color: white; padding: 1rem; border-radius: 8px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-card">
                <h2 class="profile-title">Thông tin tài khoản</h2>
                
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

            <div class="profile-card">
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
</div>
@endsection