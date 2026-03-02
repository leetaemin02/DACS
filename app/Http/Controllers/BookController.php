<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sach;

class BookController extends Controller
{
    public function show($id)
    {
        $book = Sach::findOrFail($id);

        // Tìm các sách random
        // Cho ví dụ, ở đây ta lấy ngẫu nhiên 4 cuốn sách khác
        $relatedBooks = Sach::where('id', '!=', $id)->inRandomOrder()->take(4)->get();
        // Trả về view với dữ liệu sách và sách liên quan
        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function categories()
    {
        $books = Sach::paginate(9);
        return view('books.categories', compact('books'));
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
