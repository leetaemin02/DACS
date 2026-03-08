@extends('layouts.app')

@section('content')
<div class="container" style="padding-top: 4rem; padding-bottom: 4rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem;">Giỏ hàng của bạn</h1>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
            <!-- Cart Items -->
            <div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                            <th style="padding: 1rem 0;">Sản phẩm</th>
                            <th style="padding: 1rem 0;">Giá</th>
                            <th style="padding: 1rem 0;">Số lượng</th>
                            <th style="padding: 1rem 0;">Tổng</th>
                            <th style="padding: 1rem 0;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1.5rem 0; display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 80px; height: 110px; background: #f1f5f9; border-radius: 0.5rem; overflow: hidden; flex-shrink: 0;">
                                        @if($item->sach->hinh_anh)
                                            <img src="{{ Str::startsWith($item->sach->hinh_anh, ['http://', 'https://']) ? $item->sach->hinh_anh : asset('storage/' . $item->sach->hinh_anh) }}" 
                                                 alt="{{ $item->sach->ten_sach }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #64748b; font-size: 0.75rem;">No Image</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 1.125rem;">{{ $item->sach->ten_sach }}</div>
                                        <div style="font-size: 0.875rem; color: var(--text-secondary);">bởi {{ $item->sach->tac_gia }}</div>
                                    </div>
                                </td>
                                <td style="padding: 1.5rem 0;" class="item-price" data-price="{{ $item->sach->gia }}">{{ number_format($item->sach->gia, 3) }}đ</td>
                                <td style="padding: 1.5rem 0;">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="so_luong" value="{{ $item->so_luong }}" min="1" 
                                               class="cart-qty-input"
                                               style="width: 60px; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.25rem;">
                                    </form>
                                </td>
                                <td style="padding: 1.5rem 0; font-weight: 600;" class="item-line-total">{{ number_format($item->sach->gia * $item->so_luong, 3) }}đ</td>
                                <td style="padding: 1.5rem 0; text-align: right;">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary -->
            <div>
                <div style="background: #f8fafc; padding: 2rem; border-radius: 1rem; border: 1px solid var(--border-color);">
                    <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem;">Tổng đơn hàng</h2>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                        <span style="color: var(--text-secondary);">Tạm tính</span>
                        <span style="font-weight: 600;" id="cart-subtotal">{{ number_format($total, 3) }}đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
                        <span style="font-weight: 700; font-size: 1.25rem;">Tổng cộng</span>
                        <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary-color);" id="cart-total">{{ number_format($total, 3) }}đ</span>
                    </div>
                    <button class="nav-btn" style="width: 100%; padding: 1rem;">Tiến hành thanh toán</button>
                    <a href="{{ route('books.categories') }}" style="display: block; text-align: center; margin-top: 1rem; color: var(--text-secondary); text-decoration: none;">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 4rem 0;">
            <div style="margin-bottom: 1.5rem; color: #cbd5e1;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 2rem;">Giỏ hàng của bạn đang trống.</p>
            <a href="{{ route('books.categories') }}" class="nav-btn">Khám phá ngay</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.cart-qty-input');
    
    qtyInputs.forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const priceEl = row.querySelector('.item-price');
            const lineTotalEl = row.querySelector('.item-line-total');
            
            if (!priceEl || !lineTotalEl) return;
            
            const price = parseFloat(priceEl.dataset.price);
            const qty = parseInt(this.value) || 0;
            
            // Update line total
            const lineTotal = price * qty;
            lineTotalEl.textContent = formatCurrency(lineTotal);
            
            // Update grand total
            updateGrandTotal();
        });
    });

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const priceEl = row.querySelector('.item-price');
            const qtyInput = row.querySelector('.cart-qty-input');
            
            if (priceEl && qtyInput) {
                const price = parseFloat(priceEl.dataset.price);
                const qty = parseInt(qtyInput.value) || 0;
                grandTotal += price * qty;
            }
        });

        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        
        if (subtotalEl) subtotalEl.textContent = formatCurrency(grandTotal);
        if (totalEl) totalEl.textContent = formatCurrency(grandTotal);
    }

    function formatCurrency(amount) {
        return amount.toFixed(3) + 'đ';
    }
});
</script>
@endpush
