@extends('admin.layouts.app')

@section('content')
    <!-- ===================== BAGIAN KONTAINER UTAMA ===================== -->
    <!-- Kontainer utama untuk halaman dengan padding responsif dan latar belakang gradasi -->
    <div class="max-w-6xl mx-auto px-2 sm:px-4 lg:px-6 py-5 min-h-screen"
        x-data="cardManager({{ json_encode(['from' => $cards->firstItem() ?: 0, 'to' => $cards->lastItem() ?: 0, 'total' => $cards->total()]) }})"
        x-cloak>
        <!-- Memuat library SweetAlert2 untuk notifikasi interaktif -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- ===================== BAGIAN HEADER DAN PENCARIAN ===================== -->
        <!-- Header untuk judul dan form pencarian -->
        <div class="mb-4 px-2 sm:px-4 lg:px-  -mt-8 md:-mt-10">
            <!-- Flex container untuk tata letak judul dan form -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <!-- Bagian judul halaman -->
                <div>
                    <!-- Judul halaman dengan efek gradasi -->
                    <h1
                        class="text-xl md:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 mb-2">
                        Manajemen Kartu Konten
                    </h1>
                    <!-- Deskripsi singkat halaman -->
                    <p class="text-xs text-gray-600 mb-4">Kelola konten kartu yang ditampilkan di halaman utama</p>
                </div>

                <!-- Bagian form pencarian dan dropdown -->
                <div class="flex flex-col md:flex-row gap-3">
                    <!-- Form pencarian kartu dengan indikator loading -->
                    <form method="GET" action="{{ url()->current() }}" class="relative w-full md:w-56"
                        x-data="{ searching: false }" @submit.prevent="handleSearch" aria-label="Form pencarian kartu">
                        <div class="relative mb-3">
                            <!-- Input untuk kata kunci pencarian -->
                            <input type="text" name="search" placeholder="Cari kartu..." value="{{ request('search') }}"
                                x-model="searchQuery" x-on:input.debounce.500ms="searching = true" class="w-full text-xs rounded-lg px-3 py-1.5 pr-8 border border-gray-300 shadow-sm 
                                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 
                                                  transition-all duration-300 hover:border-emerald-300 hover:shadow-md"
                                aria-label="Masukkan kata kunci pencarian kartu" />
                            <!-- Tombol submit pencarian dengan ikon -->
                            <button type="submit"
                                class="absolute top-1/2 right-1.5 -translate-y-1/2 text-gray-400 hover:text-emerald-600 
                                                   flex items-center justify-center focus:outline-none transition-all duration-300"
                                title="Cari kartu">
                                <!-- Ikon pencarian saat tidak loading -->
                                <template x-if="!searching">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </template>
                                <!-- Ikon loading saat pencarian sedang berlangsung -->
                                <template x-if="searching">
                                    <svg class="animate-spin w-4 h-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </form>

                    <!-- Dropdown untuk memilih jumlah data per halaman -->
                    <div class="relative">
                        <select x-model="perPage" x-on:change="changePerPage" class="w-full md:w-28 text-xs rounded-lg px-3 py-1.5 border border-gray-300 shadow-sm 
                                               focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 
                                               transition-all duration-300 hover:border-emerald-300 hover:shadow-md"
                            aria-label="Jumlah data per halaman">
                            <!-- Loop untuk opsi jumlah data per halaman -->
                            @for ($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ request('per_page') == $i ? 'selected' : '' }}>{{ $i }} per halaman
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== BAGIAN KARTU AKSI ===================== -->
        <!-- Grid untuk menampilkan kartu aksi -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-4 px-2 sm:px-4 lg:px-0">
            <!-- Kartu untuk menambah kartu baru -->
            <div @click="openCreateModal = true" class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-lg shadow-sm p-3 border border-emerald-100 
                                hover:border-emerald-300 hover:shadow-lg transition-all duration-300 cursor-pointer 
                                hover:-translate-y-1 transform" role="button" aria-label="Tambah kartu baru">
                <div class="flex items-center gap-2">
                    <!-- Ikon untuk tombol tambah kartu -->
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 flex items-center 
                                         justify-center text-white group-hover:from-emerald-600 group-hover:to-teal-600 
                                         transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <!-- Teks untuk tombol tambah kartu -->
                    <div>
                        <h3
                            class="font-medium text-sm text-gray-800 group-hover:text-emerald-700 transition-colors duration-300">
                            Tambah Kartu Baru
                        </h3>
                        <p class="text-xs text-gray-500 group-hover:text-gray-600 transition-colors duration-300">
                            Buat konten kartu baru
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kartu untuk menampilkan total kartu -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg shadow-sm p-3 border border-blue-100 
                                hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-2">
                    <!-- Ikon untuk total kartu -->
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center 
                                         justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <!-- Teks untuk total kartu -->
                    <div>
                        <h3 class="font-medium text-sm text-gray-800">Total Kartu</h3>
                        <p id="totalCount" class="text-xl font-bold text-blue-700">{{ $cards->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Kartu untuk menampilkan jumlah kartu aktif -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-sm p-3 border border-green-100 
                                hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-2">
                    <!-- Ikon untuk kartu aktif -->
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-green-500 to-emerald-500 flex items-center 
                                         justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <!-- Teks untuk kartu aktif -->
                    <div>
                        <h3 class="font-medium text-sm text-gray-800">Kartu Aktif</h3>
                        <p id="activeCount" class="text-xl font-bold text-green-700">
                            {{ $cards->where('is_active', true)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kartu untuk menampilkan jumlah kartu tidak aktif -->
            <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-lg shadow-sm p-3 border border-red-100 
                                hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-2">
                    <!-- Ikon untuk kartu tidak aktif -->
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 flex items-center 
                                         justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <!-- Teks untuk kartu tidak aktif -->
                    <div>
                        <h3 class="font-medium text-sm text-gray-800">Kartu Tidak Aktif</h3>
                        <p id="inactiveCount" class="text-xl font-bold text-red-700">
                            {{ $cards->where('is_active', false)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== BAGIAN NOTIFIKASI SUKSES ===================== -->
        <!-- Menampilkan notifikasi sukses dari sesi jika ada -->
        @if(session('success'))
            <div class="mb-4 px-2 sm:px-4 lg:px-0">
                <div
                    class="p-3 text-xs bg-gradient-to-r from-emerald-100 to-emerald-50 border-l-4 border-emerald-500 
                                            text-emerald-700 rounded-lg transition-all duration-300 flex items-center shadow-sm">
                    <!-- Ikon untuk notifikasi sukses -->
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Pesan notifikasi sukses -->
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- ===================== BAGIAN TABEL KARTU ===================== -->
        <!-- Tabel untuk menampilkan daftar kartu -->
        <div class="px-2 sm:px-4 lg:px-0 mb-4">
            <div class="bg-white rounded-lg shadow-lg border border-gray-300 overflow-hidden">
                <div class="overflow-x-auto">
                    <!-- Tabel daftar kartu -->
                    <table class="w-full border border-gray-300">
                        <!-- Header tabel -->
                        <thead class="bg-emerald-600 border border-emerald-600">
                            <tr>
                                <!-- Kolom nomor urut -->
                                <th class="px-3 py-1.5 text-left text-xs font-medium text-white uppercase tracking-wider 
                                                   border-r border-emerald-600">No</th>
                                <!-- Kolom judul kartu -->
                                <th class="px-3 py-1.5 text-left text-xs font-medium text-white uppercase tracking-wider 
                                                   border-r border-emerald-600">Judul</th>
                                <!-- Kolom deskripsi (tersembunyi di layar kecil) -->
                                <th class="px-3 py-1.5 text-left text-xs font-medium text-white uppercase tracking-wider 
                                                   hidden sm:table-cell border-r border-emerald-600">Deskripsi</th>
                                <!-- Kolom status kartu -->
                                <th class="px-3 py-1.5 text-left text-xs font-medium text-white uppercase tracking-wider 
                                                   border-r border-emerald-600">Status</th>
                                <!-- Kolom link eksternal (tersembunyi di layar kecil) -->
                                <th class="px-3 py-1.5 text-left text-xs font-medium text-white uppercase tracking-wider 
                                                   hidden md:table-cell border-r border-emerald-600">Link</th>
                                <!-- Kolom gambar kartu -->
                                <th class="px-3 py-1.5 text-left text-xs font-medium text-white uppercase tracking-wider 
                                                   border-r border-emerald-600">Gambar</th>
                                <!-- Kolom aksi -->
                                <th class="px-3 py-1.5 text-right text-xs font-medium text-white uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <!-- Body tabel -->
                        <tbody class="bg-white divide-y divide-gray-200 border border-gray-300">
                            @forelse($cards as $index => $card)
                                        <!-- Baris untuk setiap kartu -->
                                        <tr
                                            class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150 group">
                                            <!-- Nomor urut kartu -->
                                            <td class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500 group-hover:text-gray-700 
                                                                          transition-colors duration-300">
                                                {{ ($cards->currentPage() - 1) * $cards->perPage() + $index + 1 }}
                                            </td>
                                            <!-- Judul kartu -->
                                            <td class="px-3 py-1.5 whitespace-nowrap">
                                                <div class="text-xs font-medium text-gray-900 group-hover:text-emerald-700 
                                                                                transition-colors duration-300">
                                                    {{ $card->title }}
                                                </div>
                                            </td>
                                            <!-- Deskripsi kartu (tersembunyi di layar kecil) -->
                                            <td class="px-3 py-1.5 whitespace-nowrap hidden sm:table-cell">
                                                <div class="text-xs text-gray-500 max-w-xs truncate group-hover:text-gray-700 
                                                                                transition-colors duration-300">
                                                    {{ Str::limit($card->description, 50) }}
                                                </div>
                                            </td>
                                            <!-- Status kartu (aktif/nonaktif) -->
                                            <td class="px-3 py-1.5 whitespace-nowrap">
                                                <span x-data="{ isActive: {{ $card->is_active ? 'true' : 'false' }} }"
                                                    :class="isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                    class="px-1.5 py-0.5 text-xs font-semibold rounded-full">
                                                    <span x-text="isActive ? 'Aktif' : 'Nonaktif'"></span>
                                                </span>
                                            </td>
                                            <!-- Link eksternal kartu (tersembunyi di layar kecil) -->
                                            <td class="px-3 py-1.5 whitespace-nowrap hidden md:table-cell">
                                                <a href="{{ $card->external_link }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 hover:underline transition-all 
                                                                              duration-300 inline-flex items-center group/link">
                                                    {{ Str::limit($card->external_link, 20) }}
                                                    <!-- Ikon untuk link eksternal -->
                                                    <svg class="w-3 h-3 ml-1 group-hover/link:translate-x-1 transition-transform duration-300"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </td>
                                            <!-- Gambar kartu dengan modal pratinjau -->
                                            <td class="px-3 py-1.5 whitespace-nowrap">
                                                <div x-data="{ showImageModal: false }">
                                                    <!-- Gambar kartu -->
                                                    <img src="{{ $card->image_url }}" @click="showImageModal = true"
                                                        alt="Gambar Kartu {{ $card->title }}" class="h-10 w-10 sm:h-12 sm:w-12 object-cover rounded-md cursor-pointer
                                border-2 border-gray-200 hover:border-emerald-400 transition-all duration-300" />
                                                    <!-- Modal pratinjau gambar -->
                                                    <div x-show="showImageModal" x-cloak @click.away="showImageModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-3 backdrop-blur-sm
                                                                                    bg-black/80" role="dialog"
                                                        aria-labelledby="imageModalTitle">
                                                        <div class="relative max-w-3xl max-h-[85vh]">
                                                            <!-- Gambar dalam modal -->
                                                            <img src="{{ $card->image_url }}" alt="Gambar Kartu {{ $card->title }}"
                                                                class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl" />
                                                            <!-- Tombol tutup modal -->
                                                            <button @click="showImageModal = false"
                                                                class="absolute top-3 right-3 p-1.5 rounded-full bg-white/80
                                                                                               hover:bg-white text-gray-800 shadow-md transition-all duration-300"
                                                                aria-label="Tutup modal pratinjau gambar">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- Kolom aksi (edit, hapus, toggle status) -->
                                            <td class="px-3 py-1.5 whitespace-nowrap text-right text-xs font-medium">
                                                <div class="flex justify-end space-x-1.5">
                                                    <!-- Tombol edit kartu -->
                                                    <button @click="openEditModal({{ $card->id }})"
                                                        class="flex items-center gap-1.5 px-1.5 py-0.5 rounded-lg transition-all duration-300 
                                                                                       bg-blue-600 text-white hover:bg-blue-700 group/edit" title="Edit kartu"
                                                        aria-label="Edit kartu {{ $card->title }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-3 h-3 group-hover/edit:scale-110 transition-transform duration-300"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" viewBox="0 0 24 24">
                                                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                        </svg>
                                                    </button>
                                                    <!-- Tombol hapus kartu -->
                                                    <button @click="openDeleteModal({{ $card->id }})"
                                                        class="flex items-center gap-1.5 px-1.5 py-0.5 rounded-lg transition-all duration-300 
                                                                                       bg-red-600 text-white hover:bg-red-700 group/delete" title="Hapus kartu"
                                                        aria-label="Hapus kartu {{ $card->title }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-3 h-3 group-hover/delete:scale-110 transition-transform duration-300"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" viewBox="0 0 24 24">
                                                            <polyline points="3 6 5 6 21 6" />
                                                            <path
                                                                d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5-3h4a2 2 0 0 1 2 2v1H8V5a2 2 0 0 1 2-2z" />
                                                        </svg>
                                                    </button>
                                                    <!-- Tombol toggle status aktif/nonaktif -->
                                                    <button
                                                        @click="toggleCardStatus({{ $card->id }}, {{ $card->is_active ? 'true' : 'false' }})"
                                                        class="flex items-center gap-1.5 px-1.5 py-0.5 rounded-lg transition-all duration-300 
                                                                                       {{ $card->is_active ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-red-600 text-white hover:bg-red-700' }}"
                                                        title="{{ $card->is_active ? 'Nonaktifkan kartu' : 'Aktifkan kartu' }}"
                                                        aria-label="{{ $card->is_active ? 'Nonaktifkan kartu' : 'Aktifkan kartu' }} {{ $card->title }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-3 h-3 transition-transform duration-300 group-hover:scale-110"
                                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" viewBox="0 0 24 24">
                                                            @if(!$card->is_active)
                                                                <!-- Ikon untuk status nonaktif -->
                                                                <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
                                                                <line x1="12" y1="2" x2="12" y2="12" />
                                                            @else
                                                                <!-- Ikon untuk status aktif -->
                                                                <circle cx="12" cy="12" r="10" />
                                                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                                            @endif
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                            @empty
                                <!-- Baris jika tidak ada data kartu -->
                                <tr>
                                    <td colspan="7" class="px-3 py-3 text-center text-xs text-gray-500">
                                        Tidak ada data kartu yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Bagian pagination -->
                <div class="px-3 sm:px-4 py-3 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <!-- Menampilkan link pagination -->
                    {{ $cards->appends(request()->except('page'))->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>

        <!-- ===================== BAGIAN MODAL TAMBAH KARTU ===================== -->
        <!-- Modal untuk menambah kartu baru -->
        <div x-show="openCreateModal" x-cloak x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 backdrop-blur-sm bg-black/50">
            <div x-show="openCreateModal" x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="openCreateModal = false" class="bg-white rounded-lg shadow-2xl w-full max-w-sm max-h-[85vh] overflow-y-auto 
                                border-2 border-emerald-200">
                <!-- Header modal tambah kartu -->
                <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-t-lg">
                    <!-- Judul modal -->
                    <h2 id="createModalTitle" class="text-lg font-bold text-white">Tambah Kartu Konten</h2>
                    <!-- Deskripsi modal -->
                    <p class="mt-1 text-xs text-emerald-100">Isi form berikut untuk menambahkan kartu konten baru</p>
                </div>
                <!-- Form untuk menambah kartu -->
                <div class="p-4">
                    <input type="hidden" id="store_route" value="{{ route('admin.cards.store') }}">
                    <form x-ref="createForm" @submit.prevent="submitCreateForm" enctype="multipart/form-data"
                        class="space-y-4">
                        @csrf
                        <!-- Input untuk judul kartu -->
                        <div>
                            <label for="create_title" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Judul Kartu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="create_title" required placeholder="Masukkan judul kartu"
                                x-model="createForm.title"
                                :class="errors.title ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
                                class="w-full px-3 py-1.5 text-xs border rounded-lg shadow-sm focus:outline-none 
                                                  focus:ring-2 focus:ring-opacity-50 transition-all duration-300 
                                                  hover:border-emerald-300 hover:shadow-md" aria-required="true"
                                aria-describedby="create_title_error" />
                            <!-- Pesan error untuk judul -->
                            <template x-if="errors.title">
                                <p id="create_title_error" class="mt-1.5 text-xs text-red-600" x-text="errors.title[0]"></p>
                            </template>
                        </div>
                        <!-- Input untuk deskripsi kartu -->
                        <div>
                            <label for="create_description" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="create_description" rows="3" required
                                placeholder="Tulis deskripsi singkat..." x-model="createForm.description"
                                :class="errors.description ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
                                class="w-full px-3 py-1.5 text-xs border rounded-lg shadow-sm focus:outline-none 
                                                     focus:ring-2 focus:ring-opacity-50 transition-all duration-300 resize-none 
                                                     hover:border-emerald-300 hover:shadow-md" aria-required="true"
                                aria-describedby="create_description_error"></textarea>
                            <!-- Pesan error untuk deskripsi -->
                            <template x-if="errors.description">
                                <p id="create_description_error" class="mt-1.5 text-xs text-red-600"
                                    x-text="errors.description[0]"></p>
                            </template>
                        </div>
                        <!-- Input untuk upload gambar -->
                        <div>
                            <label for="create_image_url" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Gambar Kartu <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="create_image_url"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 
                                                      border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 
                                                      transition-colors duration-300 hover:border-emerald-300 hover:shadow-inner">
                                    <!-- Teks placeholder jika belum ada gambar -->
                                    <template x-if="!preview">
                                        <div class="flex flex-col items-center justify-center pt-4 pb-5">
                                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="mb-1 text-xs text-gray-500">Klik untuk upload gambar</p>
                                            <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                        </div>
                                    </template>
                                    <!-- Pratinjau gambar yang diupload -->
                                    <template x-if="preview">
                                        <img :src="preview" alt="Pratinjau Gambar"
                                            class="h-full w-full object-contain p-1.5 rounded-lg">
                                    </template>
                                    <!-- Input file untuk upload gambar -->
                                    <input id="create_image_url" name="image_url" type="file" accept="image/png,image/jpeg"
                                        required
                                        @change="if ($event.target.files[0].size > 2 * 1024 * 1024) { errors.image_url = ['Ukuran file maksimum 2MB']; $event.target.value = null; preview = null; } else { preview = URL.createObjectURL($event.target.files[0]); }"
                                        class="hidden" aria-describedby="create_image_url_error" />
                                </label>
                            </div>
                            <!-- Pesan error untuk gambar -->
                            <template x-if="errors.image_url">
                                <p id="create_image_url_error" class="mt-1.5 text-xs text-red-600"
                                    x-text="errors.image_url[0]"></p>
                            </template>
                        </div>
                        <!-- Input untuk link eksternal -->
                        <div>
                            <label for="create_external_link" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Link Eksternal <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="external_link" id="create_external_link" required
                                placeholder="https://contoh.com" x-model="createForm.external_link"
                                :class="errors.external_link ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
                                class="w-full px-3 py-1.5 text-xs border rounded-lg shadow-sm focus:outline-none 
                                                  focus:ring-2 focus:ring-opacity-50 transition-all duration-300 
                                                  hover:border-emerald-300 hover:shadow-md" aria-required="true"
                                aria-describedby="create_external_link_error" />
                            <!-- Pesan error untuk link eksternal -->
                            <template x-if="errors.external_link">
                                <p id="create_external_link_error" class="mt-1.5 text-xs text-red-600"
                                    x-text="errors.external_link[0]"></p>
                            </template>
                        </div>

                    </form>
                </div>
                <!-- Footer modal dengan tombol aksi -->
                <div class="border-t border-gray-200 px-4 py-3 flex justify-end gap-2 
                                    bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-lg">
                    <!-- Tombol batal -->
                    <button type="button" @click="openCreateModal = false" class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 
                                           shadow-sm text-xs font-medium transition-all duration-300 hover:-translate-y-0.5 
                                           hover:shadow-md" aria-label="Batal menambah kartu">Batal</button>
                    <!-- Tombol simpan -->
                    <button type="button" @click="submitCreateForm" :disabled="loading"
                        class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 
                                           hover:from-emerald-700 hover:to-teal-700 text-white shadow-md text-xs font-medium 
                                           transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed 
                                           flex items-center justify-center min-w-[80px] hover:-translate-y-0.5 hover:shadow-lg" aria-label="Simpan kartu baru">
                        <!-- Teks tombol saat tidak loading -->
                        <span x-show="!loading">Simpan</span>
                        <!-- Teks dan ikon loading saat memproses -->
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===================== BAGIAN MODAL EDIT KARTU ===================== -->
        <!-- Modal untuk mengedit kartu -->
        <div x-show="openEditModalId !== null" x-cloak x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 backdrop-blur-sm bg-black/50" role="dialog"
            aria-labelledby="editModalTitle">
            <div x-show="openEditModalId !== null" x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="openEditModalId = null" class="bg-white rounded-lg shadow-2xl w-full max-w-md max-h-[85vh] overflow-y-auto 
                                border-2 border-blue-200">
                <!-- Header modal edit kartu -->
                <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-lg">
                    <!-- Judul modal -->
                    <h2 id="editModalTitle" class="text-lg font-bold text-white">Edit Kartu Konten</h2>
                    <!-- Deskripsi modal -->
                    <p class="mt-1 text-xs text-blue-100">Perbarui informasi kartu konten</p>
                </div>
                <!-- Form untuk mengedit kartu -->
                <div class="p-4">
                    <form x-ref="editForm" @submit.prevent="submitEditForm" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <!-- Input untuk judul kartu -->
                        <div>
                            <label for="edit_title" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Judul Kartu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="edit_title" required x-model="cardData.title"
                                :class="errors.title ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
                                class="w-full px-3 py-1.5 text-xs border rounded-lg shadow-sm focus:outline-none 
                                                  focus:ring-2 focus:ring-opacity-50 transition-all duration-300 
                                                  hover:border-blue-300 hover:shadow-md" aria-required="true"
                                aria-describedby="edit_title_error" />
                            <!-- Pesan error untuk judul -->
                            <template x-if="errors.title">
                                <p id="edit_title_error" class="mt-1.5 text-xs text-red-600" x-text="errors.title[0]"></p>
                            </template>
                        </div>
                        <!-- Input untuk deskripsi kartu -->
                        <div>
                            <label for="edit_description" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="edit_description" rows="3" required
                                x-model="cardData.description"
                                :class="errors.description ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
                                class="w-full px-3 py-1.5 text-xs border rounded-lg shadow-sm focus:outline-none 
                                                     focus:ring-2 focus:ring-opacity-50 transition-all duration-300 resize-none 
                                                     hover:border-blue-300 hover:shadow-md" aria-required="true"
                                aria-describedby="edit_description_error"></textarea>
                            <!-- Pesan error untuk deskripsi -->
                            <template x-if="errors.description">
                                <p id="edit_description_error" class="mt-1.5 text-xs text-red-600"
                                    x-text="errors.description[0]"></p>
                            </template>
                        </div>
                        <!-- Input untuk gambar kartu -->
                        <div>
                            <label for="edit_image_url" class="block text-xs font-medium text-gray-700 mb-1.5">Gambar
                                Kartu</label>
                            <!-- Menampilkan gambar saat ini -->
                            <div class="mb-3 border p-2 rounded-lg bg-gray-50">
                                <p class="text-xs font-semibold text-gray-700 mb-1.5">Gambar Saat Ini</p>
                                <img x-show="cardData && cardData.image_url"
                                    :src="cardData.image_url ? cardData.image_url : ''"
                                    :alt="cardData && cardData.name ? cardData.name : ''" class="h-32 w-full object-contain rounded-lg border-2 border-gray-200
                                                    hover:border-blue-300 transition-all duration-300 cursor-pointer"
                                    @click="cardData && cardData.image_url && openImageModal(cardData.image_url)">
                            </div>
                            <!-- Upload gambar baru -->
                            <div class="mt-3 border p-2 rounded-lg bg-gray-50">
                                <p class="text-xs font-semibold text-gray-700 mb-1.5">Upload Gambar Baru</p>
                                <div class="flex items-center justify-center w-full">
                                    <label for="edit_image_url"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 
                                                          border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 
                                                          transition-colors duration-300 hover:border-blue-300 hover:shadow-inner relative">
                                        <!-- Teks placeholder jika belum ada gambar -->
                                        <template x-if="!previewEdit">
                                            <div class="flex flex-col items-center justify-center pt-4 pb-5">
                                                <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <p class="mb-1 text-xs text-gray-500">Klik untuk upload gambar baru</p>
                                                <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                                            </div>
                                        </template>
                                        <!-- Pratinjau gambar baru -->
                                        <template x-if="previewEdit">
                                            <img :src="previewEdit" alt="Pratinjau Gambar Baru"
                                                class="h-full w-full object-contain p-1.5 rounded-lg">
                                        </template>
                                        <!-- Input file untuk upload gambar baru -->
                                        <input id="edit_image_url" name="image_url" type="file"
                                            accept="image/png,image/jpeg" class="hidden" x-ref="editImageInput"
                                            @change="if ($event.target.files[0].size > 2 * 1024 * 1024) { errors.image_url = ['Ukuran file maksimum 2MB']; $event.target.value = null; previewEdit = null; } else { previewEdit = URL.createObjectURL($event.target.files[0]); }"
                                            aria-describedby="edit_image_url_error" />
                                    </label>
                                </div>
                            </div>
                            <!-- Pesan error untuk gambar -->
                            <template x-if="errors.image_url">
                                <p id="edit_image_url_error" class="mt-1.5 text-xs text-red-600"
                                    x-text="errors.image_url[0]"></p>
                            </template>
                        </div>
                        <!-- Input untuk link eksternal -->
                        <div>
                            <label for="edit_external_link" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Link Eksternal <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="external_link" id="edit_external_link" required
                                x-model="cardData.external_link"
                                :class="errors.external_link ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
                                class="w-full px-3 py-1.5 text-xs border rounded-lg shadow-sm focus:outline-none 
                                                  focus:ring-2 focus:ring-opacity-50 transition-all duration-300 
                                                  hover:border-blue-300 hover:shadow-md" aria-required="true"
                                aria-describedby="edit_external_link_error" />
                            <!-- Pesan error untuk link eksternal -->
                            <template x-if="errors.external_link">
                                <p id="edit_external_link_error" class="mt-1.5 text-xs text-red-600"
                                    x-text="errors.external_link[0]"></p>
                            </template>
                        </div>

                    </form>
                </div>
                <!-- Footer modal dengan tombol aksi -->
                <div class="border-t border-gray-200 px-4 py-3 flex justify-end gap-2 
                                    bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-lg">
                    <!-- Tombol batal -->
                    <button type="button" @click="openEditModalId = null" class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 
                                           shadow-sm text-xs font-medium transition-all duration-300 hover:-translate-y-0.5 
                                           hover:shadow-md" aria-label="Batal mengedit kartu">Batal</button>
                    <!-- Tombol simpan perubahan -->
                    <button type="button" @click="submitEditForm" :disabled="loading"
                        class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 
                                           hover:from-blue-700 hover:to-indigo-700 text-white shadow-md text-xs font-medium 
                                           transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed 
                                           flex items-center justify-center min-w-[80px] hover:-translate-y-0.5 hover:shadow-lg" aria-label="Simpan perubahan kartu">
                        <!-- Teks tombol saat tidak loading -->
                        <span x-show="!loading">Simpan Perubahan</span>
                        <!-- Teks dan ikon loading saat memproses -->
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===================== BAGIAN MODAL HAPUS KARTU ===================== -->
        <!-- Modal konfirmasi hapus kartu -->
        <div x-show="openDeleteModalId !== null" x-cloak x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 backdrop-blur-sm bg-black/50" role="dialog"
            aria-labelledby="deleteModalTitle">
            <div x-show="openDeleteModalId !== null" x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="openDeleteModalId = null"
                class="bg-white rounded-lg shadow-2xl w-full max-w-sm border-2 border-red-200">
                <!-- Header modal hapus kartu -->
                <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-red-600 to-pink-600 rounded-t-lg">
                    <div class="flex items-center gap-2">
                        <!-- Ikon peringatan -->
                        <div class="p-1.5 rounded-full bg-white/20 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <!-- Judul dan deskripsi modal -->
                        <div>
                            <h2 id="deleteModalTitle" class="text-lg font-bold text-white">Hapus Kartu Konten</h2>
                            <p class="text-xs text-red-100">Aksi ini tidak dapat dibatalkan</p>
                        </div>
                    </div>
                </div>
                <!-- Konten modal hapus kartu -->
                <div class="p-4">
                    <!-- Informasi kartu yang akan dihapus -->
                    <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded-lg">
                        <div class="flex">
                            <div class="ml-1.5">
                                <p class="text-xs font-medium text-red-800" x-text="'Judul: ' + cardData.title"></p>
                                <p class="text-xs text-red-700 mt-1" x-text="'Deskripsi: ' + cardData.description"></p>
                                <p class="text-xs text-red-700 mt-1"
                                    x-text="'Status: ' + (cardData.is_active ? 'Aktif' : 'Nonaktif')"></p>
                            </div>
                        </div>
                    </div>
                    <!-- Peringatan konfirmasi penghapusan -->
                    <p class="mt-3 text-xs text-gray-600">
                        Anda yakin ingin menghapus kartu konten ini? Semua data yang terkait akan dihapus secara permanen.
                    </p>
                </div>
                <!-- Footer modal dengan tombol aksi -->
                <div class="border-t border-gray-200 px-4 py-3 flex justify-end gap-2 
                                    bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-lg">
                    <!-- Tombol batal -->
                    <button type="button" @click="openDeleteModalId = null" class="px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 
                                           shadow-sm text-xs font-medium transition-all duration-300 hover:-translate-y-0.5 
                                           hover:shadow-md" aria-label="Batal menghapus kartu">Batal</button>
                    <!-- Tombol hapus -->
                    <button type="button" @click="submitDeleteForm" :disabled="loading"
                        class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-red-600 to-pink-600 
                                           hover:from-red-700 hover:to-pink-700 text-white shadow-md text-xs font-medium 
                                           transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed 
                                           flex items-center justify-center min-w-[80px] hover:-translate-y-0.5 hover:shadow-lg" aria-label="Hapus kartu">
                        <!-- Teks tombol saat tidak loading -->
                        <span x-show="!loading">Hapus</span>
                        <!-- Teks dan ikon loading saat memproses -->
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection