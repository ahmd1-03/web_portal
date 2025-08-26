<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecentActivityController extends Controller
{
    /**
     * Get recent activities by action type
     */
    public function getByActionType(Request $request, string $type): JsonResponse
    {
        $validTypes = ['created', 'deleted', 'updated'];
        
        if (!in_array($type, $validTypes)) {
            return response()->json(['error' => 'Invalid action type'], 400);
        }

        $activities = ActivityLog::active()
            ->byAction($type)
            ->orderBy('timestamp', 'desc')
            ->take(20)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'description' => $this->formatDescription($activity),
                    'image' => $this->getActivityImage($activity),
                    'timestamp' => $activity->timestamp->toISOString(),
                    'relative_time' => $activity->timestamp->diffForHumans(),
                    'action' => $activity->action,
                    'type' => $activity->type
                ];
            });

        return response()->json([
            'activities' => $activities,
            'count' => $activities->count()
        ]);
    }

    /**
     * Format activity description
     */
    private function formatDescription($activity): string
    {
        $details = json_decode($activity->details, true);
        
        switch ($activity->action) {
            case 'created':
                return 'Kartu baru ditambahkan ke sistem';
            case 'updated':
                $changes = $details['changes'] ?? [];
                $changeCount = count($changes);
                return $changeCount > 0 
                    ? "{$changeCount} perubahan dilakukan pada kartu ini"
                    : 'Kartu diperbarui tanpa perubahan signifikan';
            case 'deleted':
                return 'Kartu telah dihapus dari sistem';
            default:
                return 'Aktivitas dilakukan pada kartu ini';
        }
    }

    /**
     * Get activity image/icon
     */
    private function getActivityImage($activity): string
    {
        // Default icons based on action type
        $icons = [
            'created' => '/images/icons/add-circle.svg',
            'updated' => '/images/icons/edit-pencil.svg',
            'deleted' => '/images/icons/trash-bin.svg'
        ];
        
        return $icons[$activity->action] ?? '/images/icons/default.svg';
    }
}
