@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Quản lý người dùng</h1>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tham gia</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td style="font-weight: 600;">{{ $user->ho_ten }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(strtolower($user->vai_tro) === 'admin')
                        <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; background: #fee2e2; color: #991b1b;">
                            Admin
                        </span>
                    @else
                        <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; background: #e0e7ff; color: #3730a3;">
                            Khách hàng
                        </span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('admin.users.edit', $user->id) }}" style="padding: 0.25rem 0.75rem; border-radius: 0.5rem; background: #3b82f6; color: white; text-decoration: none; font-size: 0.875rem;">Sửa</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá người dùng này?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 0.25rem 0.75rem; border-radius: 0.5rem; background: #ef4444; color: white; border: none; font-size: 0.875rem; cursor: pointer;">Xoá</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $users->onEachSide(1)->links() }}
    </div>
</div>
@endsection