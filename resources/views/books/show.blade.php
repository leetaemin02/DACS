@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 4rem; padding-bottom: 4rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-bottom: 4rem;">
        <!-- Book Image -->
        <div style="background: #f1f5f9; border-radius: 1rem; overflow: hidden; height: 500px;">
            @if($book->hinh_anh)
            <img src="{{ Str::startsWith($book->hinh_anh, ['http://', 'https://']) ? $book->hinh_anh : asset('storage/' . $book->hinh_anh) }}"
                alt="{{ $book->ten_sach }}"
                style="width: 100%; height: 100%; object-fit: cover;">
            @else
            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #64748b;">No Image Available</div>
            @endif
        </div>

        <!-- Book Details -->
        <div>
            <div style="font-size: 0.875rem; text-transform: uppercase; color: var(--primary-color); font-weight: 700; margin-bottom: 1rem;">
                Chi tiết
            </div>
            <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; line-height: 1.1;">{{ $book->ten_sach }}</h1>
            <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 2rem;">bởi {{ $book->tac_gia }}</p>

            <div style="font-size: 2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 2rem;">
                {{ number_format($book->gia, 3) }}đ
            </div>

            <p style="margin-bottom: 2rem; color: var(--text-secondary); line-height: 1.8;">
                {{ $book->mo_ta ?? 'No description available for this book.' }}
            </p>

            <form id="add-to-cart-form">
                @csrf
                <input type="hidden" name="sach_id" id="sach_id" value="{{ $book->id }}">
                <div style="display: flex; gap: 1rem; margin-bottom: 3rem;">
                    <input type="number" name="so_luong" id="so_luong" min="1" value="1" style="width: 80px; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius); font-size: 1rem;">
                    <button type="submit" class="nav-btn" style="flex: 1;">Thêm vào giỏ hàng</button>
                </div>
            </form>

            <div style="padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span style="color: var(--text-secondary); display: block; font-size: 0.875rem;">Thể loại</span>
                        <span style="font-weight: 600;">Viễn tưởng</span>
                    </div>
                    <div>
                        <span style="color: var(--text-secondary); display: block; font-size: 0.875rem;">Trạng thái</span>
                        <span style="font-weight: 600;">{{ $book->so_luong > 0 ? 'Còn hàng' : 'Hết hàng' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Books -->
    @if($relatedBooks->count() > 0)
    <section>
        <h2 class="mb-4">Bạn cũng có thể thích</h2>
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
                    <p class="book-author">{{ $related->tac_gia }}</p>

                    <div class="book-price">
                        <span>{{ number_format($related->gia, 3) }}đ</span>
                        <button class="add-to-cart-btn">Xem chi tiết</button>
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
document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const sach_id = document.getElementById('sach_id').value;
    const so_luong = document.getElementById('so_luong').value;
    const btn = this.querySelector('button');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = 'Đang thêm...';

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
            // Cập nhật số lượng trên header nếu có
            const cartBadge = document.querySelector('.nav-link[href*="cart"]');
            if(cartBadge) {
                cartBadge.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Cart (${data.count})
                `;
            }
        } else {
            showToast(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
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
</script>
@endpush
@endsection