<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\ThanhToan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VnPayController extends Controller
{
    public function vnpay_payment(Request $request)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_Returnurl');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        // Tính tổng tiền từ giỏ hàng
        $cartItems = GioHang::with('sach')->where('nguoi_dung_id', Auth::id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng đang trống!');
        }

        $totalRaw = 0;
        foreach ($cartItems as $item) {
            $totalRaw += $item->sach->gia * $item->so_luong;
        }

        // Tùy vào cách bạn lưu giá trong DB (vd 150000 hay 150)
        $amountMultiplier = 100; 
        if ($totalRaw < 1000) { 
            // Đoán rằng số tiền đang lưu dạng số nghìn (VD: 150 thay vì 150,000)
            $amountMultiplier = 100000; 
        }

        $vnp_TxnRef = date('YmdHis') . rand(1, 10000); // Mã tham chiếu (mã đơn hàng tự tạo)
        $vnp_OrderInfo = 'Thanh toan don hang ' . $vnp_TxnRef;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $totalRaw * $amountMultiplier;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Redirect sang trang thanh toán VNPAY
        return redirect($vnp_Url);
    }

    public function vnpay_return(Request $request)
    {
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            if ($request->input('vnp_ResponseCode') == '00') {
                DB::beginTransaction();
                try {
                    // 1. Tìm hoặc tạo phương thức thanh toán VNPAY
                    $thanhToan = ThanhToan::firstOrCreate(
                        ['phuong_thuc' => 'VNPAY'],
                        ['trang_thai' => 'Đã thanh toán']
                    );

                    // 2. Lấy thông tin giỏ hàng
                    $cartItems = GioHang::with('sach')->where('nguoi_dung_id', Auth::id())->get();
                    $total = 0;
                    foreach ($cartItems as $item) {
                        $total += $item->sach->gia * $item->so_luong;
                    }

                    // 3. Tạo Đơn hàng mới
                    $donHang = DonHang::create([
                        'nguoi_dung_id' => Auth::id(),
                        'thanh_toan_id' => $thanhToan->id,
                        'tong_tien' => $total,
                        'trang_thai' => 'Chờ duyệt', // Trạng thái mặc định chờ duyệt
                        'dia_chi_giao_hang' => 'Thanh toán trực tuyến VNPAY', // Bạn có thể bổ sung form nhập địa chỉ trước khi thanh toán
                    ]);

                    // 4. Lưu Chi tiết đơn hàng
                    foreach ($cartItems as $item) {
                        ChiTietDonHang::create([
                            'don_hang_id' => $donHang->id,
                            'sach_id' => $item->sach_id,
                            'so_luong' => $item->so_luong,
                            'don_gia' => $item->sach->gia,
                        ]);
                    }

                    // 5. Xoá giỏ hàng
                    GioHang::where('nguoi_dung_id', Auth::id())->delete();

                    DB::commit();

                    return redirect()->route('vnpay.success', ['id' => $donHang->id]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Lỗi hệ thống khi lưu đơn hàng: ' . $e->getMessage());
                }
            } else {
                return redirect()->route('cart.index')->with('error', 'Lỗi trong quá trình thanh toán qua VNPAY!');
            }
        } else {
            return redirect()->route('cart.index')->with('error', 'Chữ ký số VNPAY không hợp lệ!');
        }
    }

    public function vnpay_success($id)
    {
        $donHang = DonHang::with(['chiTietDonHangs.sach', 'thanhToan'])->findOrFail($id);

        // Kiểm tra quyền sở hữu đơn hàng
        if ($donHang->nguoi_dung_id !== Auth::id()) {
            abort(403);
        }

        return view('cart.success', compact('donHang'));
    }
}
