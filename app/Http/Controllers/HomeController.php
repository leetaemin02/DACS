<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sach;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch latest 6 books with authors, average rating, and review count
        $books = Sach::with('tacGias')
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias')
            ->latest()
            ->take(6)
            ->get();
        
        // Fetch unique categories
        $categories = Sach::whereNotNull('the_loai')
            ->distinct()
            ->pluck('the_loai');

        return view('home', compact('books', 'categories'));
    }
}
