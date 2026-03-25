@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Sửa thông tin người dùng</h1>
    <div style="margin-left: auto;">
        <a href="{{ route('admin.users') }}" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: #94a3b8; color: white; text-decoration: none; font-weight: 500;">Quay lại</a>
    </div>
</div>

<div class="table-card" style="max-width: 600px; padding: 2rem;">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155;">Họ tên</label>
            <input type="text" value="{{ $user->ho_ten }}" disabled style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; background: #f1f5f9; color: #64748b; font-family: inherit;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155;">Email</label>
            <input type="email" value="{{ $user->email }}" disabled style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; background: #f1f5f9; color: #64748b; font-family: inherit;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155;">Vai trò</label>
            <select name="vai_tro" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-family: inherit; font-size: 1rem; background: white;">
                <option value="khach_hang" {{ strtolower($user->vai_tro) == 'khach_hang' ? 'selected' : '' }}>Khách hàng</option>
                <option value="admin" {{ strtolower($user->vai_tro) == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
            </select>
            @error('vai_tro')
                <span style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem; background: #3b82f6; color: white; border: none; font-size: 1rem; font-weight: 600; cursor: pointer; width: 100%;">Lưu thay đổi</button>
    </form>
</div>
@endsection
