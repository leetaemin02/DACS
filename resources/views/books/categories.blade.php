@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thư viện</h1>
    <!-- Filter by category -->
    <div class="filter-mini-wrapper">
    <form method="GET" action="{{ route('books.categories') }}" class="filter-mini">
    <select name="price">
        <option value="">Lọc giá</option>
        <option value="low" {{ request('price') == 'low' ? 'selected' : '' }}>Dưới 50k</option>
        <option value="medium" {{ request('price') == 'medium' ? 'selected' : '' }}>50k - 100k</option>
        <option value="high" {{ request('price') == 'high' ? 'selected' : '' }}>Trên 100k</option>
    </select>
    <select name="sort">
        <option value="">Sắp xếp</option>
        <option value="bestseller" {{ request('sort') == 'bestseller' ? 'selected' : '' }}>Bán chạy</option>
        <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>Mới nhất</option>
    </select>
    <button type="submit">Lọc</button>
    </form>
    </div>

    <div class="book-grid">
        @foreach($books as $book)
        <div class="book-card">
            <div class="book-image">
                @if($book->hinh_anh)
                <img src="{{ Str::startsWith($book->hinh_anh, ['http://', 'https://']) ? $book->hinh_anh : asset('storage/' . $book->hinh_anh) }}"
                    alt="{{ $book->ten_sach }}"
                    style="width:100%; height:100%; object-fit:cover;">
                @else
                <div style="display:flex; align-items:center; justify-content:center; height:100%; background:#e2e8f0; color:#64748b;">No Image</div>
                @endif
            </div>
            <div class="book-content">
                <h3 class="book-title">{{ $book->ten_sach }}</h3>
                <p class="book-author">bởi {{ $book->tacGias->pluck('ten_tac_gia')->implode(', ') }}</p>
                <div class="book-price">
                    <span style="color: #ff0000">{{ number_format($book->gia,0) }}đ</span>
                    <button class="add-to-cart-btn" data-id="{{ $book->id }}" style="position: relative; z-index: 2;">Thêm vào giỏ hàng</button>
                </div>
            </div>
            <a href="{{ route('books.show', $book->id) }}" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:1;"></a>
        </div>
        @endforeach
    </div>
    <div class="pagination-container">
        {{ $books->onEachSide(1)->links() }}
    </div>
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
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
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