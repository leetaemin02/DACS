<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sach;
use App\Models\DonHang;
use App\Models\TacGia;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = DonHang::where('trang_thai', 'Đã giao hàng')->sum('tong_tien');
        $totalOrders = DonHang::count();
        $totalUsers = User::count();
        $totalProducts = Sach::count();

        // Thống kê doanh thu theo tháng (ví dụ)
        $monthlyRevenue = DonHang::where('trang_thai', 'Đã giao hàng')
            ->select(DB::raw('SUM(tong_tien) as total'), DB::raw('MONTH(created_at) as month'))
            ->groupBy('month')
            ->get();

        return view('admin.dashboard', compact('totalRevenue', 'totalOrders', 'totalUsers', 'totalProducts', 'monthlyRevenue'));
    }

    // --- Quản lý người dùng ---
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // --- Quản lý sản phẩm ---
    public function products()
    {
        $products = Sach::with('tacGias')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $authors = TacGia::orderBy('ten_tac_gia')->get();
        return view('admin.products.create', compact('authors'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'ten_sach' => 'required',
            'gia' => 'required|numeric',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:tac_gia,id'
        ]);

        $product = Sach::create($request->except('authors'));
        
        if ($request->has('authors')) {
            $product->tacGias()->sync($request->authors);
        }

        return redirect()->route('admin.products')->with('success', 'Thêm sản phẩm thành công');
    }

    public function editProduct(Request $request, $id)
    {
        $product = Sach::with('tacGias')->findOrFail($id);
        $authors = TacGia::orderBy('ten_tac_gia')->get();
        $currentPage = $request->input('page', 1);
        return view('admin.products.edit', compact('product', 'authors', 'currentPage'));
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'ten_sach' => 'required',
            'gia' => 'required|numeric',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:tac_gia,id'
        ]);

        $product = Sach::findOrFail($id);
        $product->update($request->except('authors'));

        if ($request->has('authors')) {
            $product->tacGias()->sync($request->authors);
        } else {
            $product->tacGias()->detach();
        }

        $currentPage = $request->input('page', 1);
        return redirect()->route('admin.products', ['page' => $currentPage])->with('success', 'Cập nhật sản phẩm thành công');
    }

    // --- Quản lý đơn hàng ---
    public function orders()
    {
        $orders = DonHang::with(['nguoiDung', 'thanhToan'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = DonHang::findOrFail($id);
        $order->update(['trang_thai' => $request->trang_thai]);
        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }
}
