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
        // $query = Card::query();

         $query = Card::where('is_active', true); // hanya ambil yang aktif

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $cards = $query->get();

        return view('frontend.home', compact('cards', 'search'));
    }

    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
