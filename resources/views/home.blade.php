@extends('layouts.app')

@section('content')
<div class="container">
    <section class="hero animate-fade-in">
        <h1>Khám phá cuốn sách yêu thích
            tiếp theo của bạn</h1>
        <p>Khám phá hàng ngàn đầu sách thuộc nhiều thể loại từ tiểu thuyết đến công nghệ. Hãy sẵn sàng cho một hành trình mới với mỗi trang sách.</p>
        <a href="#books" class="nav-btn">Xem bộ sưu tập</a>
    </section>

    <section id="books" class="mb-8">
        <h2 class="mb-4">Đang Thịnh Hành</h2>

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
                    <div class="book-category">Fiction</div>
                    <h3 class="book-title">{{ $book->ten_sach }}</h3>
                    <p class="book-author">{{ $book->tac_gia }}</p>

                    <div class="book-price">
                        <span>{{ number_format($book->gia, 3) }}đ</span>
                        <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                    </div>
                </div>
                <a href="{{ route('books.show', $book->id) }}" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:1;"></a>
            </div>
            @endforeach

            <!-- Placeholder items if empty (remove in production) -->
            @if($books->isEmpty())
            <div class="book-card">
                <div class="book-image">
                    <!-- Placeholder image -->
                    <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=800" style="width:100%; height:100%; object-fit:cover;" alt="Book">
                </div>
                <div class="book-content">
                    <div class="book-category">Bestseller</div>
                    <h3 class="book-title">The Art of Code</h3>
                    <p class="book-author">Alan Turing</p>

                    <div class="book-price">
                        <span>$29.99</span>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-image">
                    <!-- Placeholder image -->
                    <img src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=800" style="width:100%; height:100%; object-fit:cover;" alt="Book">
                </div>
                <div class="book-content">
                    <div class="book-category">New Arrival</div>
                    <h3 class="book-title">Silent Spring</h3>
                    <p class="book-author">Rachel Carson</p>

                    <div class="book-price">
                        <span>$15.50</span>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            <div class="book-card">
                <div class="book-image">
                    <!-- Placeholder image -->
                    <img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=800" style="width:100%; height:100%; object-fit:cover;" alt="Book">
                </div>
                <div class="book-content">
                    <div class="book-category">Classic</div>
                    <h3 class="book-title">1984</h3>
                    <p class="book-author">George Orwell</p>

                    <div class="book-price">
                        <span>$12.00</span>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection