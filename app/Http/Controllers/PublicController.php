<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuWisuda;
use App\Models\Wisudawan;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        // Fetch latest book for hero section
        $latestBook = BukuWisuda::where('status', 'Published')->latest('tanggal_terbit')->first();

        // Fetch books for the list with search and pagination
        $query = BukuWisuda::where('status', 'Published')
            ->withCount('wisudawan')
            ->with('template');

        if ($request->filled('search_book')) {
            $search = $request->search_book;
            $query->where(function($q) use ($search) {
                $q->where('nama_buku', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        $books = $query->latest('tanggal_terbit')->paginate(8)->withQueryString();

        return view('landing', compact('latestBook', 'books'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $graduates = [];

        if ($query) {
            $graduates = Wisudawan::where('nama', 'like', "%{$query}%")
                ->orWhere('nim', 'like', "%{$query}%")
                ->with('bukuWisuda')
                ->get();
        }

        return view('search_results', compact('graduates', 'query'));
    }

    public function showBook(BukuWisuda $book)
    {
        $graduates = $book->wisudawan()->paginate(20);
        return view('book_detail', compact('book', 'graduates'));
    }
}
