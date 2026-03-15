<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BookStore') }}</title>
    
    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.google.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ asset('img/book_logo_purple.png') }}" alt="Logo">
                BookStore
            </a>
            <!-- Search -->
            <div class= "search-container">
                <form action="{{ route('books.search') }}" method="GET" autocomplete="off">
                    <input type="text" name="query" id="live-search-input" placeholder="Tìm kiếm sách bạn yêu thích..." class="search-input" value="{{ request('query') }}">
                    <button type="submit" class="search-button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
                <div id="search-dropdown" class="search-dropdown"></div>
            </div>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
                <a href="{{ route('books.categories') }}" class="nav-link">Categories</a>
                <a href="{{ route('cart.index') }}" class="nav-link" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Cart ({{ $cartCount ?? 0 }})
                </a>
                @guest
                    <a href="{{ route('login') }}" class="nav-btn">Đăng Nhập</a>
                @endguest

                @auth
                    <div class="user-dropdown">
                        <div class="nav-link user-dropdown-toggle">
                            Xin chào, <b>{{ Auth::user()->ho_ten }}</b> 
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                        
                        <div class="user-dropdown-menu">
                            <a href="#" class="user-dropdown-item">Thông tin tài khoản</a>
                            <a href="#" class="user-dropdown-item">Đơn hàng của tôi</a>
                            
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="user-dropdown-item text-danger">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="main-footer">
    <div class="container footer-grid">
        <div class="footer-col">
            <h4>Về BookStore</h4>
            <p>Hệ thống bán sách trực tuyến hàng đầu, cung cấp hàng ngàn đầu sách đa dạng thể loại.</p>
        </div>
        <div class="footer-col">
            <h4>Liên kết nhanh</h4>
            <ul>
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('books.categories') }}">Danh mục</a></li>
                <li><a href="#">Điều khoản dịch vụ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Liên hệ</h4>
            <p>📧 Email: support@bookstore.vn</p>
            <p>📞 Hotline: 1900 1234</p>
            <p>📍 Địa chỉ: TP. Hồ Chí Minh, Việt Nam</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} BookStore Project. Designed for IT Subject.</p>
    </div>
    </footer>

    <div id="toast-container" style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.75rem;"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? '#10b981' : '#ef4444';
            
            toast.style.cssText = `
                background: ${bgColor};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 300px;
                animation: slideIn 0.3s ease-out forwards;
            `;
            
            toast.innerHTML = `
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span style="font-weight: 500;">${message}</span>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-in forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('live-search-input');
            const dropdown = document.getElementById('search-dropdown');
            let timeout = null;

            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    dropdown.classList.remove('active');
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`{{ route('books.search') }}?query=${query}&ajax=1`)
                        .then(response => response.json())
                        .then(books => {
                            if (books.length > 0) {
                                dropdown.innerHTML = books.map(book => `
                                    <a href="/books/${book.id}" class="search-item">
                                        <img src="${book.hinh_anh?.startsWith('http') ? book.hinh_anh : '/storage/' + book.hinh_anh}" class="search-item-img" onerror="this.src='https://via.placeholder.com/40x60?text=Book'">
                                        <div class="search-item-info">
                                            <div class="search-item-title">${book.ten_sach}</div>
                                            <div class="search-item-author">${book.tac_gia}</div>
                                        </div>
                                    </a>
                                `).join('');
                                dropdown.classList.add('active');
                            } else {
                                dropdown.innerHTML = '<div class="search-item"><div class="search-item-info"><div class="search-item-title">Không tìm thấy sách</div></div></div>';
                                dropdown.classList.add('active');
                            }
                        });
                }, 300);
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });

            // Re-show dropdown on click if it has content
            searchInput.addEventListener('click', function() {
                if (this.value.trim().length >= 2) {
                    dropdown.classList.add('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
