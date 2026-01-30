<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sach;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch latest 8 books
        $books = Sach::latest()->take(6)->get();
        return view('home', compact('books'));
    }
}
