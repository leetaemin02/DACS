<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpWhitelistMiddleware
{
    /**
     * Danh sách các IP được phép truy cập (IP Whitelist)
     * Thêm IP thật của bạn hoặc IP công ty vào đây.
     * '127.0.0.1' và '::1' là cho localhost khi chạy trên máy cá nhân (XAMPP).
     */
    protected $whitelist = [
        '127.0.0.1',
        '::1',
        '1.55.42.156',
        // '192.168.1.100', // Ví dụ IP máy trạm
        // '203.113.xxx.yyy', // Ví dụ IP Public văn phòng
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lấy IP mặc định của request
        $clientIp = $request->ip();

        // Trên Render, IP thật của người dùng luôn được nhét vào X-Forwarded-For
        // Do TrustProxies đôi khi chưa cấu hình đủ header nên ta lấy thủ công an toàn ở đây
        if ($forwarded = $request->header('X-Forwarded-For')) {
            // X-Forwarded-For có thể là chuỗi "IP_Client, IP_Proxy_1, IP_Proxy_2"
            // IP thật của user luôn là IP nằm đầu tiên mạng lưới (bên trái cùng) hoặc do Render cung cấp ở vị trí cuối cùng tuỳ config.
            // Để an toàn, ta lấy theo mảng và kiểm tra xem có IP nào trong mảng nằm ở whitelist không.
            $ips = array_map('trim', explode(',', $forwarded));
            
            // Tìm xem trong chuỗi Forwarded IP có cái nào khớp Whitelist không
            $hasValidIp = false;
            foreach ($ips as $ip) {
                if (in_array($ip, $this->whitelist)) {
                    $hasValidIp = true;
                    $clientIp = $ip; // Gán lại IP để log/hiển thị
                    break;
                }
            }
            
            if ($hasValidIp) {
                return $next($request);
            }
        }

        // Kiểm tra IP gốc (nếu không chạy qua proxy hoặc chạy ở localhost)
        if (!in_array($clientIp, $this->whitelist)) {
            abort(403, "Truy cập bị từ chối. Lỗi IP: " . $clientIp . " (Forwarded: " . $request->header('X-Forwarded-For', 'None') . ")");
        }

        return $next($request);
    }
}
