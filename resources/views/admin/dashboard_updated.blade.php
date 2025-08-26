@extends('admin.layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    <!-- Container utama dengan konsistensi seperti halaman manajemen kartu -->
    <div class="max-w-full mx-auto px-4 md:px-6 transition-all duration-300">
        
        <!-- Header dengan gradient hijau seperti manajemen kartu -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">
                        Dashboard Admin
                    </h1>
                    <p class="text-sm text-gray-600 mt-2">Selamat datang di dashboard manajemen portal Karawang</p>
                </div>
                <div>
                    <a href="/"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition duration-300 shadow-md text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Lihat Website
                    </a>
                </div>
            </div>
        </div>

        <!-- Grid layout konsisten dengan manajemen kartu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Link ke halaman manajemen kartu -->
            <a href="{{ route('admin.cards.index') }}"
                class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 hover:border-emerald-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Management Kartu</h3>
                        <p class="text-sm text-gray-600">Kelola konten kartu</p>
                    </div>
                </div>
            </a>

            <!-- Link ke halaman manajemen pengguna -->
            <a href="{{ route('admin.users.index') }}"
                class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-all duration-300 hover:border-purple-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Management Pengguna</h3>
                        <p class="text-sm text-gray-600">Kelola akses admin</p>
                    </div>
                </div>
            </a>

            <!-- Statistik jumlah kartu -->
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Total Kartu</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $cardsCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistik jumlah admin -->
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Total Admin</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $adminsCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Ringkasan Aktivitas Terakhir - Dengan styling konsisten -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ringkasan Aktivitas Terakhir</h3>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <div class="flex items-center gap-3">
                            <label for="timeRange" class="text-sm font-medium text-gray-700">Filter Waktu:</label>
                            <select id="timeRange"
                                class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                onchange="updateTimeFilter()">
                                <option value="1_minute">1 Menit</option>
                                <option value="1_hour">1 Jam</option>
                                <option value="1_day">1 Hari</option>
                                <option value="1_week" selected>1 Minggu</option>
                                <option value="1_month">1 Bulan</option>
                                <option value="1_year">1 Tahun</option>
                            </select>
                        </div>
                        <a href="{{ route('admin.detail') }}"
                            class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold transition hover:underline">
                            Lihat Detail
                        </a>
                    </div>
                </div>

                <!-- Ringkasan Aktivitas - Dengan grid responsif -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Aktivitas Ditambahkan -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-900">Ditambahkan</h4>
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $totalAdded ?? 0 }}
                            </span>
                        </div>
                        @if($recentAdded->isEmpty())
                            <p class="text-gray-500 text-sm text-center py-4">Belum ada aktivitas yang ditambahkan.</p>
                        @else
                            <div class="space-y-3 max-h-48 overflow-y-auto">
                                @foreach($recentAdded as $activity)
                                    <div class="border-l-4 border-emerald-400 pl-3 py-2">
                                        <p class="text-sm font-medium text-gray-900 truncate" title="{{ $activity->title }}">
                                            {{ $activity->title }}
                                        </p>
                                        @if($activity->details)
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($activity->details, 50) }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->timestamp->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- <!-- Aktivitas Dihapus -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-900">Dihapus</h4>
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $totalDeleted ?? 0 }}
                            </span>
                        </div>
                        @if($recentDeleted->isEmpty())
                            <p class="text-gray-500 text-sm text-center py-4">Tidak ada kartu yang dihapus.</p>
                        @else
                            <div class="space-y-3 max-h-48 overflow-y-auto">
                                @foreach($recentDeleted as $activity)
                                    <div class="border-l-4 border-red-400 pl-3 py-2">
                                        <p class="text-sm font-medium text-gray-900 truncate" title="{{ $activity->title }}">
                                            {{ $activity->title }}
                                        </p>
                                        @if($activity->details)
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($activity->details, 50) }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->deleted_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}

                    <!-- Aktivitas Diubah -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-900">Diubah</h4>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $totalUpdated ?? 0 }}
                            </span>
                        </div>
                        @if($recentUpdated->isEmpty())
                            <p class="text-gray-500 text-sm text-center py-4">Tidak ada kartu yang diubah.</p>
                        @else
                            <div class="space-y-3 max-h-48 overflow-y-auto">
                                @foreach($recentUpdated as $activity)
                                    <div class="border-l-4 border-yellow-400 pl-3 py-2">
                                        <p class="text-sm font-medium text-gray-900 truncate" title="{{ $activity->title }}">
                                            {{ $activity->title }}
                                        </p>
                                        @if($activity->details)
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($activity->details, 50) }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->timestamp->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data untuk perbandingan
    const cardData = {
        daily: @json($dailyCardCounts ?? []),
        weekly: @json($weeklyCardCounts ?? [])
    };

    // Fungsi untuk update filter waktu
    function updateTimeFilter() {
        const timeRange = document.getElementById('timeRange').value;
        // Implementasi filter akan ditambahkan sesuai kebutuhan
        console.log('Filter updated:', timeRange);
    }

    // Inisialisasi Chart
    function initChart() {
        const ctx = document.getElementById('cardComparisonChart').getContext('2d');
        
        // Prepare data for chart
        const daily = cardData.daily;
        const weekly = cardData.weekly;
        
        // Create combined labels
        const labels = daily.map(item => item.date);
        const dailyData = daily.map(item => item.count);
        
        // For weekly data, we need to match the daily dates
        const weeklyData = [];
        for (let i = 0; i < daily.length; i++) {
            const weekIndex = Math.floor(i / 7);
            weeklyData.push(weekly[weekIndex]?.count || 0);
        }
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kartu per Hari',
                    data: dailyData,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }, {
                    label: 'Kartu per Minggu (rata-rata)',
                    data: weeklyData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Kartu Ditambahkan'
                    }
                }
            }
        });
    }

    // Update tabel perbandingan
    function updateComparisonTable() {
        const tbody = document.getElementById('comparisonTableBody');
        const daily = cardData.daily;
        const weekly = cardData.weekly;
        
        let totalDaily = 0;
        let totalWeekly = 0;
        
        tbody.innerHTML = '';
        
        // Create weekly summary from daily data
        const weeklySummary = {};
        daily.forEach((item, index) => {
            const weekNumber = Math.floor(index / 7);
            if (!weeklySummary[weekNumber]) {
                weeklySummary[weekNumber] = {
                    date: `Minggu ${weekNumber + 1}`,
                    count: 0
                };
            }
            weeklySummary[weekNumber].count += item.count;
        });
        
        // Display comparison
        const maxLength = Math.max(daily.length, Object.keys(weeklySummary).length);
        
        for (let i = 0; i < Math.min(7, daily.length); i++) {
            const dailyItem = daily[i] || { date: '-', count: 0 };
            const weekIndex = Math.floor(i / 7);
            const weeklyItem = weekly[weekIndex] || { date: '-', count: 0 };
            
            totalDaily += dailyItem.count;
            totalWeekly += weeklyItem.count;
            
            const diff = dailyItem.count - weeklyItem.count;
            const diffText = diff > 0 ? '+' + diff : diff;
            const diffColor = diff > 0 ? 'text-green-600' : diff < 0 ? 'text-red-600' : 'text-gray-600';
            
            const row = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${dailyItem.date}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${dailyItem.count}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${weeklyItem.count}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm ${diffColor}">
                        ${diffText}
                    </td>
                </tr>
            `;
            
            tbody.innerHTML += row;
        }
        
        // Update summary statistics
        document.getElementById('totalDaily').textContent = totalDaily;
        document.getElementById('totalWeekly').textContent = totalWeekly;
        
        const ratio = totalWeekly > 0 ? Math.round((totalDaily / totalWeekly) * 100) : 0;
        document.getElementById('comparisonRatio').textContent = ratio + '%';
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initChart();
        updateComparisonTable();
    });
</script>
