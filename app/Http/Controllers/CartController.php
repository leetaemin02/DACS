<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\Sach;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [];
        $total = 0;

        if (Auth::check()) {
            $cartItems = GioHang::with('sach')->where('nguoi_dung_id', Auth::id())->get();
            foreach ($cartItems as $item) {
                $total += $item->sach->gia * $item->so_luong;
            }
        } else {
            $cart = session()->get('cart', []);
            $sachIds = array_keys($cart);
            $sachers = Sach::whereIn('id', $sachIds)->get()->keyBy('id');

            foreach ($cart as $id => $details) {
                if (isset($sachers[$id])) {
                    $cartItems[] = (object)[
                        'id' => $id,
                        'sach' => $sachers[$id],
                        'so_luong' => $details['so_luong']
                    ];
                    $total += $sachers[$id]->gia * $details['so_luong'];
                }
            }
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

        if (Auth::check()) {
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
        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$sach_id])) {
                $cart[$sach_id]['so_luong'] += $so_luong;
            } else {
                $cart[$sach_id] = [
                    'so_luong' => $so_luong
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Đã thêm sách vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'so_luong' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $cartItem = GioHang::where('nguoi_dung_id', Auth::id())->where('id', $id)->first();
            if ($cartItem) {
                $cartItem->so_luong = $request->so_luong;
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['so_luong'] = $request->so_luong;
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật!');
    }

    public function remove($id)
    {
        if (Auth::check()) {
            GioHang::where('nguoi_dung_id', Auth::id())->where('id', $id)->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

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

        if (Auth::check()) {
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
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$sach_id])) {
                $cart[$sach_id]['so_luong'] += $so_luong;
            } else {
                $cart[$sach_id] = ['so_luong' => $so_luong];
            }
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng!',
            'count' => Auth::check() ? GioHang::where('nguoi_dung_id', Auth::id())->sum('so_luong') : array_sum(array_column(session()->get('cart', []), 'so_luong'))
        ]);
    }

    public function apiRemove(Request $request, $id)
    {
        if (Auth::check()) {
            GioHang::where('nguoi_dung_id', Auth::id())->where('id', $id)->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng!'
        ]);
    }
}
