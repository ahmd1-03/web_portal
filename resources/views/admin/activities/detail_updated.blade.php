@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                        Detail Aktivitas Terakhir
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">Informasi lengkap tentang aktivitas yang telah dilakukan</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-200 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 min-h-[600px]">
                <!-- Left Column - Main Info -->
                <div class="lg:col-span-2">
                    <!-- Aktivitas Terakhir yang Diubah -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-white/20 rounded-full mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-white">Aktivitas Terakhir yang Diubah</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($recentUpdated->isEmpty())
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg">Belum ada aktivitas yang diubah.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($recentUpdated as $activity)
                                        <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-start justify-between mb-2">
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                    Diubah
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $activity->timestamp->diffForHumans() }}</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600 mb-2 line-clamp-3">
                                                {{ $activity->details ? Str::limit($activity->details, 100) : 'Perubahan dilakukan' }}
                                            </p>
                                            <div class="text-xs text-gray-500">
                                                <p>User: {{ $activity->user_id ?? 'System' }}</p>
                                                <p>IP: {{ $activity->ip_address ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Aktivitas Terakhir yang Dihapus -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-white/20 rounded-full mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-white">Kartu yang Dihapus</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($recentDeleted->isEmpty())
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg">Tidak ada kartu yang dihapus.</p>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($recentDeleted as $activity)
                                        <div class="border border-red-200 rounded-lg p-4 bg-red-50 hover:shadow-md transition-shadow duration-200">
                                            <div class="flex items-start justify-between mb-2">
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                    Dihapus
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $activity->deleted_at->diffForHumans() }}</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $activity->details ? Str::limit($activity->details, 100) : 'Kartu telah dihapus' }}
                                            </p>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.activities.restore', $activity->id) }}" 
                                                   class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition">
                                                    Pulihkan
                                                </a>
                                                <a href="{{ route('admin.activities.permanentDelete', $activity->id) }}" 
                                                   class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus permanen?')">
                                                    Hapus Permanen
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Aktivitas Terakhir yang Ditambahkan -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-white/20 rounded-full mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold text-white">Aktivitas Terakhir yang Ditambahkan</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($recentAdded->isEmpty())
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-lg">Belum ada aktivitas yang ditambahkan.</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($recentAdded as $activity)
                                        <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                                            <div class="flex items-start justify-between mb-2">
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    Ditambahkan
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $activity->timestamp->diffForHumans() }}</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $activity->details ? Str::limit($activity->details, 100) : 'Kartu baru ditambahkan' }}
                                            </p>
                                            <div class="text-xs text-gray-500 mt-2">
                                                <p>User: {{ $activity->user_id ?? 'System' }}</p>
                                                <p>IP: {{ $activity->ip_address ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
