@extends('layouts.admin')

@section('admin_content')
<div class="header">
    <h1>Quản lý đánh giá</h1>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Đánh giá</th>
                <th>Bình luận & Phản hồi</th>
                <th>Ngày đánh giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>#{{ $review->id }}</td>
                <td style="font-weight: 600;">
                    {{ $review->nguoiDung->ho_ten ?? 'N/A' }}
                </td>
                <td>
                    {{ $review->sach->ten_sach ?? 'N/A' }}
                </td>
                <td>
                    <span style="color: #fbbf24;">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->so_sao)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                    ({{ $review->so_sao }})
                </td>
                <td style="max-width: 300px;">
                    <div style="margin-bottom: 0.5rem; font-style: italic; color: #475569;">
                        "{{ $review->binh_luan }}"
                    </div>
                    @if($review->phan_hoi_admin)
                        <div style="background: #e0e7ff; padding: 0.5rem; border-radius: 0.5rem; font-size: 0.875rem; color: #1e3a8a; margin-bottom: 0.5rem;">
                            <span style="font-weight: 600; color: #4338ca;">Admin trl:</span> 
                            {{ $review->phan_hoi_admin }}
                        </div>
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                            <!-- Nút sửa (dùng JS để toggle form) -->
                            <button onclick="document.getElementById('edit-reply-{{ $review->id }}').style.display = 'flex'" style="padding: 0.25rem 0.5rem; background: #eab308; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Sửa</button>
                            
                            <!-- Form xoá phản hồi -->
                            <form action="{{ route('admin.reviews.reply.delete', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phản hồi này?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 0.25rem 0.5rem; background: #ef4444; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Xóa</button>
                            </form>
                        </div>

                        <!-- Form sửa phản hồi (ẩn mặc định) -->
                        <form id="edit-reply-{{ $review->id }}" action="{{ route('admin.reviews.reply', $review->id) }}" method="POST" style="display: none; gap: 0.5rem; margin-top: 0.5rem;">
                            @csrf
                            <input type="text" name="phan_hoi_admin" value="{{ $review->phan_hoi_admin }}" required style="flex: 1; padding: 0.25rem 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; font-size: 0.875rem;">
                            <button type="submit" style="padding: 0.25rem 0.5rem; background: #10b981; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Lưu</button>
                            <button type="button" onclick="document.getElementById('edit-reply-{{ $review->id }}').style.display = 'none'" style="padding: 0.25rem 0.5rem; background: #64748b; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Hủy</button>
                        </form>
                    @else
                        <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST" style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                            @csrf
                            <input type="text" name="phan_hoi_admin" placeholder="Nhập phản hồi..." required style="flex: 1; padding: 0.25rem 0.5rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; font-size: 0.875rem;">
                            <button type="submit" style="padding: 0.25rem 0.5rem; background: #3b82f6; color: white; border: none; border-radius: 0.25rem; cursor: pointer; font-size: 0.875rem;">Gửi</button>
                        </form>
                    @endif
                </td>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá đánh giá này?');" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 0.25rem 0.75rem; border-radius: 0.5rem; background: #ef4444; color: white; border: none; font-size: 0.875rem; cursor: pointer;">Xoá</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-container">
        {{ $reviews->onEachSide(1)->links() }}
    </div>
</div>
@endsection
