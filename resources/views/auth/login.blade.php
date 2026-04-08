@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Chào mừng trở lại</h2>
            <p>Đăng nhập để tiếp tục mua sắm tại BookStore</p>
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

        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
            </div>
            
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label for="mat_khau">Mật khẩu</label>
                    
                </div>
                <input type="password" id="mat_khau" name="mat_khau" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="auth-button">Đăng nhập</button>
        </form>

        <div class="auth-footer">
            Chưa có tài khoản? <a href="{{ route('register') }}" class="auth-link">Đăng ký ngay</a>
        </div>
        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('password.request') }}" class="auth-link" style="font-size: 0.85rem;">Quên mật khẩu?</a>
        </div>
    </div>
</div>
@endsection