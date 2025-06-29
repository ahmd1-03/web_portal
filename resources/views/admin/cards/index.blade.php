@extends('admin.layouts.app')

@section('content')
  <!-- Container utama dengan padding responsif dan penyesuaian margin sidebar -->
  <div class="max-w-full mx-auto px-4 transition-all duration-300" x-data="cardManager({{ json_encode(['from' => $cards->firstItem() ?: 0, 'to' => $cards->lastItem() ?: 0, 'total' => $cards->total()]) }})" x-cloak>
    <!-- Memasukkan library SweetAlert2 untuk notifikasi interaktif -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bagian header untuk judul dan form pencarian -->
    <div class="mb-6 px-2 md:px-4">
    <!-- Flex container untuk menata judul, form pencarian, dan dropdown -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <!-- Bagian judul halaman -->
      <div>
      <h1
        class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 mb-3">
        Manajemen Kartu Konten
      </h1>
      <p class="text-xs md:text-sm text-gray-600 mb-6">Kelola konten kartu yang ditampilkan di halaman utama</p>
      </div>

      <!-- Container untuk pencarian dan dropdown -->
      <div class="flex flex-col md:flex-row gap-4">
      <!-- Form pencarian kartu dengan status loading -->
      <form method="GET" action="{{ url()->current() }}" class="relative w-full md:w-64" x-data="{ searching: false }"
        @submit.prevent="handleSearch" aria-label="Form pencarian kartu">
        <div class="relative mb-4">
        <input type="text" name="search" placeholder="Cari kartu..." value="{{ request('search') }}"
          x-model="searchQuery" x-on:input.debounce.500ms="searching = true"
          class="w-full text-sm rounded-lg px-4 py-2 pr-10 border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 hover:border-emerald-300 hover:shadow-md"
          aria-label="Masukkan kata kunci pencarian kartu" />
        <button type="submit"
          class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 flex items-center justify-center focus:outline-none transition-all duration-300"
          title="Cari kartu">
          <template x-if="!searching">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          </template>
          <template x-if="searching">
          <svg class="animate-spin w-5 h-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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
        <select x-model="perPage" x-on:change="changePerPage"
        class="w-full md:w-32 text-sm rounded-lg px-4 py-2 border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 hover:border-emerald-300 hover:shadow-md"
        aria-label="Jumlah data per halaman">
        @for ($i = 1; $i <= 20; $i++)
      <option value="{{ $i }}" {{ request('per_page') == $i ? 'selected' : '' }}>{{ $i }} per halaman</option>
      @endfor
        </select>
      </div>
      </div>
    </div>

    <!-- Grid untuk menampilkan kartu aksi -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
      <div @click="openCreateModal = true"
      class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl shadow-sm p-4 border border-emerald-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 cursor-pointer hover:-translate-y-1 transform"
      role="button" aria-label="Tambah kartu baru">
      <div class="flex items-center gap-3">
        <div
        class="w-12 h-12 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 flex items-center justify-center text-white group-hover:from-emerald-600 group-hover:to-teal-600 transition-all duration-300">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        </div>
        <div>
        <h3 class="font-medium text-gray-800 group-hover:text-emerald-700 transition-colors duration-300">Tambah
          Kartu Baru</h3>
        <p class="text-sm text-gray-500 group-hover:text-gray-600 transition-colors duration-300">Buat konten kartu
          baru</p>
        </div>
      </div>
      </div>

      <div
      class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm p-4 border border-blue-100 hover:shadow-md transition-all duration-300">
      <div class="flex items-center gap-3">
        <div
        class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        </div>
        <div>
        <h3 class="font-medium text-gray-800">Total Kartu</h3>
        <p id="totalCount" class="text-2xl font-bold text-blue-700">{{ $cards->total() }}</p>
        </div>
      </div>
      </div>

      <div
      class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-sm p-4 border border-green-100 hover:shadow-md transition-all duration-300">
      <div class="flex items-center gap-3">
        <div
        class="w-12 h-12 rounded-lg bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        </div>
        <div>
        <h3 class="font-medium text-gray-800">Kartu Aktif</h3>
        <p id="activeCount" class="text-2xl font-bold text-green-700">
          {{ $cards->where('is_active', true)->count() }}
        </p>
        </div>
      </div>
      </div>

      <!-- Tambahan card untuk kartu tidak aktif -->
      <div
      class="bg-gradient-to-br from-red-50 to-pink-50 rounded-xl shadow-sm p-4 border border-red-100 hover:shadow-md transition-all duration-300">
      <div class="flex items-center gap-3">
        <div
        class="w-12 h-12 rounded-lg bg-gradient-to-r from-red-500 to-pink-500 flex items-center justify-center text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        </div>
        <div>
        <h3 class="font-medium text-gray-800">Kartu Tidak Aktif</h3>
        <p id="inactiveCount" class="text-2xl font-bold text-red-700">
          {{ $cards->where('is_active', false)->count() }}
        </p>
        </div>
      </div>
      </div>
    </div>
    </div>

    <!-- Notifikasi sukses jika ada pesan dari sesi -->
    @if(session('success'))
    <div class="mb-6 px-2 md:px-4">
    <div
      class="p-4 text-sm bg-gradient-to-r from-emerald-100 to-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg transition-all duration-300 flex items-center shadow-sm">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      {{ session('success') }}
    </div>
    </div>
    @endif

    <!-- Tabel untuk menampilkan daftar kartu -->
    <div class="px-2 md:px-4 mb-6">
    <div class="bg-white rounded-xl shadow-lg border border-gray-300 overflow-hidden">
      <div class="overflow-x-auto">
      <table class="w-full border border-gray-300">
        <thead class="bg-emerald-600 border border-emerald-600">
        <tr>
          <th
          class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider border-r border-emerald-600">
          No</th>
          <th
          class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider border-r border-emerald-600">
          Judul</th>
          <th
          class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider hidden sm:table-cell border-r border-emerald-600">
          Deskripsi</th>
          <th
          class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider border-r border-emerald-600">
          Status</th>
          <th
          class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider hidden md:table-cell border-r border-emerald-600">
          Link</th>
          <th
          class="px-2 py-2 text-left text-xs font-medium text-white uppercase tracking-wider border-r border-emerald-600">
          Gambar</th>
          <th class="px-2 py-2 text-right text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 border border-gray-300">
        @forelse($cards as $index => $card)
      <tr
        class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150 group">
        <td
        class="px-2 py-2 whitespace-nowrap text-sm text-gray-500 group-hover:text-gray-700 transition-colors duration-300">
        {{ ($cards->currentPage() - 1) * $cards->perPage() + $index + 1 }}
        </td>
        <td class="px-2 py-2 whitespace-nowrap">
        <div
        class="text-sm font-medium text-gray-900 group-hover:text-emerald-700 transition-colors duration-300">
        {{ $card->title }}
        </div>
        </td>
        <td class="px-2 py-2 whitespace-nowrap hidden sm:table-cell">
        <div
        class="text-sm text-gray-500 max-w-xs truncate group-hover:text-gray-700 transition-colors duration-300">
        {{ Str::limit($card->description, 50) }}
        </div>
        </td>
        <td class="px-2 py-2 whitespace-nowrap">
        <span x-data="{ isActive: {{ $card->is_active ? 'true' : 'false' }} }"
        :class="isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
        class="px-2 py-1 text-xs font-semibold rounded-full">
        <span x-text="isActive ? 'Aktif' : 'Nonaktif'"></span>
        </span>
        </td>
        <td class="px-2 py-2 whitespace-nowrap hidden md:table-cell">
        <a href="{{ $card->external_link }}" target="_blank"
        class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition-all duration-300 inline-flex items-center group/link">
        {{ Str::limit($card->external_link, 20) }}
        <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform duration-300" fill="none"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
        </a>
        </td>
        <td class="px-2 py-2 whitespace-nowrap">
        <div x-data="{ showImageModal: false }">
        <img src="{{ asset('storage/' . $card->image_url) }}" @click="showImageModal = true"
        alt="Gambar Kartu {{ $card->title }}"
        class="h-12 w-12 sm:h-16 sm:w-16 object-cover rounded-md cursor-pointer border-2 border-gray-200 hover:border-emerald-400 transition-all duration-300" />
        <div x-show="showImageModal" x-cloak @click.away="showImageModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/80"
        role="dialog" aria-labelledby="imageModalTitle">
        <div class="relative max-w-4xl max-h-[90vh]">
          <img :src="'{{ asset('storage/' . $card->image_url) }}'" alt="Gambar Kartu {{ $card->title }}"
          class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl" />
          <button @click="showImageModal = false"
          class="absolute top-4 right-4 p-2 rounded-full bg-white/80 hover:bg-white text-gray-800 shadow-md transition-all duration-300"
          aria-label="Tutup modal pratinjau gambar">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M6 18L18 6M6 6l12 12" />
          </svg>
          </button>
        </div>
        </div>
        </div>
        </td>
        <td class="px-2 py-2 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex justify-end space-x-2">
        <button @click="openEditModal({{ $card->id }})"
        class="flex items-center gap-2 px-2 py-1 rounded-lg transition-all duration-300 bg-blue-600 text-white hover:bg-blue-700 group/edit"
        title="Edit kartu" aria-label="Edit kartu {{ $card->title }}">
        <svg xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 group-hover/edit:scale-110 transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          viewBox="0 0 24 24">
          <path d="M12 20h9" />
          <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
        </svg>
        </button>
        <button @click="openDeleteModal({{ $card->id }})"
        class="flex items-center gap-2 px-2 py-1 rounded-lg transition-all duration-300 bg-red-600 text-white hover:bg-red-700 group/delete"
        title="Hapus kartu" aria-label="Hapus kartu {{ $card->title }}">
        <svg xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 group-hover/delete:scale-110 transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          viewBox="0 0 24 24">
          <polyline points="3 6 5 6 21 6" />
          <path
          d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5-3h4a2 2 0 0 1 2 2v1H8V5a2 2 0 0 1 2-2z" />
        </svg>
        </button>
        <button @click="toggleCardStatus({{ $card->id }}, {{ $card->is_active ? 'true' : 'false' }})"
        class="flex items-center gap-2 px-2 py-1 rounded-lg transition-all duration-300 {{ $card->is_active ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-red-600 text-white hover:bg-red-700' }}"
        title="{{ $card->is_active ? 'Nonaktifkan kartu' : 'Aktifkan kartu' }}"
        aria-label="{{ $card->is_active ? 'Nonaktifkan kartu' : 'Aktifkan kartu' }} {{ $card->title }}">
        <svg xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          viewBox="0 0 24 24">
          @if(!$card->is_active)
        <path d="M18.36 6.64a9 9 0 1 1-12.73 0" />
        <line x1="12" y1="2" x2="12" y2="12" />
      @else
        <circle cx="12" cy="12" r="10" />
        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
      @endif
        </svg>
        </button>
        </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="px-2 py-4 text-center text-sm text-gray-500">
        Tidak ada data kartu yang ditemukan.
        </td>
      </tr>
      @endforelse
        </tbody>
      </table>
      </div>
      <!-- Bagian pagination dengan gaya responsif -->
      <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
      <!-- Informasi jumlah data -->
      <div class="text-sm text-gray-700 mb-2"
        x-text="`Menampilkan ${pagination.from} sampai ${pagination.to} dari ${pagination.total} hasil`">
      </div>
      {{-- <div class="text-sm text-gray-700 mb-2">
        Menampilkan <span>{{ $cards->firstItem() ?: 0 }}</span> sampai
        <span>{{ $cards->lastItem() ?: 0 }}</span> dari
        <span>{{ $cards->total() }}</span> hasil
      </div> --}}
      {{ $cards->appends(request()->except('page'))->onEachSide(1)->links('vendor.pagination.tailwind') }}
      </div>
    </div>
    </div>

    <!-- Modal untuk menambah kartu baru -->
    {{-- <div x-show="openCreateModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50" role="dialog"
    aria-labelledby="createModalTitle"> --}}

    {{-- <div @click.away="openCreateModal = false"
      class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto border-2 border-emerald-200">
      --}}

      <!-- Backdrop (hanya fade in/out) -->
      <div x-show="openCreateModal" x-cloak x-transition:enter="ease-out duration-200"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50">

      <!-- Modal content (scale animation) -->
      <div x-show="openCreateModal" x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @click.away="openCreateModal = false"
        class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto border-2 border-emerald-200">


        <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-t-xl">
        <h2 id="createModalTitle" class="text-xl font-bold text-white">Tambah Kartu Konten</h2>
        <p class="mt-1 text-sm text-emerald-100">Isi form berikut untuk menambahkan kartu konten baru</p>
        </div>
        <div class="p-6">
        <input type="hidden" id="store_route" value="{{ route('admin.cards.store') }}">
        <form x-ref="createForm" @submit.prevent="submitCreateForm" enctype="multipart/form-data" class="space-y-5">
          @csrf
          <div>
          <label for="create_title" class="block text-sm font-medium text-gray-700 mb-2">Judul Kartu <span
            class="text-red-500">*</span></label>
          <input type="text" name="title" id="create_title" required placeholder="Masukkan judul kartu"
            x-model="createForm.title"
            :class="errors.title ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 hover:border-emerald-300 hover:shadow-md"
            aria-required="true" aria-describedby="create_title_error" />
          <template x-if="errors.title">
            <p id="create_title_error" class="mt-2 text-sm text-red-600" x-text="errors.title[0]"></p>
          </template>
          </div>
          <div>
          <label for="create_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span
            class="text-red-500">*</span></label>
          <textarea name="description" id="create_description" rows="3" required
            placeholder="Tulis deskripsi singkat..." x-model="createForm.description"
            :class="errors.description ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 resize-none hover:border-emerald-300 hover:shadow-md"
            aria-required="true" aria-describedby="create_description_error"></textarea>
          <template x-if="errors.description">
            <p id="create_description_error" class="mt-2 text-sm text-red-600" x-text="errors.description[0]"></p>
          </template>
          </div>
          <div>
          <label for="create_image_url" class="block text-sm font-medium text-gray-700 mb-2">Gambar Kartu <span
            class="text-red-500">*</span></label>
          <div class="flex items-center justify-center w-full">
            <label for="create_image_url"
            class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-300 hover:border-emerald-300 hover:shadow-inner">
            <template x-if="!preview">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
              <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
              <p class="mb-2 text-sm text-gray-500">Klik untuk upload gambar</p>
              <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
              </div>
            </template>
            <template x-if="preview">
              <img :src="preview" alt="Pratinjau Gambar" class="h-full w-full object-contain p-2 rounded-lg">
            </template>
            <input id="create_image_url" name="image_url" type="file" accept="image/png,image/jpeg" required
              @change="if ($event.target.files[0].size > 2 * 1024 * 1024) { errors.image_url = ['Ukuran file maksimum 2MB']; $event.target.value = null; preview = null; } else { preview = URL.createObjectURL($event.target.files[0]); }"
              class="hidden" aria-describedby="create_image_url_error" />
            </label>
          </div>
          <template x-if="errors.image_url">
            <p id="create_image_url_error" class="mt-2 text-sm text-red-600" x-text="errors.image_url[0]"></p>
          </template>
          </div>
          <div>
          <label for="create_external_link" class="block text-sm font-medium text-gray-700 mb-2">Link Eksternal
            <span class="text-red-500">*</span></label>
          <input type="url" name="external_link" id="create_external_link" required
            placeholder="https://contoh.com" x-model="createForm.external_link"
            :class="errors.external_link ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 hover:border-emerald-300 hover:shadow-md"
            aria-required="true" aria-describedby="create_external_link_error" />
          <template x-if="errors.external_link">
            <p id="create_external_link_error" class="mt-2 text-sm text-red-600" x-text="errors.external_link[0]">
            </p>
          </template>
          </div>
          <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <div class="flex items-center">
            <input type="checkbox" name="is_active" id="create_is_active" checked
            class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
            aria-label="Aktifkan kartu ini" />
            <label for="create_is_active" class="ml-2 block text-sm text-gray-700">Aktifkan kartu ini</label>
          </div>
          </div>
        </form>
        </div>
        <div
        class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
        <button type="button" @click="openCreateModal = false"
          class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md"
          aria-label="Batal menambah kartu">Batal</button>
        <button type="button" @click="submitCreateForm" :disabled="loading"
          class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white shadow-md text-sm font-medium transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px] hover:-translate-y-0.5 hover:shadow-lg"
          aria-label="Simpan kartu baru">
          <span x-show="!loading">Simpan</span>
          <span x-show="loading" class="flex items-center justify-center">
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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

      <!-- Modal untuk mengedit kartu -->
      {{-- <div x-show="openEditModalId !== null" x-cloak x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
      x-transition:leave-end="opacity-0 scale-95"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50" role="dialog"
      aria-labelledby="editModalTitle">
      <div @click.away="openEditModalId = null"
        class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto border-2 border-blue-200">
        --}}

        <!-- Modal untuk mengedit kartu - DIUBAH ANIMASI -->
        <div x-show="openEditModalId !== null" x-cloak x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50" role="dialog"
        aria-labelledby="editModalTitle">

        <div x-show="openEditModalId !== null" x-transition:enter="ease-out duration-200"
          x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
          x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
          x-transition:leave-end="opacity-0 scale-95" @click.away="openEditModalId = null"
          class="bg-white rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto border-2 border-blue-200">

          <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl">
          <h2 id="editModalTitle" class="text-xl font-bold text-white">Edit Kartu Konten</h2>
          <p class="mt-1 text-sm text-blue-100">Perbarui informasi kartu konten</p>
          </div>
          <div class="p-6">
          <form x-ref="editForm" @submit.prevent="submitEditForm" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
            <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-2">Judul Kartu <span
              class="text-red-500">*</span></label>
            <input type="text" name="title" id="edit_title" required x-model="cardData.title"
              :class="errors.title ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 hover:border-blue-300 hover:shadow-md"
              aria-required="true" aria-describedby="edit_title_error" />
            <template x-if="errors.title">
              <p id="edit_title_error" class="mt-2 text-sm text-red-600" x-text="errors.title[0]"></p>
            </template>
            </div>
            <div>
            <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span
              class="text-red-500">*</span></label>
            <textarea name="description" id="edit_description" rows="3" required x-model="cardData.description"
              :class="errors.description ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 resize-none hover:border-blue-300 hover:shadow-md"
              aria-required="true" aria-describedby="edit_description_error"></textarea>
            <template x-if="errors.description">
              <p id="edit_description_error" class="mt-2 text-sm text-red-600" x-text="errors.description[0]">
              </p>
            </template>
            </div>
            <div>
            <label for="edit_image_url" class="block text-sm font-medium text-gray-700 mb-2">Gambar
              Kartu</label>
            <div class="mb-4 border p-3 rounded-lg bg-gray-50">
              <p class="text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</p>
              <img :src="cardData.image_url" alt="Gambar Kartu Saat Ini"
              class="h-40 w-full object-contain rounded-lg border-2 border-gray-200 hover:border-blue-300 transition-all duration-300 cursor-pointer">
            </div>
            <div class="mt-4 border p-3 rounded-lg bg-gray-50">
              <p class="text-sm font-semibold text-gray-700 mb-2">Upload Gambar Baru</p>
              <div class="flex items-center justify-center w-full">
              <label for="edit_image_url"
                class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-300 hover:border-blue-300 hover:shadow-inner relative">
                <template x-if="!previewEdit">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                  <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                  <p class="mb-2 text-sm text-gray-500">Klik untuk upload gambar baru</p>
                  <p class="text-xs text-gray-500">PNG, JPG (Max. 2MB)</p>
                </div>
                </template>
                <template x-if="previewEdit">
                <img :src="previewEdit" alt="Pratinjau Gambar Baru"
                  class="h-full w-full object-contain p-2 rounded-lg">
                </template>
                <input id="edit_image_url" name="image_url" type="file" accept="image/png,image/jpeg"
                class="hidden" x-ref="editImageInput"
                @change="if ($event.target.files[0].size > 2 * 1024 * 1024) { errors.image_url = ['Ukuran file maksimum 2MB']; $event.target.value = null; previewEdit = null; } else { previewEdit = URL.createObjectURL($event.target.files[0]); }"
                aria-describedby="edit_image_url_error" />
              </label>
              </div>
            </div>
            <template x-if="errors.image_url">
              <p id="edit_image_url_error" class="mt-2 text-sm text-red-600" x-text="errors.image_url[0]"></p>
            </template>
            </div>
            <div>
            <label for="edit_external_link" class="block text-sm font-medium text-gray-700 mb-2">Link Eksternal
              <span class="text-red-500">*</span></label>
            <input type="url" name="external_link" id="edit_external_link" required
              x-model="cardData.external_link"
              :class="errors.external_link ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 hover:border-blue-300 hover:shadow-md"
              aria-required="true" aria-describedby="edit_external_link_error" />
            <template x-if="errors.external_link">
              <p id="edit_external_link_error" class="mt-2 text-sm text-red-600"
              x-text="errors.external_link[0]">
              </p>
            </template>
            </div>
            <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <div class="flex items-center">
              <input type="checkbox" name="is_active" id="edit_is_active" x-model="cardData.is_active"
              class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              aria-label="Aktifkan kartu ini" />
              <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">Aktifkan kartu ini</label>
            </div>
            </div>
          </form>
          </div>
          <div
          class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
          <button type="button" @click="openEditModalId = null"
            class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md"
            aria-label="Batal mengedit kartu">Batal</button>
          <button type="button" @click="submitEditForm" :disabled="loading"
            class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-md text-sm font-medium transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px] hover:-translate-y-0.5 hover:shadow-lg"
            aria-label="Simpan perubahan kartu">
            <span x-show="!loading">Simpan Perubahan</span>
            <span x-show="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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

        <!-- Modal konfirmasi untuk menghapus kartu -->

        <!-- Modal konfirmasi untuk menghapus kartu - DIUBAH ANIMASI -->
        <div x-show="openDeleteModalId !== null" x-cloak x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50" role="dialog"
        aria-labelledby="deleteModalTitle">

        <div x-show="openDeleteModalId !== null" x-transition:enter="ease-out duration-200"
          x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
          x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 scale-100"
          x-transition:leave-end="opacity-0 scale-95" @click.away="openDeleteModalId = null"
          class="bg-white rounded-xl shadow-2xl w-full max-w-md border-2 border-red-200">

          {{-- <div x-show="openDeleteModalId !== null" x-cloak
          x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
          x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
          x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
          class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50"
          role="dialog" aria-labelledby="deleteModalTitle">
          <div @click.away="openDeleteModalId = null"
            class="bg-white rounded-xl shadow-2xl w-full max-w-md border-2 border-red-200"> --}}
            <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-red-600 to-pink-600 rounded-t-xl">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-full bg-white/20 text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              </div>
              <div>
              <h2 id="deleteModalTitle" class="text-xl font-bold text-white">Hapus Kartu Konten</h2>
              <p class="text-sm text-red-100">Aksi ini tidak dapat dibatalkan</p>
              </div>
            </div>
            </div>
            <div class="p-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
              <div class="flex">
              <div class="ml-2">
                <p class="text-sm font-medium text-red-800" x-text="'Judul: ' + cardData.title"></p>
                <p class="text-sm text-red-700 mt-1" x-text="'Deskripsi: ' + cardData.description"></p>
                <p class="text-sm text-red-700 mt-1"
                x-text="'Status: ' + (cardData.is_active ? 'Aktif' : 'Nonaktif')">
                </p>
              </div>
              </div>
            </div>
            <p class="mt-4 text-sm text-gray-600">Anda yakin ingin menghapus kartu konten ini? Semua data yang
              terkait
              akan
              dihapus secara permanen.</p>
            </div>
            <div
            class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
            <button type="button" @click="openDeleteModalId = null"
              class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md"
              aria-label="Batal menghapus kartu">Batal</button>
            <button type="button" @click="submitDeleteForm" :disabled="loading"
              class="px-4 py-2 rounded-lg bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white shadow-md text-sm font-medium transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px] hover:-translate-y-0.5 hover:shadow-lg"
              aria-label="Hapus kartu">
              <span x-show="!loading">Hapus</span>
              <span x-show="loading" class="flex items-center justify-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
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