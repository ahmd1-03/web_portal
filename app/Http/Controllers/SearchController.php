<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    /**
     * Handle search requests via AJAX
     * Fokus pencarian hanya pada kolom title untuk hasil yang lebih relevan
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        
        if (empty($query) || strlen($query) < 1) {
            return response()->json([
                'cards' => [],
                'message' => 'Silakan masukkan minimal 1 karakter untuk pencarian',
                'success' => false
            ]);
        }

        // Pencarian hanya di kolom title untuk hasil yang lebih relevan
        $cards = Card::where('title', 'like', "%{$query}%")
                ->where('is_active', true)
                ->where('enabled', true)
                ->orderByRaw("
                    CASE 
                        WHEN title LIKE ? THEN 1
                        WHEN title LIKE ? THEN 2
                        WHEN title LIKE ? THEN 3
                        ELSE 4
                    END
                ", ["{$query}%", "% {$query}%", "%{$query}%"])
                ->orderBy('title')
                ->limit(50)
                ->get()
                ->map(function($card) {
                    return [
                        'id' => $card->id,
                        'title' => $card->title,
                        'description' => $card->description,
                        'image_url' => $card->image_url ? Storage::url($card->image_url) : '/images/placeholder.jpg',
                        'external_link' => $card->external_link,
                        'created_at' => $card->created_at->diffForHumans()
                    ];
                });

        return response()->json([
            'cards' => $cards,
            'message' => $cards->isEmpty() ? 'Tidak ada hasil yang ditemukan untuk "' . $query . '"' : null,
            'success' => true,
            'total' => $cards->count()
        ]);
    }
}
