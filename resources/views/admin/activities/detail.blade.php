@extends('admin.layouts.app')

@section('content')
    <div class="max-w-full mx-auto px-4 py-6 min-h-screen  -mt-8 md:-mt-10">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1
                    class="text-3xl md:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">
                    Detail Kartu
                </h1>
                <p class="text-sm text-gray-500 mt-1">Informasi detail kartu yang diperbarui, ditambahkan, atau dihapus.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-medium rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <hr class="my-6 border-gray-200">

        <div class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3 shadow-md">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Kartu yang Diperbarui</h2>
                </div>
                <button id="toggle-updated-cards"
                    class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-colors duration-300 text-sm">
                    Lihat Semua
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if($recentUpdated->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Belum ada kartu yang diperbarui.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
                            id="updated-cards-container">
                            @foreach($recentUpdated as $index => $activity)
                                @php
                                    $cardData = $activity->new_values ?? $activity->old_values ?? [];
                                    $card = is_array($cardData) ? (object) $cardData : $cardData;
                                @endphp
                                <div
                                    class="card-item bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden {{ $index >= 5 ? 'hidden' : '' }}">
                                    @if(!empty($card->image_url))
                                        <div class="relative">
                                            <img src="{{ Storage::url($card->image_url) }}" alt="{{ $card->title ?? 'Card Image' }}"
                                                class="w-full h-48 object-cover">
                                        </div>
                                    @endif

                                    <div class="p-5">
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">
                                            {{ $card->title ?? 'Untitled Card' }}
                                        </h3>

                                        @if(!empty($card->description))
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $card->description }}
                                            </p>
                                        @endif

                                        @if(!empty($card->external_link))
                                            <a href="{{ $card->external_link }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                Kunjungi Link
                                            </a>
                                        @endif

                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 font-semibold rounded-full">
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

        <hr class="my-6 border-gray-200">

        <div class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3 shadow-md">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Kartu yang Dihapus</h2>
                </div>
                <button id="toggle-deleted-cards"
                    class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-colors duration-300 text-sm">
                    Lihat Semua
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if($recentDeleted->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada kartu yang dihapus.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
                            id="deleted-cards-container">
                            @foreach($recentDeleted as $index => $activity)
                                @php
                                    $cardData = $activity->old_values ?? [];
                                    $card = is_array($cardData) ? (object) $cardData : $cardData;
                                @endphp
                                <div
                                    class="card-item bg-white border border-red-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden {{ $index >= 5 ? 'hidden' : '' }}">
                                    @if(!empty($card->image_url))
                                        <div class="relative">
                                            <img src="{{ Storage::url($card->image_url) }}" alt="{{ $card->title ?? 'Card Image' }}"
                                                class="w-full h-48 object-cover opacity-75">
                                        </div>
                                    @endif

                                    <div class="p-5">
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">
                                            {{ $card->title ?? 'Untitled Card' }}
                                        </h3>

                                        @if(!empty($card->description))
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $card->description }}
                                            </p>
                                        @endif

                                        @if(!empty($card->external_link))
                                            <a href="{{ $card->external_link }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center text-sm text-red-600 hover:text-red-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                Kunjungi Link
                                            </a>
                                        @endif

                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                                <span class="px-3 py-1 bg-red-100 text-red-800 font-semibold rounded-full">
                                                    Dihapus
                                                </span>
                                                <span>{{ $activity->timestamp->diffForHumans() }}</span>
                                            </div>
                                        <div class="flex space-x-2">
                                                <button type="button" 
                                                    class="flex-1 text-center px-3 py-2 bg-green-500 text-white text-xs rounded-lg hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 restore-btn"
                                                    data-activity-id="{{ $activity->id }}"
                                                    data-card-title="{{ $card->title ?? 'Untitled Card' }}">
                                                    Pulihkan
                                                </button>
                                                <button type="button"
                                                    class="flex-1 text-center px-3 py-2 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 delete-btn"
                                                    data-activity-id="{{ $activity->id }}"
                                                    data-card-title="{{ $card->title ?? 'Untitled Card' }}">
                                                    Hapus Permanen
                                                </button>
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

        <hr class="my-6 border-gray-200">

        <div class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 shadow-md">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Kartu yang Ditambahkan</h2>
                </div>
                <button id="toggle-added-cards"
                    class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-colors duration-300 text-sm">
                    Lihat Semua
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if($recentAdded->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Belum ada kartu yang ditambahkan.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
                            id="added-cards-container">
                            @foreach($recentAdded as $index => $activity)
                                @php
                                    $cardData = $activity->new_values ?? [];
                                    $card = is_array($cardData) ? (object) $cardData : $cardData;
                                @endphp
                                <div
                                    class="card-item bg-white border border-green-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden {{ $index >= 5 ? 'hidden' : '' }}">
                                    @if(!empty($card->image_url))
                                        <div class="relative">
                                            <img src="{{ Storage::url($card->image_url) }}" alt="{{ $card->title ?? 'Card Image' }}"
                                                class="w-full h-48 object-cover">
                                        </div>
                                    @endif

                                    <div class="p-5">
                                        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2">
                                            {{ $card->title ?? 'Untitled Card' }}
                                        </h3>

                                        @if(!empty($card->description))
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                                {{ $card->description }}
                                            </p>
                                        @endif

                                        @if(!empty($card->external_link))
                                            <a href="{{ $card->external_link }}" target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center text-sm text-green-600 hover:text-green-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                                Kunjungi Link
                                            </a>
                                        @endif

                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span class="px-3 py-1 bg-green-100 text-green-800 font-semibold rounded-full">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to handle toggling cards for a specific section
            function setupCardToggle(containerId, buttonId) {
                const container = document.getElementById(containerId);
                const button = document.getElementById(buttonId);
                const initialCount = 5;

                // Check if the container exists and has more than 0 items, then set initial state
                if (container && container.querySelectorAll('.card-item').length > initialCount) {
                    button.textContent = 'Lihat Semua';
                    container.dataset.expanded = 'false';
                }

                button.addEventListener('click', function () {
                    const isExpanded = container.dataset.expanded === 'true';

                    if (isExpanded) {
                        // Collapse cards
                        if (container) {
                            container.querySelectorAll('.card-item').forEach((card, index) => {
                                if (index >= initialCount) {
                                    card.classList.add('hidden');
                                }
                            });
                        }
                        button.textContent = 'Lihat Semua';
                        container.dataset.expanded = 'false';
                    } else {
                        // Expand cards
                        if (container) {
                            container.querySelectorAll('.card-item').forEach(card => card.classList.remove('hidden'));
                        }
                        button.textContent = 'Ringkas';
                        container.dataset.expanded = 'true';
                    }
                });
            }

            // Setup for each section
            setupCardToggle('updated-cards-container', 'toggle-updated-cards');
            setupCardToggle('deleted-cards-container', 'toggle-deleted-cards');
            setupCardToggle('added-cards-container', 'toggle-added-cards');

            // SweetAlert and AJAX functionality for deleted cards
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Function to show confirmation dialog
            function showConfirmation(title, text, confirmButtonText = 'Ya') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }

            // Function to show success message
            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            }

            // Function to show error message
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    confirmButtonText: 'OK'
                });
            }

            // Function to handle restore action
            async function handleRestore(activityId, cardTitle) {
                try {
                    const result = await showConfirmation(
                        'Pulihkan Kartu',
                        `Apakah Anda yakin ingin memulihkan kartu "${cardTitle}"?`,
                        'Ya, Pulihkan'
                    );

                    if (result.isConfirmed) {
                        const response = await fetch(`/admin/activities/${activityId}/restore`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showSuccess(data.message || 'Kartu berhasil dipulihkan');
                            // Remove the card item from the UI
                            const cardElement = document.querySelector(`.restore-btn[data-activity-id="${activityId}"]`).closest('.card-item');
                            if (cardElement) {
                                cardElement.remove();
                            }
                        } else {
                            showError(data.message || 'Gagal memulihkan kartu');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memulihkan kartu');
                }
            }

            // Function to handle permanent delete action
            async function handlePermanentDelete(activityId, cardTitle) {
                try {
                    const result = await showConfirmation(
                        'Hapus Permanen',
                        `Apakah Anda yakin ingin menghapus permanen kartu "${cardTitle}"? Tindakan ini tidak dapat dibatalkan!`,
                        'Ya, Hapus Permanen'
                    );

                    if (result.isConfirmed) {
                        const response = await fetch(`/admin/activities/${activityId}/permanent-delete`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showSuccess(data.message || 'Kartu berhasil dihapus permanen');
                            // Remove the card item from the UI
                            const cardElement = document.querySelector(`.delete-btn[data-activity-id="${activityId}"]`).closest('.card-item');
                            if (cardElement) {
                                cardElement.remove();
                            }
                        } else {
                            showError(data.message || 'Gagal menghapus kartu permanen');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat menghapus kartu');
                }
            }

            // Add event listeners to restore buttons
            document.querySelectorAll('.restore-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const activityId = this.dataset.activityId;
                    const cardTitle = this.dataset.cardTitle;
                    handleRestore(activityId, cardTitle);
                });
            });

            // Add event listeners to delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const activityId = this.dataset.activityId;
                    const cardTitle = this.dataset.cardTitle;
                    handlePermanentDelete(activityId, cardTitle);
                });
            });
        });
    </script>
@endsection
