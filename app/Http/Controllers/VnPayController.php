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

        $donHangId = session('vnpay_don_hang_id');
        if (!$donHangId) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy đơn hàng cần thanh toán!');
        }

        $donHang = DonHang::findOrFail($donHangId);

        // Sử dụng tổng tiền đã được tính (bao gồm mã giảm giá)
        $totalRaw = $donHang->tong_tien;

        $amountMultiplier = 100;
        if ($totalRaw < 1000) { 
            // Nếu lưu ở DB dạng nghìn đồng
            $amountMultiplier = 100000; 
        }

        $vnp_TxnRef = $donHang->id . '_' . date('YmdHis'); // Gắn ID đơn hàng vào mã tham chiếu
        session(['vnpay_txn_ref' => $donHang->id]); // Lưu lại ID vào session để return check dễ hơn

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
                    $donHangId = session('vnpay_txn_ref');
                    
                    // Nếu session bị mất, thử lấy ID từ TxnRef của VNPAY trả về (ID đơn hàng sẽ ở trước dấu _)
                    if (!$donHangId) {
                         $txnRef = $request->input('vnp_TxnRef');
                         $parts = explode('_', $txnRef);
                         $donHangId = $parts[0];
                    }

                    $donHang = DonHang::findOrFail($donHangId);

                    // 1. Tìm hoặc tạo phương thức thanh toán VNPAY
                    $thanhToan = ThanhToan::firstOrCreate(
                        ['phuong_thuc' => 'Thanh toán VNPAY'],
                        ['trang_thai' => 'Đã thanh toán']
                    );

                    // 2. Cập nhật Đơn hàng (ĐÃ được tạo ở CartController) thay vì tạo mới
                    $donHang->update([
                        'thanh_toan_id' => $thanhToan->id,
                        'trang_thai' => 'Chờ duyệt',
                    ]);

                    // (Chi tiết đơn hàng và tính phần trăm đã được xử lý ở CartController lúc create!)

                    // 3. Xoá giỏ hàng sau khi thanh toán thành công
                    GioHang::where('nguoi_dung_id', $donHang->nguoi_dung_id)->delete();

                    DB::commit();
                    
                    // Xóa vnpay session cho sạch
                    session()->forget(['vnpay_don_hang_id', 'vnpay_txn_ref']);

                    return redirect()->route('vnpay.success', ['id' => $donHang->id]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Lỗi hệ thống khi lưu đơn hàng: ' . $e->getMessage());
                }
            } else {
                // VNPAY thanh toán thất bại/bị hủy
                // Ta nên lấy DonHang và cập nhật trạng thái là "Đã hủy" hoặc xóa nó
                $donHangId = session('vnpay_txn_ref') ?? explode('_', $request->input('vnp_TxnRef'))[0];
                if ($donHangId) {
                    $donHang = DonHang::find($donHangId);
                    if ($donHang) {
                        // Khôi phục lại sách
                        foreach($donHang->chiTietDonHangs as $ct) {
                            $ct->sach->increment('so_luong', $ct->so_luong);
                        }
                        if ($donHang->maGiamGia) {
                            $donHang->maGiamGia->increment('so_luong');
                        }
                        $donHang->update(['trang_thai' => 'Đã hủy']);
                    }
                }
                return redirect()->route('cart.index')->with('error', 'Bạn đã hủy thanh toán hoặc giao dịch qua VNPAY bị lỗi!');
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
