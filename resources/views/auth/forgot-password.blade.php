@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Quên mật khẩu?</h2>
            <p>Nhập email của bạn để nhận link đặt lại mật khẩu.</p>
        </div>

        @if (session('status'))
            <div class="success-alert" style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-alert">
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
            </div>
            
            <button type="submit" class="auth-button">Gửi link đặt lại mật khẩu</button>
        </form>

        <div class="auth-footer">
            <a href="{{ route('login') }}" class="auth-link">Quay lại đăng nhập</a>
        </div>
    </div>
</div>
@endsection
