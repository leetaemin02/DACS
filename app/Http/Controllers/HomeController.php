<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sach;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Top 4 best-selling books (most purchased based on chi_tiet_don_hang)
        $trendingBooks = Sach::with('tacGias')
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias')
            ->withCount(['chiTietDonHangs as total_sold' => function ($query) {
                $query->select(DB::raw('COALESCE(SUM(so_luong), 0)'));
            }])
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        // Top 4 highest-rated books (by average rating, min 1 review)
        $favoriteBooks = Sach::with('tacGias')
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias')
            ->has('danhGias')
            ->orderByDesc('danh_gias_avg_so_sao')
            ->take(4)
            ->get();

        // Fetch unique categories
        $categories = Sach::whereNotNull('the_loai')
            ->distinct()
            ->pluck('the_loai');

        return view('home', compact('trendingBooks', 'favoriteBooks', 'categories'));
    }
}
