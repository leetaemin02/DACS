@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Dashboard Tổng quan</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Tổng doanh thu</div>
        <div class="stat-value">{{ number_format($totalRevenue, 0) }}đ</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Tổng đơn hàng</div>
        <div class="stat-value">{{ $totalOrders }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Người dùng</div>
        <div class="stat-value">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sản phẩm</div>
        <div class="stat-value">{{ $totalProducts }}</div>
    </div>
</div>

<div class="table-card">
    <h3 style="margin-bottom:1rem">Doanh thu theo tháng</h3>
    <table style="width:100%">
        <thead>
            <tr>
                <th>Tháng</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyRevenue as $item)
            <tr>
                <td>Tháng {{ $item->month }}</td>
                <td>{{ number_format($item->total, 0) }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
