<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Page;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Card::where('is_active', true); // hanya ambil yang aktif

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        // Implement pagination with 10 items per page
        $cards = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'cards' => $cards->items(),
                'pagination' => [
                    'current_page' => $cards->currentPage(),
                    'last_page' => $cards->lastPage(),
                    'per_page' => $cards->perPage(),
                    'total' => $cards->total(),
                    'from' => $cards->firstItem(),
                    'to' => $cards->lastItem(),
                    'has_more' => $cards->hasMorePages(),
                    'on_first' => $cards->onFirstPage(),
                    'previous_url' => $cards->previousPageUrl(),
                    'next_url' => $cards->nextPageUrl(),
                    'url_range' => $cards->getUrlRange(1, $cards->lastPage()),
                ],
                'search' => $search,
                'success' => true
            ]);
        }

        return view('frontend.home', compact('cards', 'search'));
    }

    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
