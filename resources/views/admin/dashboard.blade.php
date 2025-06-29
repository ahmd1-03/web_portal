@extends('admin.layouts.app')

@section('content')
    {{-- Konten utama dashboard admin --}}
    <main class="p-4 md:p-6 transition-margin duration-300" :>


        {{-- Bagian header dengan judul dan sambutan --}}
        <section class="mb-6 md:mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6 mb-4 md:mb-6">
                <div class="order-2 md:order-1">
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">KARAWANG</h1>
                    <h2 class="text-2xl md:text-3xl font-semibold text-green-700 mt-1">Selamat Datang Admin!</h2>
                    <p class="text-gray-700 mt-2 md:mt-3 text-sm md:text-base max-w-md leading-relaxed">
                        Dashboard management portal Karawang - Pantau dan kelola sistem dengan mudah
                    </p>
                </div>
                <div class="order-1 md:order-2">
                    {{-- Tombol untuk melihat website frontend --}}
                    <a href="/"
                        class="inline-flex items-center px-4 py-2 md:px-6 md:py-3 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg hover:from-emerald-800 hover:to-emerald-700 transition duration-300 shadow-md text-sm md:text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Lihat Website
                    </a>
                </div>
            </div>

            {{-- Link cepat ke manajemen kartu dan pengguna --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6">
                {{-- Link ke halaman manajemen kartu --}}
                <a href="{{ route('admin.cards.index') }}"
                    class="flex items-center p-4 md:p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-md border border-green-200 hover:border-green-400 hover:shadow-lg transition gap-3 md:gap-5">
                    <div class="bg-green-200 text-green-800 p-2 md:p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-7 md:w-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-green-900">Management Kartu</h3>
                        <p class="text-xs md:text-sm text-green-700">Kelola konten kartu</p>
                    </div>
                </a>

                {{-- Link ke halaman manajemen pengguna --}}
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center p-4 md:p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-md border border-purple-200 hover:border-purple-400 hover:shadow-lg transition gap-3 md:gap-5">
                    <div class="bg-purple-200 text-purple-800 p-2 md:p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-7 md:w-7" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base md:text-lg font-semibold text-purple-900">Management Pengguna</h3>
                        <p class="text-xs md:text-sm text-purple-700">Kelola akses admin</p>
                    </div>
                </a>

                {{-- Statistik jumlah kartu --}}
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 md:p-6 rounded-xl shadow-md border border-blue-200 flex items-center gap-3 md:gap-4 hover:shadow-lg transition">
                    <div class="bg-blue-200 text-blue-800 p-2 md:p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl lg:text-3xl font-extrabold text-blue-900">{{ $cardsCount }}</h3>
                        <p class="text-blue-700 text-sm md:text-base font-medium">Jumlah Kartu</p>
                    </div>
                </div>

                {{-- Statistik jumlah admin --}}
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 p-4 md:p-6 rounded-xl shadow-md border border-green-200 flex items-center gap-3 md:gap-4 hover:shadow-lg transition">
                    <div class="bg-green-200 text-green-800 p-2 md:p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-8 md:w-8" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl lg:text-3xl font-extrabold text-green-900">{{ $adminsCount }}</h3>
                        <p class="text-green-700 text-sm md:text-base font-medium">Jumlah Admin</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Bagian aktivitas terakhir --}}
        <section class="bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 md:mb-6">
                <h3 class="text-lg md:text-xl font-bold text-gray-900">Aktivitas Terakhir</h3>
                <a href="#"
                    class="text-blue-600 hover:text-blue-800 text-sm md:text-base font-semibold transition hover:underline">Lihat
                    Semua</a>
            </div>

            {{-- Daftar aktivitas terbaru dengan scroll --}}
            <div class="max-h-[300px] md:max-h-[460px] overflow-y-auto pr-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                    @forelse($recentActivities as $activity)
                        @php $act = (array) $activity; @endphp
                        <div
                            class="flex items-start gap-4 p-4 rounded-xl transition cursor-default border {{ isset($act['type']) && $act['type'] === 'card' ? 'border-blue-300 bg-blue-50 hover:bg-blue-100 shadow-md hover:shadow-lg' : 'border-green-300 bg-green-50 hover:bg-green-100 shadow-md hover:shadow-lg' }}">
                            <div class="mt-1 flex-shrink-0">
                                @if(isset($act['type']) && $act['type'] === 'card')
                                    <div class="bg-blue-500 text-white p-3 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                    </div>
                                @elseif(isset($act['type']) && $act['type'] === 'admin')
                                    <div class="bg-green-500 text-white p-3 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-gray焦点
                                                                                                            font-semibold text-gray-900 text-sm md:text-base truncate">
                                    @if(isset($act['action']) && $act['action'] === 'updated')
                                        {{ isset($act['type']) ? ucfirst($act['type']) : 'Tipe tidak diketahui' }} diperbarui:
                                        <span class="font-bold">{{ $act['title'] ?? 'Judul tidak diketahui' }}</span>
                                        @if(!empty($act['details']))
                                            <ul class="text-xs text-gray-600 mt-2 list-disc list-inside max-h-20 overflow-y-auto">
                                                @foreach(json_decode($act['details'], true) as $field => $change)
                                                    <li>
                                                        <strong>{{ ucfirst($field) }}:</strong>
                                                        @if(is_array($change))
                                                            <span>Diubah dari "{{ $change['old'] }}" menjadi "{{ $change['new'] }}"</span>
                                                        @else
                                                            <span>{{ $change }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    @elseif(isset($act['action']) && $act['action'] === 'created')
                                    {{ isset($act['type']) ? ucfirst($act['type']) : 'Tipe tidak diketahui' }} baru ditambahkan:
                                    <span class="font-bold">{{ $act['title'] ?? 'Judul tidak diketahui' }}</span>
                                    @if(!empty($act['details']))
                                        <ul class="text-xs text-gray-600 mt-2 list-disc list-inside max-h-20 overflow-y-auto">
                                            @foreach(json_decode($act['details'], true) as $field => $value)
                                                <li>
                                                    <strong>{{ ucfirst($field) }}:</strong> {{ $value }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @elseif(isset($act['action']) && $act['action'] === 'deleted')
                                    {{ isset($act['type']) ? ucfirst($act['type']) : 'Tipe tidak diketahui' }} dihapus:
                                    <span class="font-bold">{{ $act['title'] ?? 'Judul tidak diketahui' }}</span>
                                @else
                                    {{ isset($act['type']) ? ucfirst($act['type']) : 'Tipe tidak diketahui' }}
                                    {{ $act['action'] ?? 'aksi tidak diketahui' }}: <span
                                        class="font-bold">{{ $act['title'] ?? 'Judul tidak diketahui' }}</span>
                                @endif
                                </p>
                                <p class="text-gray-500 text-xs mt-1 whitespace-nowrap">
                                    {{ isset($act['timestamp']) ? \Carbon\Carbon::parse($act['timestamp'])->diffForHumans() : 'Waktu tidak diketahui' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4 md:py-6 col-span-2">Tidak ada aktivitas terbaru</p>
                    @endforelse
                </div>
            </div>
        </section>

    </main>
@endsection