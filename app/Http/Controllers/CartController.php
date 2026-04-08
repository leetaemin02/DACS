<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\Sach;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ThanhToan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getCartData()
    {
        $cartItems = GioHang::with('sach')->where('nguoi_dung_id', Auth::id())->get();
        $total = $cartItems->sum(function ($item) {
            return $item->sach->gia * $item->so_luong;
        });
        return compact('cartItems', 'total');
    }

    public function index()
    {
        $cartItems = GioHang::with('sach.tacGias')->where('nguoi_dung_id', Auth::id())->get();
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->sach->gia * $item->so_luong;
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'sach_id' => 'required|exists:sach,id',
            'so_luong' => 'required|integer|min:1'
        ]);

        $sach_id = $request->sach_id;
        $so_luong = $request->so_luong;

        $cartItem = GioHang::where('nguoi_dung_id', Auth::id())
            ->where('sach_id', $sach_id)
            ->first();

        if ($cartItem) {
            $cartItem->so_luong += $so_luong;
            $cartItem->save();
        } else {
            GioHang::create([
                'nguoi_dung_id' => Auth::id(),
                'sach_id' => $sach_id,
                'so_luong' => $so_luong
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sách vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'so_luong' => 'required|integer|min:1'
        ]);

        $cartItem = GioHang::where('nguoi_dung_id', Auth::id())->where('id', $id)->first();
        if ($cartItem) {
            $cartItem->so_luong = $request->so_luong;
            $cartItem->save();
        }

        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật!');
    }

    public function remove($id)
    {
        GioHang::where('nguoi_dung_id', Auth::id())->where('id', $id)->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa sách khỏi giỏ hàng!');
    }

    // --- API Methods ---

    public function apiAdd(Request $request)
    {
        $request->validate([
            'sach_id' => 'required|exists:sach,id',
            'so_luong' => 'required|integer|min:1'
        ]);

        $sach_id = $request->sach_id;
        $so_luong = $request->so_luong;

        $cartItem = GioHang::where('nguoi_dung_id', Auth::id())
            ->where('sach_id', $sach_id)
            ->first();

        if ($cartItem) {
            $cartItem->so_luong += $so_luong;
            $cartItem->save();
        } else {
            GioHang::create([
                'nguoi_dung_id' => Auth::id(),
                'sach_id' => $sach_id,
                'so_luong' => $so_luong
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng!',
            'count' => GioHang::where('nguoi_dung_id', Auth::id())->sum('so_luong')
        ]);
    }

    public function apiRemove(Request $request, $id)
    {
        GioHang::where('nguoi_dung_id', Auth::id())->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng!'
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $code = trim($request->input('code'));
        $coupon = \App\Models\MaGiamGia::where('ma_code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }

        if ($coupon->so_luong <= 0) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        if ($coupon->ngay_het_han && \Carbon\Carbon::parse($coupon->ngay_het_han)->isPast()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn.']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã thành công!',
            'coupon' => [
                'id' => $coupon->id,
                'so_tien_giam' => $coupon->so_tien_giam,
                'phan_tram_giam' => $coupon->phan_tram_giam,
            ]
        ]);
    }

    public function checkout()
    {
        $data = $this->getCartData();

        if ($data['cartItems']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        return view('cart.checkout', $data);
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment' => 'required|in:cod,vnpay'
        ]);

        $data = $this->getCartData();
        
        if ($data['cartItems']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra tồn kho trước khi đặt hàng
        foreach ($data['cartItems'] as $item) {
            if ($item->sach->so_luong < $item->so_luong) {
                return redirect()->route('cart.index')->with('error', 'Sách "' . $item->sach->ten_sach . '" chỉ còn ' . $item->sach->so_luong . ' cuốn trong kho.');
            }
        }

        $dia_chi_day_du = $request->address;

        // Cập nhật thông tin người dùng nếu cần thiết
        $user = Auth::user();
        if (!$user->ho_ten || !$user->so_dien_thoai || !$user->dia_chi) {
            $user->update([
                'ho_ten' => $request->name,
                'so_dien_thoai' => $request->phone,
                'dia_chi' => $request->address,
            ]);
        }

        // --- Xử lý mã giảm giá ---
        $maGiamGiaId = $request->input('ma_giam_gia_id');
        $tongTien = $data['total'];

        if ($maGiamGiaId) {
            $coupon = \App\Models\MaGiamGia::find($maGiamGiaId);
            if ($coupon && $coupon->so_luong > 0 && (!$coupon->ngay_het_han || !\Carbon\Carbon::parse($coupon->ngay_het_han)->isPast())) {
                $discount = 0;
                if ($coupon->so_tien_giam !== null && $coupon->so_tien_giam > 0) {
                    $discount = $coupon->so_tien_giam;
                } elseif ($coupon->phan_tram_giam !== null && $coupon->phan_tram_giam > 0) {
                    $discount = $tongTien * ($coupon->phan_tram_giam / 100);
                }
                
                $tongTien -= $discount;
                if ($tongTien < 0) $tongTien = 0;
            } else {
                $maGiamGiaId = null; // Mã không hợp lệ
            }
        }

        DB::beginTransaction();
        try {
            $donHang = DonHang::create([
                'nguoi_dung_id' => $user->id,
                'tong_tien' => $tongTien,
                'dia_chi_giao_hang' => $dia_chi_day_du,
                'trang_thai' => 'Chờ xử lý',
                'ma_giam_gia_id' => $maGiamGiaId
            ]);

            if ($maGiamGiaId && isset($coupon)) {
                 $coupon->decrement('so_luong');
            }

            foreach ($data['cartItems'] as $item) {
                ChiTietDonHang::create([
                    'don_hang_id' => $donHang->id,
                    'sach_id' => $item->sach_id,
                    'so_luong' => $item->so_luong,
                    'don_gia' => $item->sach->gia
                ]);

                // Trừ số lượng tồn kho
                $item->sach->decrement('so_luong', $item->so_luong);
            }

            if ($request->payment == 'vnpay') {
                DB::commit();
                session(['vnpay_don_hang_id' => $donHang->id]);
                return redirect()->route('vnpay.payment');
            }

            // --- XỬ LÝ THANH TOÁN COD ---
            $thanhToan = ThanhToan::firstOrCreate(
                ['phuong_thuc' => DonHang::PAYMENT_COD],
                ['trang_thai' => 'Hoạt động']
            );

            $donHang->update([
                'thanh_toan_id' => $thanhToan->id,
                'trang_thai' => DonHang::STATUS_PENDING
            ]);

            // Xóa giỏ hàng sau khi đặt thành công
            GioHang::where('nguoi_dung_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('vnpay.success', ['id' => $donHang->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Lỗi khi đặt hàng: ' . $e->getMessage());
        }
    }
}
