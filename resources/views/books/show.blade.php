@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap');
    .book-detail-page {
        font-family: 'Manrope', sans-serif;
    }
    .book-detail-grid {
        display: grid;
        grid-template-columns: 4fr 8fr;
        gap: 4rem;
        margin-bottom: 4rem;
        align-items: start;
    }
    .book-detail-intro {
        background-color: #fbf9f4;
        border-radius: 1.5rem;
        padding: 3rem 4rem;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05), 0 10px 30px rgba(0,37,24,0.05);
        margin-bottom: 4rem;
        border: 1px solid rgba(0,37,24,0.05);
    }
    .book-serif-font {
        font-family: 'Noto Serif', serif;
    }
    .book-image-wrapper::before {
        content: '';
        position: absolute;
        top: -1rem; left: -1rem; right: -1rem; bottom: -1rem;
        background-color: #f5f3ee;
        border-radius: 0.75rem;
        z-index: -1;
        transform: rotate(1.5deg);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .btn-premium-cart {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #002518 0%, #003d2a 100%);
        color: #ffffff;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 10px 15px -3px rgba(0,37,24,0.2), 0 4px 6px -2px rgba(0,37,24,0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-premium-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(0,37,24,0.3), 0 8px 10px -6px rgba(0,37,24,0.1);
    }
    .btn-premium-input {
        width: 3.5rem;
        background: transparent;
        border: none;
        text-align: center;
        font-weight: 700;
        color: #002518;
        outline: none;
        font-size: 1rem;
    }
    @media (max-width: 768px) {
        .book-detail-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .book-detail-intro {
            padding: 2rem 1.5rem;
        }
    }
</style>

<div class="container book-detail-page" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="book-detail-grid">
        <!-- Book Image -->
        <div style="position: relative; z-index: 1; max-width: 320px;">
            <div class="book-image-wrapper" style="position: relative;">
                <div style="aspect-ratio: 2/3; overflow: hidden; border-radius: 0.5rem; box-shadow: 0px 30px 60px rgba(0,37,24,0.2); background-color: #e4e2dd;">
                    @if($book->hinh_anh)
                    <img src="{{ Str::startsWith($book->hinh_anh, ['http://', 'https://']) ? $book->hinh_anh : asset('storage/' . $book->hinh_anh) }}"
                        alt="{{ $book->ten_sach }}"
                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                    @else
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #64748b; font-weight: 600;">No Image Available</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Book Details -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem; padding-top: 0.5rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <span style="padding: 0.25rem 0.75rem; background-color: #a7f2cf; color: #00513a; border-radius: 9999px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; display: inline-flex; align-items: center; gap: 0.4rem;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path></svg>
                        Thể loại: {{ $book->the_loai }}
                    </span>
                    <div style="display: flex; align-items: center; gap: 0.25rem; color: #434749;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" {{ $book->so_luong > 0 ? 'stroke="#00513a"' : 'stroke="#ba1a1a"' }} stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            @if($book->so_luong > 0)
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            @else
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                            @endif
                        </svg>
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $book->so_luong > 0 ? '#00513a' : '#ba1a1a' }}">{{ $book->so_luong > 0 ? 'Còn hàng' : 'Hết hàng' }}</span>
                    </div>
                </div>
                <h1 class="book-serif-font" style="font-size: 2.5rem; font-weight: 700; color: #002518; line-height: 1.15; margin-bottom: 0.5rem; letter-spacing: -0.01em;">{{ $book->ten_sach }}</h1>
                <p class="book-serif-font" style="font-size: 1.25rem; color: #586768; font-style: italic;">bởi <span style="font-weight: 600; color: #002518;">{{ $book->tacGias->pluck('ten_tac_gia')->implode(', ') }}</span></p>
            </div>

            <div style="display: flex; align-items: baseline; gap: 1rem;">
                <span class="book-serif-font" style="font-size: 2rem; font-weight: 700; color: #002518;">{{ number_format($book->gia, 0) }}đ</span>
            </div>

            <form id="add-to-cart-form" style="padding-top: 1rem; border-top: 1px solid rgba(195,199,201,0.3); margin-top: 0.5rem;">
                @csrf
                <input type="hidden" name="sach_id" id="sach_id" value="{{ $book->id }}">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="display: flex; align-items: center; background-color: #e4e2dd; border-radius: 0.65rem; padding: 0.4rem; border: 1px solid rgba(195,199,201,0.5); box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                        <input type="number" name="so_luong" id="so_luong" min="1" value="1" class="btn-premium-input">
                    </div>
                    <button type="submit" class="btn-premium-cart">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Thêm vào giỏ hàng
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Giới Thiệu Sách Section -->
    <section class="book-detail-intro">
        <div style="max-width: 50rem; margin: 0 auto;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 1.25rem; margin-bottom: 2.5rem;">
                <div style="flex: 1; height: 1px; background: linear-gradient(90deg, transparent, rgba(195,199,201,0.5));"></div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #002518;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
                    </svg>
                    <h2 class="book-serif-font" style="font-size: 1.75rem; font-weight: 700; font-style: italic; letter-spacing: -0.01em; margin: 0;">Giới thiệu sách</h2>
                </div>
                <div style="flex: 1; height: 1px; background: linear-gradient(270deg, transparent, rgba(195,199,201,0.5));"></div>
            </div>
            
            <div class="book-serif-font" style="color: #434749; line-height: 1.8; font-size: 1.1rem; white-space: pre-line; text-align: justify;">
                <span class="book-serif-font" style="font-size: 3.5rem; font-weight: 700; color: #002518; float: left; line-height: 0.8; margin-right: 0.5rem; margin-top: 0.25rem;">{{ Str::substr(trim($book->mo_ta ?? 'Chưa có thông tin'), 0, 1) }}</span>{{ Str::substr(trim($book->mo_ta ?? 'Chưa có thông tin'), 1) }}
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews-section">
        <div class="reviews-header">
            <h2 style="font-size: 2rem;">Đánh giá của người mua</h2>
            <div class="reviews-stats">
                @php
                    $avgRating = $book->danhGias->avg('so_sao') ?: 0;
                    $reviewCount = $book->danhGias->count();
                @endphp
                <div class="average-rating">
                    <div class="rating-number">{{ number_format($avgRating, 1) }}</div>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="star-icon" viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? '#fbbf24' : '#d1d5db' }}">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="rating-count">{{ $reviewCount }} đánh giá</div>
                </div>
            </div>
        </div>

        @auth
            <div class="review-form-card">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Viết đánh giá của bạn</h3>
                <form id="review-form">
                    @csrf
                    <input type="hidden" name="sach_id" value="{{ $book->id }}">
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Số sao</label>
                        <div class="star-rating-input">
                            <input type="radio" id="star5" name="so_sao" value="5" required/><label for="star5" title="5 stars">★</label>
                            <input type="radio" id="star4" name="so_sao" value="4"/><label for="star4" title="4 stars">★</label>
                            <input type="radio" id="star3" name="so_sao" value="3"/><label for="star3" title="3 stars">★</label>
                            <input type="radio" id="star2" name="so_sao" value="2"/><label for="star2" title="2 stars">★</label>
                            <input type="radio" id="star1" name="so_sao" value="1"/><label for="star1" title="1 star">★</label>
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="binh_luan" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bình luận</label>
<textarea name="binh_luan" id="binh_luan" rows="4" style="width: 100%; padding: 1rem; border: 1px solid var(--border-color); border-radius: var(--radius); font-size: 1rem; resize: vertical;" placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này..."></textarea>
                    </div>

                    <button type="submit" class="nav-btn" style="min-width: 160px;">Gửi đánh giá</button>
                </form>
            </div>
        @else
            <div style="background: #f8fafc; padding: 2rem; border-radius: var(--radius); text-align: center; margin-bottom: 3rem; border: 2px dashed var(--border-color);">
                <p style="color: var(--text-secondary); margin-bottom: 1rem;">Bạn cần đăng nhập để gửi đánh giá.</p>
                <a href="{{ route('login') }}" class="nav-link" style="color: var(--primary-color); font-weight: 700;">Đăng nhập ngay &rarr;</a>
            </div>
        @endauth

        <div class="reviews-list">
            @forelse($book->danhGias->sortByDesc('created_at') as $review)
                <div class="review-item">
                    <div class="review-user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr($review->nguoiDung->ho_ten ?? 'U', 0, 1)) }}
                        </div>
                        <div class="review-meta">
                            <h4>{{ $review->nguoiDung->ho_ten ?? 'Người dùng' }}</h4>
                            <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div style="margin-left: auto;">
                            <div class="rating-stars" style="margin-top: 0;">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="star-icon" viewBox="0 0 24 24" fill="{{ $i <= $review->so_sao ? '#fbbf24' : '#d1d5db' }}">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @if($review->binh_luan)
                        <div class="review-comment-text">
                            {{ $review->binh_luan }}
                        </div>
                    @endif
                    
                    @if($review->phan_hoi_admin)
                        <div class="admin-reply" style="margin-top: 1rem; padding: 1rem; background-color: #f8fafc; border-left: 4px solid var(--primary-color); border-radius: 0.5rem;">
                            <h5 style="margin-bottom: 0.5rem; color: var(--text-color); font-size: 0.9rem; font-weight: 600;">Phản hồi từ người bán:</h5>
<p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5;">{{ $review->phan_hoi_admin }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                    Chưa có đánh giá nào cho cuốn sách này. Hãy là người đầu tiên đánh giá!
                </div>
            @endforelse
        </div>
    </section>

    <!-- Related Books -->
    @if($relatedBooks->count() > 0)
    <section style="margin-top: 4rem;">
        <h2 class="mb-4" style="font-size: 2rem;">Bạn cũng có thể thích</h2>
        <div class="book-grid">
            @foreach($relatedBooks as $related)
            <div class="book-card">
                <div class="book-image">
                    @if($related->hinh_anh)
                    <img src="{{ Str::startsWith($related->hinh_anh, ['http://', 'https://']) ? $related->hinh_anh : asset('storage/' . $related->hinh_anh) }}"
                        alt="{{ $related->ten_sach }}"
                        style="width:100%; height:100%; object-fit:cover;">
                    @else
                    <div style="display:flex; align-items:center; justify-content:center; height:100%; background:#e2e8f0; color:#64748b;">No Image</div>
                    @endif
                </div>
                <div class="book-content">
                    <div class="book-category">Gợi ý cho bạn</div>
                    <h3 class="book-title">{{ $related->ten_sach }}</h3>
                    <p class="book-author">{{ $related->tacGias->pluck('ten_tac_gia')->implode(', ') }}</p>
                    
                    <div class="rating-stars" style="margin-bottom: 0.5rem; gap: 2px;">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="star-icon" viewBox="0 0 24 24" fill="{{ $i <= round($related->average_rating) ? '#fbbf24' : '#d1d5db' }}" style="width: 14px; height: 14px;">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        @endfor
                        <span style="font-size: 0.75rem; color: var(--text-secondary); margin-left: 4px;">({{ $related->review_count }})</span>
                    </div>

                    <div class="book-price">
                        <span style="color: #ff0000">{{ number_format($related->gia,0) }}đ</span>
                        <button class="add-to-cart-btn" data-id="{{ $related->id }}" style="position: relative; z-index: 2;">Thêm vào giỏ hàng</button>
                    </div>
                </div>
                <a href="{{ route('books.show', $related->id) }}" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:1;"></a>
            </div>
            @endforeach
        </div>
    </section>
@endif
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle main form
    const mainForm = document.getElementById('add-to-cart-form');
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            @if(!Auth::check())
                window.location.href = "{{ route('login') }}";
                return;
            @endif

            const sach_id = document.getElementById('sach_id').value;
            const so_luong = document.getElementById('so_luong').value;
            const btn = this.querySelector('button');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = 'Đang thêm...';

            addToCartAjax(sach_id, so_luong, btn, originalText);
        });
    }

    // Handle related books
    const relatedBtns = document.querySelectorAll('.add-to-cart-btn[data-id]');
    relatedBtns.forEach(btn => {
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

            addToCartAjax(bookId, 1, this, originalText);
        });
    });

    function addToCartAjax(sach_id, so_luong, btn, originalText) {
        fetch('{{ route("api.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sach_id: sach_id,
                so_luong: so_luong
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showToast(data.message);
                const cartBadge = document.querySelector('.nav-link[href*="cart"]');
                if(cartBadge) {
                    cartBadge.innerHTML = `
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
showToast('Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    // Handle review form
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button');
            const originalText = btn.innerHTML;
            
            const formData = new FormData(this);
            const data = {
                sach_id: formData.get('sach_id'),
                so_sao: formData.get('so_sao'),
                binh_luan: formData.get('binh_luan')
            };

            btn.disabled = true;
            btn.innerHTML = 'Đang gửi...';

            fetch('{{ route("api.reviews.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    showToast(res.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(res.message || 'Có lỗi xảy ra!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra, vui lòng thử lại!', 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }
});
</script>
@endpush
@endsection