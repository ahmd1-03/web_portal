@extends('frontend.home')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Card Detail Container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <!-- Card Image -->
            @if($card->image_url)
                <div class="relative">
                    <img src="{{ Storage::url($card->image_url) }}" 
                         alt="{{ $card->title ?? 'Card Image' }}" 
                         class="w-full h-64 md:h-96 object-cover">
                    @if($card->trashed())
                        <div class="absolute top-4 right-4">
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Dihapus
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Card Content -->
            <div class="p-6 md:p-8">
                <!-- Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                    {{ $card->title ?? 'Judul Tidak Tersedia' }}
                </h1>

                <!-- Description -->
                <div class="prose prose-gray max-w-none mb-6">
                    @if($card->description)
                        <p class="text-gray-700 leading-relaxed">
                            {!! nl2br(e($card->description)) !!}
                        </p>
                    @else
                        <p class="text-gray-500 italic">
                            Deskripsi tidak tersedia.
                        </p>
                    @endif
                </div>

                <!-- External Link -->
                @if($card->external_link)
                    <div class="mt-6">
                        <a href="{{ $card->external_link }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Kunjungi Link
                        </a>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Status:</span>
                            <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium
                                {{ $card->trashed() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $card->trashed() ? 'Dihapus' : 'Aktif' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-medium">Dibuat:</span>
                            <span class="ml-2">{{ $card->created_at ? $card->created_at->format('d M Y H:i') : 'Tidak tersedia' }}</span>
                        </div>
                        @if($card->trashed())
                            <div>
                                <span class="font-medium">Dihapus:</span>
                                <span class="ml-2">{{ $card->deleted_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Restore Button (if deleted) -->
                @if($card->trashed() && auth()->guard('admin')->check())
                    <div class="mt-6">
                        <form action="{{ route('admin.deleted-cards.restore', $card) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Pulihkan Kartu
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
