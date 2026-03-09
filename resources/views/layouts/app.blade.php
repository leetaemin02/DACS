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
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                BookStore
            </a>
            <div class="search-container">
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
                <a href="{{ route('home') }}" class="nav-link">Trang chủ</a>
                <a href="{{ route('books.categories') }}" class="nav-link">Thư viện</a>
                <a href="#" class="nav-link">Giỏ hàng (0)</a>
                <a href="{{ route('login') }}" class="nav-btn">Đăng Nhập</a>
                <a href="{{ route('register') }}" class="nav-link">Đăng Ký</a>
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
            <p>&copy; {{ date('Y') }} BookStore Project.</p>
        </div>
    </footer>

    <script>
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
</body>

</html>