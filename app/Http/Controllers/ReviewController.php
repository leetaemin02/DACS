<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sach_id' => 'required|exists:sach,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'binh_luan' => 'nullable|string|max:1000',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để đánh giá!',
            ], 401);
        }

        $userId = Auth::id();
        $sachId = $request->sach_id;

        // Optionally check if the user has already reviewed this book
        $existingReview = DanhGia::where('nguoi_dung_id', $userId)
            ->where('sach_id', $sachId)
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'so_sao' => $request->so_sao,
                'binh_luan' => $request->binh_luan,
            ]);
            $message = 'Đánh giá của bạn đã được cập nhật!';
        } else {
            DanhGia::create([
                'nguoi_dung_id' => $userId,
                'sach_id' => $sachId,
                'so_sao' => $request->so_sao,
                'binh_luan' => $request->binh_luan,
            ]);
            $message = 'Cảm ơn bạn đã đánh giá!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
