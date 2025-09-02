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
        $page = $request->input('page', 1);

        if (empty($query) || strlen($query) < 1) {
            return response()->json([
                'cards' => [],
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 0,
                    'per_page' => 10,
                    'total' => 0,
                    'from' => null,
                    'to' => null,
                    'has_more' => false,
                    'on_first' => true,
                    'previous_url' => null,
                    'next_url' => null,
                    'url_range' => [],
                ],
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
                ->paginate(10, ['*'], 'page', $page);

        $cardsData = $cards->map(function($card) {
            return [
                'id' => $card->id,
                'title' => $card->title,
                'description' => $card->description,
                // Use the accessor directly, which already returns URL with /storage/ prefix
                'image_url' => $card->image_url ? $card->image_url : '/images/placeholder.jpg',
                'external_link' => $card->external_link,
                'created_at' => $card->created_at->diffForHumans()
            ];
        });

        return response()->json([
            'cards' => $cardsData,
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
            'message' => $cards->isEmpty() ? 'Tidak ada hasil yang ditemukan untuk "' . $query . '"' : null,
            'success' => true
        ]);
    }
}
