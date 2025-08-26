<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DeletedCardController extends Controller
{
    /**
     * Display all deleted cards
     */
    public function index()
    {
        $deletedCards = Card::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(12);

        return view('admin.deleted-cards.index', compact('deletedCards'));
    }

    /**
     * Display deleted cards list
     */
    public function deleted()
    {
        $deletedCards = Card::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(12);

        return view('admin.deleted-cards.deleted', compact('deletedCards'));
    }

    /**
     * Restore a deleted card
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $card = Card::onlyTrashed()->findOrFail($id);
            $card->restore();
            ActivityLog::create([
                'type' => 'card',
                'action' => 'restored',
                'title' => $card->title,
                'details' => 'Kartu dipulihkan dari daftar dihapus',
                'user_id' => auth('admin')->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kartu berhasil dipulihkan',
                'card' => $card,
                'redirect_url' => route('admin.cards.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan kartu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a card
     */
    public function permanentDelete($id)
    {
        try {
            DB::beginTransaction();
            $card = Card::onlyTrashed()->findOrFail($id);
            $cardTitle = $card->title;
            
            // Hapus file gambar jika ada
            if ($card->image_path && Storage::exists($card->image_path)) {
                Storage::delete($card->image_path);
            }
            
            $card->forceDelete();
            ActivityLog::create([
                'type' => 'card',
                'action' => 'permanently_deleted',
                'title' => $cardTitle,
                'details' => 'Kartu dihapus permanen',
                'user_id' => auth('admin')->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kartu berhasil dihapus permanen',
                'deleted_id' => $id,
                'card_title' => $cardTitle
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kartu: ' . $e->getMessage()
            ], 500);
        }
    }
}
