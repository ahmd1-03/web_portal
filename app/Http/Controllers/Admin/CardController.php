<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = trim(preg_replace('/\s+/', ' ', $request->search));
            $words = explode(' ', $search);
            
            $query->where(function ($q) use ($search, $words) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
                foreach ($words as $word) {
                    $q->orWhere('title', 'like', '%' . $word . '%')
                      ->orWhere('description', 'like', '%' . $word . '%');
                }
            });
        }

        $perPage = $this->validatePerPage($request->input('per_page', 10));
        $cards = $query->latest()->paginate($perPage);
        
        return view('admin.cards.index', [
            'cards' => $cards,
            'cardsCount' => $cards->total()
        ]);
    }

    protected function validatePerPage($perPage): int
    {
        $perPage = is_numeric($perPage) ? (int)$perPage : 10;
        return max(1, min($perPage, 50));
    }

    public function edit($id)
    {
        $card = Card::findOrFail($id);
        return response()->json($card);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'external_link' => 'required|url|max:255',
        ]);

        $image = $request->file('image_url');
        $filename = Str::uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('cards', $filename, 'public');

        $card = Card::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_url' => 'cards/' . $filename,
            'external_link' => $validated['external_link'],
            'is_active' => true,
        ]);

        // Log activity for card creation
        ActivityLog::create([
            'type' => 'card',
            'action' => 'created',
            'title' => $card->title,
            'details' => 'Kartu baru telah ditambahkan',
            'user_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'old_values' => null,
            'new_values' => $card->toArray(),
            'timestamp' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kartu berhasil ditambahkan.',
            'card' => $card
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);
        $oldValues = $card->toArray();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'external_link' => 'required|url|max:255',
        ]);

        if ($request->hasFile('image_url')) {
            if ($card->image_url && Storage::disk('public')->exists($card->image_url)) {
                Storage::disk('public')->delete($card->image_url);
            }
            
            $image = $request->file('image_url');
            $filename = Str::uuid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('cards', $filename, 'public');
            $card->image_url = 'cards/' . $filename;
        }

        $card->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'external_link' => $validated['external_link'],
        ]);

        // Log activity for card update
        ActivityLog::create([
            'type' => 'card',
            'action' => 'updated',
            'title' => $card->title,
            'details' => 'Kartu telah diperbarui',
            'user_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'old_values' => $oldValues,
            'new_values' => $card->toArray(),
            'timestamp' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
            'card' => $card
        ]);
    }

    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $oldValues = $card->toArray();

        if ($card->image_url && Storage::disk('public')->exists($card->image_url)) {
            Storage::disk('public')->delete($card->image_url);
        }
        
        $card->delete();

        // Log activity for card deletion
        ActivityLog::create([
            'type' => 'card',
            'action' => 'deleted',
            'title' => $card->title,
            'details' => 'Kartu telah dihapus',
            'user_id' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'old_values' => $oldValues,
            'new_values' => null,
            'timestamp' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    public function toggleStatus(Request $request, Card $card)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $card->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status kartu berhasil diubah',
            'is_active' => $card->is_active
        ]);
    }
}
