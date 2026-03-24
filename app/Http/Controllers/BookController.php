<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sach;

class BookController extends Controller
{
    public function categories(Request $request)
    {
        $query = Sach::with('tacGias')
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias');

        if ($request->has('category') && $request->category != '') {
            $query->where('the_loai', $request->category);
        }

        if ($request->price == 'low') {
            $query->where('gia','<',50000);
        }

        if ($request->price == 'medium') {
            $query->whereBetween('gia',[50000,100000]);
        }

        if ($request->price == 'high') {
            $query->where('gia','>',100000);
        }

        if ($request->sort == 'bestseller') {
            $query->orderBy('so_luong','desc');
        }

        if ($request->sort == 'new') {
            $query->orderBy('created_at','desc');
        }

        $books = $query->paginate(9)->withQueryString();
        return view('books.categories', compact('books'));
    }

    public function show($id)
    {
        $book = Sach::with(['tacGias', 'danhGias.nguoiDung'])
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias')
            ->findOrFail($id);

        // Tìm các sách random
        // Cho ví dụ, ở đây ta lấy ngẫu nhiên 3 cuốn sách khác
        $relatedBooks = Sach::with('tacGias')
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(3)
            ->get();
        // Trả về view với dữ liệu sách và sách liên quan
        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $booksQuery = Sach::with('tacGias')
            ->withAvg('danhGias', 'so_sao')
            ->withCount('danhGias')
            ->where('ten_sach', 'LIKE', "%$query%")
            ->orWhereHas('tacGias', function($q) use ($query) {
                $q->where('ten_tac_gia', 'LIKE', "%$query%");
            });

        if ($request->ajax() || $request->has('ajax')) {
            $books = $booksQuery->take(5)->get();
            return response()->json($books);
        }

        $books = $booksQuery->paginate(9);
        $books->appends(['query' => $query]);
        return view('books.search', compact('books', 'query'));
    }
}
