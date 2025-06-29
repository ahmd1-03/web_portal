<?php

namespace App\Http\Controllers\Admin;

// Controller untuk halaman dashboard admin

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\Card;
use App\Models\Admin;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    // Fungsi untuk menampilkan halaman dashboard dengan data statistik dan aktivitas terbaru
    public function index(): View
    {
        // Hitung jumlah kartu
        $cardsCount = Card::count();
        // Hitung jumlah admin
        $adminsCount = Admin::count();
        // Hitung jumlah pengunjung
        $visitorsCount = Visitor::count();

        // Ambil 5 kartu terbaru yang diupdate
        $recentCards = Card::orderBy('updated_at', 'desc')->take(5)->get()->map(function ($card) {
            return [
                'type' => 'card', // tipe aktivitas
                'action' => 'updated', // aksi yang dilakukan
                'title' => $card->title, // judul kartu
                'timestamp' => $card->updated_at, // waktu update
            ];
        });

        // Ambil 5 admin terbaru yang diupdate
        $recentAdmins = Admin::orderBy('updated_at', 'desc')->take(5)->get()->map(function ($admin) {
            return [
                'type' => 'admin',
                'action' => 'updated',
                'title' => $admin->name,
                'timestamp' => $admin->updated_at,
            ];
        });

        // Ambil 5 pengguna terbaru yang diupdate
        $recentUsers = User::orderBy('updated_at', 'desc')->take(5)->get()->map(function ($user) {
            return [
                'type' => 'user',
                'action' => 'updated',
                'title' => $user->name,
                'timestamp' => $user->updated_at,
            ];
        });

        // Gabungkan semua aktivitas terbaru
        $recentActivities = $recentCards->concat($recentAdmins)->concat($recentUsers);

        // Urutkan aktivitas berdasarkan waktu terbaru dan ambil 5 teratas
        $recentActivities = $recentActivities->sortByDesc('timestamp')->take(5);

        // Kirim data ke view dashboard admin
        return view('admin.dashboard', compact(
            'cardsCount',
            'adminsCount',
            'visitorsCount',
            'recentActivities'
        ));
    }
}
