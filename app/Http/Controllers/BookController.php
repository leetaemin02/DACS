<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sach;

class BookController extends Controller
{
    public function categories(Request $request)
    {
        $query = Sach::query();

        if ($request->price == 'low') {
            $query->where('gia','<',50);
        }

        if ($request->price == 'medium') {
            $query->whereBetween('gia',[50,100]);
        }

        if ($request->price == 'high') {
            $query->where('gia','>',100);
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
        $book = Sach::findOrFail($id);

        // Tìm các sách random
        // Cho ví dụ, ở đây ta lấy ngẫu nhiên 3 cuốn sách khác
        $relatedBooks = Sach::where('id', '!=', $id)->inRandomOrder()->take(3)->get();
        // Trả về view với dữ liệu sách và sách liên quan
        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $booksQuery = Sach::where('ten_sach', 'LIKE', "%$query%")
            ->orWhere('tac_gia', 'LIKE', "%$query%");

        if ($request->ajax() || $request->has('ajax')) {
            $books = $booksQuery->take(5)->get();
            return response()->json($books);
        }

        $books = $booksQuery->paginate(9);
        $books->appends(['query' => $query]);
        return view('books.search', compact('books', 'query'));
    }
}
