<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BookStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #64748b;
            --bg: #f8fafc;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            margin: 0;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #1e293b;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 2rem 0;
        }

        .sidebar-brand {
            padding: 0 2rem;
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: #818cf8;
        }

        .nav-items {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            padding: 1rem 2rem;
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: 0.2s;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--primary);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-label { color: var(--secondary); font-size: 0.875rem; margin-bottom: 0.5rem; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: #1e293b; }

        .table-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th { text-align: left; padding: 1rem; border-bottom: 2px solid #f1f5f9; color: var(--secondary); }
td { padding: 1rem; border-bottom: 1px solid #f1f5f9; }

        .logout-btn {
            margin-top: auto;
            border: none;
            background: none;
            color: #f87171;
            padding: 1rem 2rem;
            cursor: pointer;
            text-align: left;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Pagination Styles */
        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            gap: 0.5rem;
            align-items: center;
        }

        .pagination .page-item {
            display: inline-block;
        }

        .pagination .page-link,
        .pagination .page-item span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            text-decoration: none;
            color: var(--secondary);
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: 0.2s;
        }

        .pagination .page-link:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #f5f3ff;
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link,
        .pagination .page-item.active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .pagination .page-item.disabled .page-link,
        .pagination .page-item.disabled span {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            transform: none;
        }

        /* Hide "Showing X to Y of Z results" text from Laravel default pagination */
        .pagination-container nav > div:first-child {
            display: none !important;
        }
        .pagination-container nav p {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">BookStore Admin</div>
        <div class="nav-items">
            <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="nav-item">Quản lý người dùng</a>
            <a href="{{ route('admin.products') }}" class="nav-item">Quản lý sản phẩm</a>
            <a href="{{ route('admin.orders') }}" class="nav-item">Quản lý đơn hàng</a>
            <a href="{{ route('admin.reviews') }}" class="nav-item">Quản lý đánh giá</a>
<div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 0.5rem 0;"></div>
            <a href="{{ url('/') }}" class="nav-item" style="color: #4ade80;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Thoát ra trang chủ
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div id="global-success-alert" style="background: #dcfce3; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transition: opacity 0.5s ease;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span style="font-weight: 500;">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="document.getElementById('global-success-alert').style.display='none'" style="background: transparent; border: none; color: #166534; font-size: 1.5rem; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
            </div>
            <script>
                setTimeout(function() {
                    var alert = document.getElementById('global-success-alert');
                    if(alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.style.display = 'none', 500);
                    }
                }, 10000);
            </script>
        @endif

        @if(session('error'))
            <div id="global-error-alert" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transition: opacity 0.5s ease;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
<svg width="20" height="20" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span style="font-weight: 500;">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="document.getElementById('global-error-alert').style.display='none'" style="background: transparent; border: none; color: #991b1b; font-size: 1.5rem; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
            </div>
            <script>
                setTimeout(function() {
                    var alert = document.getElementById('global-error-alert');
                    if(alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.style.display = 'none', 500);
                    }
                }, 10000);
            </script>
        @endif

        @yield('admin_content')
    </div>
</body>
</html>