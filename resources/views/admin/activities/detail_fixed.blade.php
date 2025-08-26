@extends('admin.layouts.app')

@section('content')
    <div class="max-w-full mx-auto px-4 py-6 min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-white">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">
                    Detail Kartu
                </h1>
                <p class="text-sm text-gray-600 mt-1">Informasi detail kartu yang diperbarui, ditambahkan, atau dihapus</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Kartu yang Diperbarui -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 shadow-sm">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Kartu yang Diperbarui</h2>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 md:p-6">
                    @if($recentUpdated->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">Belum ada kartu yang diperbarui.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($recentUpdated as $activity)
                                @php
                                    $cardData = $activity->new_values ?? $activity->old_values ?? [];
                                    $card = is_array($cardData) ? (object)$cardData : $cardData;
                                @endphp
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                                    <!-- Image -->
                                    @if(!empty($card->image_url))
                                        <div class="relative">
                                            @php
                                                $imagePath = $card->image_url;
                                                if (strpos($imagePath, 'storage/') === 0) {
                                                    $imagePath = substr($imagePath, 8);
                                                }
                                                $imagePath = ltrim($imagePath, '/');
                                            @endphp
                                            <img src="{{ asset('storage/' . $imagePath) }}" 
                                                 alt="{{ $card->title ?? 'Card Image' }}" 
                                                 class="w-full h-48 object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400" style="display:none;">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Content -->
                                    <div class="p-4">
                                        <!-- Title -->
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">
                                            {{ $card->title ?? 'Untitled Card' }}
                                        </h3>
                                        
                                        <!-- Description -->
                                        @if(!empty($card->description))
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $card->description }}
                                            </p>
                                        @endif
                                        
                                        <!-- External Link -->
                                        @if(!empty($card->external_link))
                                            <a href="{{ $card->external_link }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                Kunjungi Link
                                            </a>
                                        @endif
                                        
                                        <!-- Activity Info -->
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                                                    Diperbarui
                                                </span>
                                                <span>{{ $activity->timestamp->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-400">
                                                User: {{ $activity->user_id ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kartu yang Dihapus -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 shadow-sm">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Kartu yang Dihapus</h2>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 md:p-6">
                    @if($recentDeleted->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">Tidak ada kartu yang dihapus.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($recentDeleted as $activity)
                                @php
                                    $cardData = $activity->old_values ?? [];
                                    $card = is_array($cardData) ? (object)$cardData : $cardData;
                                @endphp
                                <div class="bg-white border border-red-200 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                                    <!-- Image -->
                                    @if(!empty($card->image_url))
                                        <div class="relative">
                                            @php
                                                $imagePath = $card->image_url;
                                                if (strpos($imagePath, 'storage/') === 0) {
                                                    $imagePath = substr($imagePath, 8);
                                                }
                                                $imagePath = ltrim($imagePath, '/');
                                            @endphp
                                            <img src="{{ asset('storage/' . $imagePath) }}" 
                                                 alt="{{ $card->title ?? 'Card Image' }}" 
                                                 class="w-full h-48 object-cover opacity-75"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400" style="display:none;">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Content -->
                                    <div class="p-4">
                                        <!-- Title -->
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">
                                            {{ $card->title ?? 'Untitled Card' }}
                                        </h3>
                                        
                                        <!-- Description -->
                                        @if(!empty($card->description))
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $card->description }}
                                            </p>
                                        @endif
                                        
                                        <!-- External Link -->
                                        @if(!empty($card->external_link))
                                            <a href="{{ $card->external_link }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center text-sm text-red-600 hover:text-red-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                Kunjungi Link
                                            </a>
                                        @endif
                                        
                                        <!-- Activity Info & Actions -->
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">
                                                    Dihapus
                                                </span>
                                                <span>{{ $activity->timestamp->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.activities.restore', $activity->id) }}" 
                                                   class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                    Pulihkan
                                                </a>
                                                <a href="{{ route('admin.activities.permanentDelete', $activity->id) }}" 
                                                   class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus permanen?')">
                                                    Hapus Permanen
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kartu yang Ditambahkan -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 shadow-sm">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Kartu yang Ditambahkan</h2>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 md:p-6">
                    @if($recentAdded->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg">Belum ada kartu yang ditambahkan.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($recentAdded as $activity)
                                @php
                                    $cardData = $activity->new_values ?? [];
                                    $card = is_array($cardData) ? (object)$cardData : $cardData;
                                @endphp
                                <div class="bg-white border border-green-200 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                                    <!-- Image -->
                                    @if(!empty($card->image_url))
                                        <div class="relative">
                                            @php
                                                $imagePath = $card->image_url;
                                                if (strpos($imagePath, 'storage/') === 0) {
                                                    $imagePath = substr($imagePath, 8);
                                                }
                                                $imagePath = ltrim($imagePath, '/');
                                            @endphp
                                            <img src="{{ asset('storage/' . $imagePath) }}" 
                                                 alt="{{ $card->title ?? 'Card Image' }}" 
                                                 class="w-full h-48 object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400" style="display:none;">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Content -->
                                    <div class="p-4">
                                        <!-- Title -->
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">
                                            {{ $card->title ?? 'Untitled Card' }}
                                        </h3>
                                        
                                        <!-- Description -->
                                        @if(!empty($card->description))
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $card->description }}
                                            </p>
                                        @endif
                                        
                                        <!-- External Link -->
                                        @if(!empty($card->external_link))
                                            <a href="{{ $card->external_link }}" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center text-sm text-green-600 hover:text-green-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                Kunjungi Link
                                            </a>
                                        @endif
                                        
                                        <!-- Activity Info -->
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                                    Ditambahkan
                                                </span>
                                                <span>{{ $activity->timestamp->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-400">
                                                User: {{ $activity->user_id ?? 'System' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
