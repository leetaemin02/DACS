@extends('layouts.app')

@section('content')
<div class="container">
    <section class="hero-section mb-8">
        <div class="hero-overlay"></div>
        <div class="hero-content animate-fade-in">
            <h1>Khám phá cuốn sách yêu thích của bạn</h1>
            <p>Khám phá hàng ngàn đầu sách thuộc nhiều thể loại từ tiểu thuyết đến công nghệ. Hãy sẵn sàng cho một hành trình mới với mỗi trang sách.</p>
            <a href="/categories" class="nav-btn glow-btn">Xem bộ sưu tập</a>
        </div>
    </section>

    <!-- Thêm phần tính năng nổi bật -->
    <section class="features-section mb-8">
        <div class="feature-box">
            <div class="feature-icon">🚚</div>
            <h3>Giao hàng nhanh chóng</h3>
            <p>Nhận sách tận tay chỉ trong 24h trên toàn quốc.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon">⭐</div>
            <h3>Sách bản quyền 100%</h3>
            <p>Cam kết chất lượng, bảo hành đổi trả miễn phí.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon">🎁</div>
            <h3>Nhiều ưu đãi hấp dẫn</h3>
            <p>Mã giảm giá và quà tặng kèm theo mỗi ngày.</p>
        </div>
    </section>

    {{-- ==================== ĐANG THỊNH HÀNH ==================== --}}
    <section id="trending" class="trending-section">
        <div class="trending-inner">
            <div class="trending-header">
                <div class="trending-header-left">
                    <h2>Đang Thịnh Hành</h2>
                </div>
                <p class="trending-subtitle">Những cuốn sách được ưa chuộng nhất</p>
            </div>

            <div class="trending-grid">
                @foreach($trendingBooks as $book)
                <div class="trending-card">
                    <div class="trending-card-image">
                        @if($book->hinh_anh)
                        <img src="{{ Str::startsWith($book->hinh_anh, ['http://', 'https://']) ? $book->hinh_anh : asset('storage/' . $book->hinh_anh) }}"
                            alt="{{ $book->ten_sach }}">
                        @else
                        <div class="trending-no-image">No Image</div>
                        @endif
                        <div class="trending-badge">#{{ $loop->iteration }} Bán chạy</div>
                    </div>
                    <div class="trending-card-info">
                        <div class="trending-card-category">{{ $book->the_loai }}</div>
                        <h3 class="trending-card-title">{{ $book->ten_sach }}</h3>
                        <p class="trending-card-author">{{ $book->tacGias->pluck('ten_tac_gia')->implode(', ') }}</p>
                        <div class="rating-stars" style="margin-bottom: 0.25rem; gap: 2px;">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="star-icon" viewBox="0 0 24 24" fill="{{ $i <= round($book->average_rating) ? '#fbbf24' : '#d1d5db' }}" style="width: 14px; height: 14px;">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            @endfor
                            <span style="font-size: 0.75rem; color: rgba(255,255,255,0.7); margin-left: 4px;">({{ $book->review_count }})</span>
                        </div>
                        <div class="trending-card-price">
                            <span class="trending-price-value">{{ number_format($book->gia, 0) }}đ</span>
                            <button class="add-to-cart-btn trending-cart-btn" data-id="{{ $book->id }}">Thêm vào giỏ</button>
                        </div>
                    </div>
                    <a href="{{ route('books.show', $book->id) }}" class="trending-card-link"></a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== KHÁCH HÀNG YÊU THÍCH ==================== --}}
    <section id="favorites" class="favorites-section">
        <div class="favorites-header">
            <h2>Khách Hàng Yêu Thích</h2>
            <p class="favorites-subtitle">Những cuốn sách được đánh giá cao nhất bởi độc giả</p>
        </div>

        <div class="favorites-grid">
            @foreach($favoriteBooks as $book)
            <div class="favorite-card">
                <div class="favorite-card-rank">
                    <span class="favorite-rank-number">{{ $loop->iteration }}</span>
                </div>
                <div class="favorite-card-image">
                    @if($book->hinh_anh)
                    <img src="{{ Str::startsWith($book->hinh_anh, ['http://', 'https://']) ? $book->hinh_anh : asset('storage/' . $book->hinh_anh) }}"
                        alt="{{ $book->ten_sach }}">
                    @else
                    <div class="favorite-no-image">No Image</div>
                    @endif
                </div>
                <div class="favorite-card-body">
                    <div class="favorite-card-category">{{ $book->the_loai }}</div>
                    <h3 class="favorite-card-title">{{ $book->ten_sach }}</h3>
                    <p class="favorite-card-author">{{ $book->tacGias->pluck('ten_tac_gia')->implode(', ') }}</p>
                    <div class="favorite-rating-row">
                        <div class="favorite-rating-badge">
                            <svg viewBox="0 0 24 24" fill="#fbbf24" width="16" height="16">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <span>{{ number_format($book->average_rating, 1) }}</span>
                        </div>
                        <span class="favorite-review-count">({{ $book->review_count }} đánh giá)</span>
                    </div>
                </div>
                <div class="favorite-card-action">
                    <span class="favorite-price">{{ number_format($book->gia, 0) }}đ</span>
                    <button class="add-to-cart-btn favorite-cart-btn" data-id="{{ $book->id }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Mua ngay
                    </button>
                </div>
                <a href="{{ route('books.show', $book->id) }}" class="favorite-card-link"></a>
            </div>
            @endforeach
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            @if(!Auth::check())
                window.location.href = "{{ route('login') }}";
                return;
            @endif

            const bookId = this.getAttribute('data-id');
            const originalText = this.innerHTML;
            
            this.disabled = true;
            this.innerHTML = '...';

            fetch('{{ route("api.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sach_id: bookId,
                    so_luong: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showToast(data.message);
                    // Update cart count in header
                    const cartLink = document.querySelector('.nav-link[href*="cart"]');
                    if(cartLink) {
                        cartLink.innerHTML = `
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Cart (${data.count})
                        `;
                    }
                } else {
                    showToast(data.message || 'Có lỗi xảy ra!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Không thể thêm vào giỏ hàng!', 'error');
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = originalText;
            });
        });
    });
});
</script>
@endpush
@endsection