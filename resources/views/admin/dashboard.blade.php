@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Dashboard Tổng quan</h1>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Tổng doanh thu</div>
        <div class="stat-value" style="color: #10b981;">{{ number_format($totalRevenue, 0) }}đ</div>
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

{{-- FEATURE 4: New Orders Notification --}}
@if($newOrdersCount > 0)
<div style="background: linear-gradient(135deg, #fef3c7, #fde68a); border: 1px solid #f59e0b; border-radius: 1rem; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
    <div style="background: #f59e0b; color: white; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">🔔</div>
    <div>
        <div style="font-weight: 700; color: #92400e; font-size: 1rem;">Bạn có {{ $newOrdersCount }} đơn hàng mới đang chờ xử lý!</div>
        <div style="font-size: 0.875rem; color: #a16207; margin-top: 0.25rem;">Hãy kiểm tra và cập nhật trạng thái.</div>
    </div>
    <a href="{{ route('admin.orders') }}" style="margin-left: auto; background: #f59e0b; color: white; padding: 0.5rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; white-space: nowrap;">Xem ngay →</a>
</div>
@endif

{{-- FEATURE 2: Low Stock Alerts --}}
@if($lowStockBooks->count() > 0)
<div style="background: linear-gradient(135deg, #fee2e2, #fecaca); border: 1px solid #ef4444; border-radius: 1rem; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
        <span style="font-size: 1.25rem;">⚠️</span>
        <strong style="color: #991b1b; font-size: 1rem;">Cảnh báo tồn kho thấp (≤ 5 cuốn)</strong>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
        @foreach($lowStockBooks as $book)
        <span style="background: white; border: 1px solid #fca5a5; color: #dc2626; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
            {{ $book->ten_sach }}
            <span style="background: {{ $book->so_luong == 0 ? '#ef4444' : '#f59e0b' }}; color: white; padding: 0.1rem 0.4rem; border-radius: 0.25rem; font-size: 0.7rem; margin-left: 0.25rem;">{{ $book->so_luong == 0 ? 'Hết hàng' : $book->so_luong . ' cuốn' }}</span>
        </span>
        @endforeach
    </div>
</div>
@endif

{{-- FEATURE 1: Charts Row --}}
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    {{-- Revenue Bar Chart --}}
    <div class="table-card">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">📊 Biểu đồ doanh thu theo tháng ({{ date('Y') }})</h3>
        <canvas id="revenueChart" height="120"></canvas>
    </div>

    {{-- Order Status Pie Chart --}}
    <div class="table-card">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">📋 Trạng thái đơn hàng</h3>
        <canvas id="statusChart" height="200"></canvas>
    </div>
</div>

{{-- Top Books + Recent Orders Row --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    {{-- Top 5 Best Sellers --}}
    <div class="table-card">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">🏆 Top 5 sách bán chạy</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên sách</th>
                    <th style="text-align: right;">Đã bán</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topBooks as $index => $book)
                <tr>
                    <td>
                        <span style="
                            display: inline-flex; align-items: center; justify-content: center;
                            width: 28px; height: 28px; border-radius: 50%; font-size: 0.8rem; font-weight: 700;
                            {{ $index === 0 ? 'background: #fef3c7; color: #92400e;' : ($index === 1 ? 'background: #f1f5f9; color: #475569;' : ($index === 2 ? 'background: #fed7aa; color: #9a3412;' : 'background: #f8fafc; color: #64748b;')) }}
                        ">{{ $index + 1 }}</span>
                    </td>
                    <td style="font-weight: 500;">{{ Str::limit($book->ten_sach, 35) }}</td>
                    <td style="text-align: right; font-weight: 700; color: var(--primary);">{{ $book->total_sold ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- FEATURE 4: Recent Orders --}}
    <div class="table-card">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">🕐 Đơn hàng gần đây</h3>
        <table>
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td style="font-weight: 600;">#{{ $order->id }}</td>
                    <td>{{ $order->nguoiDung->ho_ten ?? 'N/A' }}</td>
                    <td style="font-weight: 600;">{{ number_format($order->tong_tien, 0) }}đ</td>
                    <td>
                        <span style="padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 700;
                            @if($order->trang_thai == 'Chờ xử lý' || $order->trang_thai == 'Chờ duyệt') background: #fef3c7; color: #92400e;
                            @elseif($order->trang_thai == 'Đã giao hàng') background: #dcfce7; color: #166534;
                            @elseif($order->trang_thai == 'Đã hủy') background: #fee2e2; color: #991b1b;
                            @else background: #e0e7ff; color: #3730a3; @endif">
                            {{ $order->trang_thai }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('admin.orders') }}" style="color: var(--primary); font-weight: 600; font-size: 0.875rem; text-decoration: none;">Xem tất cả đơn hàng →</a>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
// Revenue Bar Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Doanh thu (đ)',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(79, 70, 229, 0.7)',
            borderColor: '#4f46e5',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return new Intl.NumberFormat('vi-VN').format(context.raw) + 'đ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                        if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                        return value;
                    }
                },
                grid: { color: '#f1f5f9' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Order Status Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = {!! json_encode($orderStatusCounts) !!};
const statusLabels = Object.keys(statusData);
const statusValues = Object.values(statusData);
const statusColors = statusLabels.map(label => {
    switch(label) {
        case 'Chờ xử lý': case 'Chờ duyệt': return '#f59e0b';
        case 'Đang xử lý': return '#3b82f6';
        case 'Đang giao hàng': return '#8b5cf6';
        case 'Đã giao hàng': return '#10b981';
        case 'Đã hủy': return '#ef4444';
        default: return '#64748b';
    }
});

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusValues,
            backgroundColor: statusColors,
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    pointStyleWidth: 10,
                    font: { size: 12 }
                }
            }
        },
        cutout: '55%'
    }
});
</script>
@endsection
