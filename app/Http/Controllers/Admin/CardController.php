<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    // Menampilkan daftar kartu dengan fitur pencarian tingkat lanjut
    public function index(Request $request)
    {
        $query = Card::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = trim(preg_replace('/\s+/', ' ', $request->search));
            $words = explode(' ', $search);
            
            $query->where(function ($q) use ($search, $words) {
                // Search for the whole phrase in title or description
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
                // Also search for each individual word in title or description
                foreach ($words as $word) {
                    $q->orWhere('title', 'like', '%' . $word . '%')
                      ->orWhere('description', 'like', '%' . $word . '%');
                }
            });
        }

        // Pagination dengan batasan
        $perPage = $this->validatePerPage($request->input('per_page', 10));
        $cards = $query->paginate($perPage);
        
        return view('admin.cards.index', [
            'cards' => $cards,
            'cardsCount' => $cards->total()
        ]);
    }

    /**
     * Normalisasi query pencarian
     */
    protected function normalizeSearchQuery(string $search): string
    {
        return Str::lower(trim(preg_replace('/\s+/', ' ', $search)));
    }

    /**
     * Ekstrak kata kunci dari query pencarian
     */
    protected function extractSearchKeywords(string $search): array
    {
        $words = explode(' ', $search);
        $words = array_unique($words);
        return array_slice($words, 0, 5); // Batasi maksimal 5 kata
    }

    /**
     * Validasi jumlah item per halaman
     */
    protected function validatePerPage($perPage): int
    {
        $perPage = is_numeric($perPage) ? (int)$perPage : 10;
        return max(1, min($perPage, 50)); // Batasi 1-50 item per halaman
    }

    // Mengambil data kartu untuk modal edit
    public function edit($id)
    {
        $card = Card::findOrFail($id);
        return response()->json($card);
    }

    // Menyimpan kartu baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|image|max:2048', // Max 2MB
            'external_link' => 'required|url|max:255',
        ]);

        $imagePath = $request->file('image_url')->store('cards', 'public');

        $card = Card::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => $imagePath,
            'external_link' => $validated['external_link'],
        ]);

        return response()->json([
            'message' => 'Kartu berhasil ditambahkan.',
            'card' => $card
        ], 201);
    }

    // Update data kartu
    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|image|max:2048',
            'external_link' => 'required|url|max:255',
        ]);

        if ($request->hasFile('image_url')) {
            // Hapus gambar lama jika ada
            if ($card->image_url) {
                Storage::disk('public')->delete($card->image_url);
            }
            $imagePath = $request->file('image_url')->store('cards', 'public');
            $card->image_url = $imagePath;
        }

        $card->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'external_link' => $validated['external_link'],
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.',
            'card' => $card
        ]);
    }

    // Hapus data kartu
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        
        // Hapus gambar terkait
        if ($card->image_url) {
            Storage::disk('public')->delete($card->image_url);
        }
        
        $card->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    // Toggle status aktif/nonaktif kartu
    public function toggleStatus(Request $request, Card $card)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $card->update(['is_active' => $request->is_active]);

        return response()->json([
            'message' => 'Status kartu berhasil diubah',
            'is_active' => $card->is_active
        ]);
    }
}