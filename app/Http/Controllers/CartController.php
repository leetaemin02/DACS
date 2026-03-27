<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\Sach;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use Illuminate\Support\Facades\Auth;

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

        $dia_chi_day_du = "Tên: " . $request->name . ", SĐT: " . $request->phone . ", Địa chỉ: " . $request->address;

        $donHang = DonHang::create([
            'nguoi_dung_id' => Auth::id(),
            'tong_tien' => $data['total'],
            'dia_chi_giao_hang' => $dia_chi_day_du,
            'trang_thai' => 'Chờ xử lý'
        ]);

        foreach ($data['cartItems'] as $item) {
            ChiTietDonHang::create([
                'don_hang_id' => $donHang->id,
                'sach_id' => $item->sach_id,
                'so_luong' => $item->so_luong,
                'don_gia' => $item->sach->gia
            ]);
        }

        if ($request->payment == 'vnpay') {
            // Lưu thông tin đơn hàng tạm vào session để VnPayController có thể lấy thông tin
            session(['vnpay_don_hang_id' => $donHang->id]);
            return redirect()->route('vnpay.payment');
        }

        // Nếu là COD thì xóa giỏ hàng
        GioHang::where('nguoi_dung_id', Auth::id())->delete();

        return redirect('/')->with('success', 'Đặt hàng thành công');
    }
}
