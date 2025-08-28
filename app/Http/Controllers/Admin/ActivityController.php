<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Card;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Tampilkan semua aktivitas yang belum dihapus
     */
    public function index()
    {
        $activities = ActivityLog::whereNull('deleted_at')
            ->orderBy('timestamp', 'desc')
            ->paginate(20);

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Tampilkan aktivitas yang sudah dihapus (soft delete)
     */
    public function deleted()
    {
        $deletedActivities = ActivityLog::onlyTrashed()
            ->orderBy('timestamp', 'desc')
            ->paginate(20);

        // Ekstrak URL gambar dari log penghapusan kartu
        $deletedActivities->getCollection()->transform(function ($activity) {
            if ($activity->type === 'card' && $activity->action === 'deleted' && is_array($activity->old_values)) {
                // Tambahkan properti baru ke objek aktivitas untuk digunakan di view
                $activity->card_image_url = $activity->old_values['image_url'] ?? null;
            }
            return $activity;
        });

        return view('admin.activities.deleted', compact('deletedActivities'));
    }

    /**
     * Tampilkan detail aktivitas, beserta recent updates
     */
    public function detail()
    {
        $activities = ActivityLog::whereNull('deleted_at')
            ->orderBy('timestamp', 'desc')
            ->paginate(20);

        $recentUpdated = ActivityLog::whereNull('deleted_at')
            ->where('action', 'updated')
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();

        $recentDeleted = ActivityLog::where('action', 'deleted')
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();

        $recentAdded = ActivityLog::whereNull('deleted_at')
            ->where('action', 'created')
            ->orderBy('timestamp', 'desc')
            ->limit(10)
            ->get();

        // Transformasi untuk menambahkan URL gambar pada aktivitas yang dihapus
        $recentDeleted->transform(function ($activity) {
            if ($activity->type === 'card' && $activity->action === 'deleted' && is_array($activity->old_values)) {
                $activity->card_image_url = $activity->old_values['image_url'] ?? null;
            }
            return $activity;
        });

        // Transformasi untuk menambahkan URL gambar pada aktivitas yang diperbarui
        $recentUpdated->transform(function ($activity) {
            if ($activity->type === 'card' && $activity->action === 'updated' && is_array($activity->new_values)) {
                $activity->card_image_url = $activity->new_values['image_url'] ?? $activity->old_values['image_url'] ?? null;
            }
            return $activity;
        });

        // Transformasi untuk menambahkan URL gambar pada aktivitas yang ditambahkan
        $recentAdded->transform(function ($activity) {
            if ($activity->type === 'card' && $activity->action === 'created' && is_array($activity->new_values)) {
                $activity->card_image_url = $activity->new_values['image_url'] ?? null;
            }
            return $activity;
        });

        return view('admin.activities.detail', compact('activities', 'recentUpdated', 'recentDeleted', 'recentAdded'));
    }

    /**
     * Restore aktivitas (untuk AJAX)
     */
    public function restore($id)
    {
        try {
            $activity = ActivityLog::withTrashed()->findOrFail($id);
            $wasTrashed = $activity->trashed();

            $restoredCard = null;
            $cardRestored = false;

            // Restore kartu terkait jika ada
            if ($activity->type === 'card' && $activity->action === 'deleted') {
                $cardData = $activity->old_values;
                if ($cardData && isset($cardData['id'])) {
                    $card = Card::withTrashed()->find($cardData['id']);
                    if ($card && $card->trashed()) {
                        $card->restore();
                        $restoredCard = $card;
                        $cardRestored = true;

                        // Log aktivitas restore kartu
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
                    }
                }
            }

            // Hapus log dari deleted jika dipulihkan
            if ($wasTrashed || $cardRestored) {
                $activity->forceDelete();
            }

            $message = $cardRestored ? 'Aktivitas dan kartu terkait berhasil dipulihkan' : 'Aktivitas berhasil dipulihkan';

            return response()->json([
                'success' => true,
                'message' => $message,
                'activity_id' => $activity->id,
                'restored_card' => $restoredCard,
                'card_restored' => $cardRestored
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan aktivitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus aktivitas secara permanen (untuk AJAX)
     */
    public function permanentDelete($id)
    {
        try {
            $activity = ActivityLog::withTrashed()->findOrFail($id);
            $activityTitle = $activity->title;

            // Hapus kartu terkait jika ada
            if ($activity->type === 'card' && $activity->action === 'deleted') {
                $cardData = $activity->old_values;
                if ($cardData && isset($cardData['id'])) {
                    $card = Card::withTrashed()->find($cardData['id']);
                    if ($card) {
                        $card->forceDelete();
                    }
                }
            }

            $activity->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Aktivitas berhasil dihapus permanen',
                'deleted_id' => $id,
                'activity_title' => $activityTitle
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus aktivitas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore aktivitas (GET fallback)
     */
    public function restoreFallback($id)
    {
        try {
            $activity = ActivityLog::withTrashed()->findOrFail($id);
            $wasTrashed = $activity->trashed();
            $cardRestored = false;

            if ($activity->type === 'card' && $activity->action === 'deleted') {
                $cardData = $activity->old_values;
                if ($cardData && isset($cardData['id'])) {
                    $card = Card::withTrashed()->find($cardData['id']);
                    if ($card && $card->trashed()) {
                        $card->restore();
                        $cardRestored = true;

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
                    }
                }
            }

            if ($cardRestored && !$wasTrashed) {
                $activity->delete();
            } elseif ($wasTrashed) {
                $activity->restore();
            }

            return redirect()->route('admin.cards.index')->with('success', 'Kartu berhasil dipulihkan dan dikembalikan ke manajemen kartu');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan aktivitas: ' . $e->getMessage());
        }
    }

    /**
     * Permanent delete fallback (GET request)
     */
    public function permanentDeleteFallback($id)
    {
        try {
            $activity = ActivityLog::withTrashed()->findOrFail($id);
            $activity->forceDelete();

            return redirect()->route('admin.activities.deleted')->with('success', 'Aktivitas berhasil dihapus permanen');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus aktivitas: ' . $e->getMessage());
        }
    }
}
