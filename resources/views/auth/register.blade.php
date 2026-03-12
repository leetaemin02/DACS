@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Tạo tài khoản</h2>
            <p>Tham gia cộng đồng yêu sách BookStore ngay hôm nay</p>
        </div>

        @if ($errors->any())
            <div class="error-alert">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="ho_ten">Họ và tên</label>
                <input type="text" id="ho_ten" name="ho_ten" class="form-control" value="{{ old('ho_ten') }}" placeholder="Nguyễn Văn A" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="mat_khau">Mật khẩu</label>
                <input type="password" id="mat_khau" name="mat_khau" class="form-control" placeholder="Ít nhất 8 ký tự" required>
            </div>

            <div class="form-group">
                <label for="mat_khau_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="mat_khau_confirmation" name="mat_khau_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="auth-button">Đăng ký tài khoản</button>
        </form>

        <div class="auth-footer">
            Đã có tài khoản? <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection