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
        // Lấy IP của người dùng đang request
        $clientIp = $request->ip();

        // Kiểm tra xem IP có nằm trong danh sách cho phép không
        if (!in_array($clientIp, $this->whitelist)) {
            // Ném ra trang lỗi 403 (Cấm truy cập)
            abort(403, 'Truy cập bị từ chối.');
        }

        return $next($request);
    }
}
