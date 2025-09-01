<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\ActivityLog;
use App\Models\Card;
use App\Models\Admin;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $cardsCount = Card::where('user_id', Auth::id())->count();
        $adminsCount = Admin::count();
        $visitorsCount = Visitor::count();

        $timeLimit = now()->subWeek();

        // Aktivitas Ditambahkan - data baru yang dibuat
        $recentAdded = ActivityLog::active()
            ->where('user_id', Auth::id())
            ->byAction('created')
            ->where('timestamp', '>=', $timeLimit)
            ->orderBy('timestamp', 'desc')
            ->take(5)
            ->get();

        // Aktivitas Diubah - data yang diperbarui
        $recentUpdated = ActivityLog::active()
            ->where('user_id', Auth::id())
            ->byAction('updated')
            ->where('timestamp', '>=', $timeLimit)
            ->orderBy('timestamp', 'desc')
            ->take(5)
            ->get();

        // Aktivitas Dihapus - data yang dihapus (bukan soft deleted)
        $recentDeleted = ActivityLog::active()
            ->where('user_id', Auth::id())
            ->byAction('deleted')
            ->where('timestamp', '>=', $timeLimit)
            ->orderBy('timestamp', 'desc')
            ->take(5)
            ->get();

        // Aktivitas terbaru untuk timeline - diurutkan berdasarkan waktu aktual
        $recentActivities = $recentAdded->concat($recentUpdated)->concat($recentDeleted)
            ->sortByDesc(function ($activity) {
                return $activity->timestamp;
            })
            ->take(10);

        // Hitung total untuk setiap kategori
        $totalAdded = ActivityLog::active()
            ->where('user_id', Auth::id())
            ->byAction('created')
            ->where('timestamp', '>=', $timeLimit)
            ->count();

        $totalUpdated = ActivityLog::active()
            ->where('user_id', Auth::id())
            ->byAction('updated')
            ->where('timestamp', '>=', $timeLimit)
            ->count();

        $totalDeleted = ActivityLog::active()
            ->where('user_id', Auth::id())
            ->byAction('deleted')
            ->where('timestamp', '>=', $timeLimit)
            ->count();

        // Data untuk perbandingan kartu harian dan mingguan
        $dailyCardCounts = $this->getDailyCardCounts();
        $weeklyCardCounts = $this->getWeeklyCardCounts();

        return view('admin.dashboard', compact(
            'cardsCount',
            'adminsCount',
            'visitorsCount',
            'recentAdded',
            'recentDeleted',
            'recentUpdated',
            'recentActivities',
            'totalAdded',
            'totalUpdated',
            'totalDeleted',
            'dailyCardCounts',
            'weeklyCardCounts'
        ));
    }

    /**
     * Get daily card addition counts for the last 30 days
     */
    private function getDailyCardCounts(): array
    {
        $dailyCounts = Card::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('user_id', Auth::id())
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return $dailyCounts->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('d M'),
                'count' => $item->count
            ];
        })->toArray();
    }

    /**
     * Get weekly card addition counts for the last 12 weeks
     */
    private function getWeeklyCardCounts(): array
    {
        $weeklyCounts = Card::selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, COUNT(*) as count')
            ->where('user_id', Auth::id())
            ->where('created_at', '>=', Carbon::now()->subWeeks(12))
            ->groupBy('year', 'week')
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get();

        return $weeklyCounts->map(function ($item) {
            $startOfWeek = Carbon::now()->setISODate($item->year, $item->week)->startOfWeek();
            return [
                'date' => 'Minggu ' . $item->week . ' (' . $startOfWeek->format('d M') . ')',
                'count' => $item->count
            ];
        })->toArray();
    }
}
