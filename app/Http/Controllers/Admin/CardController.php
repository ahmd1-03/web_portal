<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('external_link', 'like', '%' . $search . '%');
            });
        }

        $cards = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->only('search'));
        return View::make('admin.cards.index', compact('cards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'external_link' => 'required|url',
        ]);

        // Handle image upload
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('images', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        Card::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kartu konten berhasil ditambahkan.']);
        }

        return Redirect::route('admin.cards.index')->with('success', 'Kartu konten berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $card = Card::findOrFail($id);
        return Response::json($card);
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'external_link' => 'required|url',
        ]);

        // Handle image upload if new image is provided
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('images', 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        } else {
            // Keep existing image_url if no new image uploaded
            unset($validated['image_url']);
        }

        $card->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kartu konten berhasil diperbarui.']);
        }

        return Redirect::route('admin.cards.index')->with('success', 'Kartu konten berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Kartu konten berhasil dihapus.']);
        }

        return Redirect::route('admin.cards.index')->with('success', 'Kartu konten berhasil dihapus.');
    }
}
