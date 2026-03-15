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
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>#{{ $user->id }}</td>
                <td style="font-weight: 600;">{{ $user->ho_ten }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700; background: #f1f5f9; color: #475569;">
                        {{ $user->vai_tro ?? 'User' }}
                    </span>
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 1.5rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection
