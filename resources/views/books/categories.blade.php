@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Categories</h1>
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
                        <p class="book-author">by {{ $book->tac_gia }}</p>
                        <div class="book-price">
                            <span>{{ number_format($book->gia, 3) }}đ</span>
                            <button class="add-to-cart-btn">View</button>
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
@endsection