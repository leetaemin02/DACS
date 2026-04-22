<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sach;
use App\Models\DonHang;
use App\Models\TacGia;
use App\Models\DanhGia;
use App\Models\MaGiamGia;
use App\Models\ChiTietDonHang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        $totalRevenue = DonHang::where('trang_thai', 'Đã giao hàng')->sum('tong_tien');
        $totalOrders = DonHang::count();
        $totalUsers = User::count();
        $totalProducts = Sach::count();

        // --- FEATURE 1: Chart.js Data ---
        // Monthly revenue for the current year (12 months)
        $monthlyRevenue = DonHang::where('trang_thai', 'Đã giao hàng')
            ->whereYear('created_at', Carbon::now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(tong_tien) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Fill all 12 months (0 for months with no revenue)
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = 'Tháng ' . $i;
            $chartData[] = $monthlyRevenue->get($i, 0);
        }

        // Order status distribution for pie chart
        $orderStatusCounts = DonHang::select('trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('trang_thai')
            ->pluck('count', 'trang_thai');

        // Top 5 best-selling books
        $topBooks = Sach::withCount(['chiTietDonHangs as total_sold' => function ($q) {
                $q->select(DB::raw('COALESCE(SUM(so_luong), 0)'));
            }])
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // --- FEATURE 2: Inventory Alerts ---
        $lowStockBooks = Sach::where('so_luong', '<=', 5)
            ->orderBy('so_luong')
            ->get();

        // --- FEATURE 4: Notifications ---
        $newOrdersCount = DonHang::where('trang_thai', 'Chờ xử lý')
            ->orWhere('trang_thai', 'Chờ duyệt')
            ->count();
        $recentOrders = DonHang::with('nguoiDung')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalUsers', 'totalProducts',
            'chartLabels', 'chartData', 'orderStatusCounts', 'topBooks',
            'lowStockBooks', 'newOrdersCount', 'recentOrders'
        ));
    }

    // ==================== QUẢN LÝ NGƯỜI DÙNG ====================
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'vai_tro' => 'required|string|max:50'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'vai_tro' => $request->vai_tro
        ]);

        return redirect()->route('admin.users')->with('success', 'Cập nhật vai trò thành công!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() == $id) {
            return redirect()->route('admin.users')->with('error', 'Không thể tự xóa tài khoản của chính mình!');
        }
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Đã xóa người dùng thành công!');
    }

    // ==================== QUẢN LÝ SẢN PHẨM ====================
    public function products(Request $request)
    {
        $query = Sach::with('tacGias');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_sach', 'like', '%' . $search . '%')
                  ->orWhereHas('tacGias', function ($q2) use ($search) {
                      $q2->where('ten_tac_gia', 'like', '%' . $search . '%');
                  });
            });
        }

        $products = $query->latest()->paginate(10)->appends($request->query());
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
            'the_loai' => 'required',
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
            'the_loai' => 'required',
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

    // ==================== QUẢN LÝ ĐƠN HÀNG ====================
    public function orders()
    {
        $orders = DonHang::with(['nguoiDung', 'thanhToan', 'chiTietDonHangs.sach'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = DonHang::findOrFail($id);
        
        if (in_array($order->trang_thai, ['Đã giao hàng', 'Đã hủy'])) {
            return back()->with('error', 'Không thể thay đổi trạng thái của đơn hàng đã hoàn tất hoặc đã hủy.');
        }

        // FEATURE 2: Restore inventory when order is cancelled
        if ($request->trang_thai === 'Đã hủy') {
            foreach ($order->chiTietDonHangs as $chitiet) {
                $chitiet->sach->increment('so_luong', $chitiet->so_luong);
            }
        }

        $order->update(['trang_thai' => $request->trang_thai]);
        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    // ==================== QUẢN LÝ ĐÁNH GIÁ ====================
    public function reviews()
    {
        $reviews = DanhGia::with(['nguoiDung', 'sach'])->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'phan_hoi_admin' => 'required|string'
        ]);

        $review = DanhGia::findOrFail($id);
        $review->update([
            'phan_hoi_admin' => $request->phan_hoi_admin
        ]);

        return back()->with('success', 'Đã lưu phản hồi thành công!');
    }

    public function deleteReplyReview($id)
    {
        $review = DanhGia::findOrFail($id);
        $review->update([
            'phan_hoi_admin' => null
        ]);

        return back()->with('success', 'Đã xóa phản hồi thành công!');
    }

    public function destroyReview($id)
    {
        $review = DanhGia::findOrFail($id);
        $review->delete();
        return back()->with('success', 'Đã xóa đánh giá thành công!');
    }

    // ==================== FEATURE 5: QUẢN LÝ MÃ GIẢM GIÁ ====================
    public function coupons()
    {
        $coupons = MaGiamGia::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function createCoupon()
    {
        return view('admin.coupons.create');
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'ma_code' => 'required|string|unique:ma_giam_gia,ma_code|max:50',
            'so_tien_giam' => 'nullable|numeric|min:0',
            'phan_tram_giam' => 'nullable|integer|min:0|max:100',
            'so_luong' => 'required|integer|min:0',
            'ngay_het_han' => 'nullable|date|after_or_equal:today',
        ]);

        MaGiamGia::create($request->all());

        return redirect()->route('admin.coupons')->with('success', 'Tạo mã giảm giá thành công!');
    }

    public function editCoupon($id)
    {
        $coupon = MaGiamGia::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function updateCoupon(Request $request, $id)
    {
        $request->validate([
            'ma_code' => 'required|string|max:50|unique:ma_giam_gia,ma_code,' . $id,
            'so_tien_giam' => 'nullable|numeric|min:0',
            'phan_tram_giam' => 'nullable|integer|min:0|max:100',
            'so_luong' => 'required|integer|min:0',
            'ngay_het_han' => 'nullable|date',
        ]);

        $coupon = MaGiamGia::findOrFail($id);
        $coupon->update($request->all());

        return redirect()->route('admin.coupons')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function destroyCoupon($id)
    {
        $coupon = MaGiamGia::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('success', 'Đã xóa mã giảm giá thành công!');
    }
}