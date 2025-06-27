@extends('admin.layouts.app')

@section('content')
  <div class="p-4 transition-all duration-300" x-data="{ ...cardManager(), sidebarOpen: sidebarOpen }" x-cloak
    @keydown.escape.window="closeAllModals()">

    <!-- Judul Halaman: Tengah, Modern, dan Jarak Jauh dari Konten -->
    <h1 class="text-4xl font-extrabold mb-12 text-emerald-900 text-center tracking-wide">
    Manajemen Kartu Konten
    </h1>

    <!-- Baris Utama: Tombol Tambah, Total Kartu, dan Pencarian -->
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">

    <!-- Grup Kiri: Tombol Tambah dan Kartu Total -->
    <div class="flex items-center gap-4 flex-wrap">

      <!-- Tombol Tambah Kartu Baru -->
      <button @click="openCreateModal = true" class="flex items-center gap-2 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white 
      px-5 py-2.5 rounded shadow-md text-sm font-semibold whitespace-nowrap 
      transition-transform transition-colors duration-200 ease-in-out 
      hover:-translate-y-0.5 hover:brightness-110">
      <!-- Ikon Plus -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M12 5v14M5 12h14" />
      </svg>
      Tambah Kartu Baru
      </button>

      <!-- Kartu Total Kartu -->
      <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-900 to-emerald-700 text-white px-5 py-2.5 
      rounded shadow-md text-sm font-medium whitespace-nowrap">
      <!-- Ikon -->
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
      </svg>
      Total Kartu: {{ $cardsCount ?? $cards->total() }}
      </div>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" action="{{ url()->current() }}" class="relative max-w-sm w-full sm:w-auto">
      <div class="relative w-full">
      <!-- Input -->
      <input type="search" name="search" value="{{ request('search') }}"
        placeholder="Cari Judul, Deskripsi, Link Eksternal..." class="w-full rounded-full px-3 py-2 pr-12 border border-emerald-700 shadow-sm 
      text-sm text-emerald-900 focus:outline-none focus:ring-2 
      focus:ring-emerald-600 transition" />

      <!-- Tombol Search -->
      <button type="submit" class="absolute top-1/2 right-1 -translate-y-1/2 bg-gradient-to-r from-emerald-900 to-emerald-700 
      hover:from-emerald-700 hover:to-emerald-600 text-white rounded-full p-1.5 
      flex items-center justify-center focus:outline-none focus:ring-2 
      focus:ring-emerald-600 transition">
        <!-- Ikon Search -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="11" cy="11" r="7"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </button>
      </div>
    </form>
    </div>



    <!-- Notifikasi -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-100 text-emerald-800 rounded">
    {{ session('success') }}
    </div>
    @endif

    <!-- Tabel Kartu -->
    <table class="w-full border border-emerald-300 rounded-lg overflow-hidden">
    <thead class="bg-emerald-700 text-white">
      <tr>
      <th class="p-3 text-left">No</th>
      <th class="p-3 text-left">Judul</th>
      <th class="p-3 text-left">Deskripsi</th>
      <th class="p-3 text-left">Link Eksternal</th>
      <th class="p-3 text-left">Gambar</th>
      <th class="p-3 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cards as $index => $card)
      <tr class="border-b border-emerald-300 hover:bg-emerald-50">
      <td class="p-3">{{ $index + 1 }}</td>
      <td class="p-3">{{ $card->title }}</td>
      <td class="p-3">{{ Str::limit($card->description, 50) }}</td>
      <td class="p-3">
      <a href="{{ $card->external_link }}" target="_blank" class="text-emerald-700 hover:underline">
      {{ Str::limit($card->external_link, 30) }}
      </a>
      </td>
      <td class="p-3">
      <img src="{{ asset($card->image_url) }}" alt="Logo {{ $card->title }}" class="h-10 object-contain" />
      </td>
      <td class="p-3 text-center space-x-2">
      <button @click="openEditModal({{ $card->id }})" class="text-emerald-600 hover:text-emerald-800" title="Edit">
      <!-- SVG icon edit -->
      <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M12 20h9" />
        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
      </svg>
      </button>
      <button @click="openDeleteModal({{ $card->id }})" class="text-red-600 hover:text-red-800" title="Hapus">
      <!-- SVG icon trash -->
      <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="3 6 5 6 21 6" />
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5-3h4a2 2 0 0 1 2 2v1H8V5a2 2 0 0 1 2-2z" />
      </svg>
      </button>
      </td>
      </tr>
    @endforeach
    </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
    {{ $cards->links() }}
    </div>

    <!-- Modal Create -->
    <div x-cloak>
    <!-- Modal Create -->
    <div x-show="openCreateModal" x-cloak
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div @click.away="openCreateModal = false" x-transition
      class="bg-white rounded-xl p-6 w-full max-w-lg shadow-xl space-y-4">
      <h2 class="text-2xl font-bold text-emerald-700">Tambah Kartu Konten</h2>

      <!-- URL route disimpan di input hidden agar JS bisa baca -->
      <input type="hidden" id="store_route" value="{{ route('admin.cards.store') }}">
      <form x-ref="createForm" @submit.prevent="submitCreateForm" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Judul -->
        <div>
        <label for="title" class="block text-sm font-medium text-emerald-800 mb-1">Judul</label>
        <input type="text" name="title" id="title" required placeholder="Masukkan judul kartu"
          class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
        </div>

        <!-- Deskripsi -->
        <div>
        <label for="description" class="block text-sm font-medium text-emerald-800 mb-1">Deskripsi</label>
        <textarea name="description" id="description" rows="3" required placeholder="Tulis deskripsi singkat..."
          class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500 resize-none"></textarea>
        </div>

        <!-- Gambar + Preview -->
        <div x-data="{ preview: null }">
        <label for="image_url" class="block text-sm font-medium text-emerald-800 mb-1">Gambar</label>
        <input type="file" name="image_url" id="image_url" accept="image/*" required
          @change="preview = URL.createObjectURL($event.target.files[0])"
          class="w-full bg-white border border-emerald-300 rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 shadow-sm" />

        <!-- Preview Gambar -->
        <template x-if="preview">
          <img :src="preview" alt="Preview Gambar"
          class="mt-4 h-32 rounded-lg shadow object-contain border border-gray-200" />
        </template>
        </div>

        <!-- Link Eksternal -->
        <div>
        <label for="external_link" class="block text-sm font-medium text-emerald-800 mb-1">Link Eksternal</label>
        <input type="url" name="external_link" id="external_link" required placeholder="https://contoh.com"
          class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
        </div>

        <!-- Tombol -->
        <div class="flex justify-end space-x-3 pt-2">
        <button type="button" @click="openCreateModal = false"
          class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 shadow">Batal</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white"
          :disabled="loading">Simpan</button>
        </div>
      </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="openEditModalId !== null" x-cloak
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div @click.away="openEditModalId = null" x-transition
      class="bg-white rounded-xl p-6 w-full max-w-lg shadow-xl space-y-4">
      <h2 class="text-2xl font-bold text-emerald-700">Edit Kartu Konten</h2>

      <form :action="`{{ url('admin/cards') }}/${openEditModalId}`" method="POST" x-ref="editForm"
        enctype="multipart/form-data" @submit.prevent="submitEditForm" x-data="{ previewEdit: null }" novalidate>
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div>
        <label for="edit_title" class="block text-sm font-medium text-emerald-800 mb-1">Judul</label>
        <input type="text" name="title" id="edit_title" required x-model="cardData.title"
          class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
        </div>

        <!-- Deskripsi -->
        <div>
        <label for="edit_description" class="block text-sm font-medium text-emerald-800 mb-1">Deskripsi</label>
        <textarea name="description" id="edit_description" rows="3" required x-model="cardData.description"
          class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500 resize-none"></textarea>
        </div>

        <!-- Gambar -->
        <div>
        <label for="edit_image_url" class="block text-sm font-medium text-emerald-800 mb-1">Gambar</label>
        <input type="file" name="image_url" id="edit_image_url" accept="image/*"
          @change="previewEdit = URL.createObjectURL($event.target.files[0])"
          class="w-full bg-white border border-emerald-300 rounded-lg px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 shadow-sm" />

        <!-- Preview gambar baru -->
        <template x-if="previewEdit">
          <img :src="previewEdit" alt="Preview Gambar Baru"
          class="mt-3 h-28 rounded-lg shadow border border-gray-200 object-contain" />
        </template>

        <!-- Preview gambar lama jika belum pilih baru -->
        <template x-if="cardData.image_url && !previewEdit">
          <img :src="`{{ asset('') }}/${cardData.image_url}`" alt="Gambar Lama"
          class="mt-3 h-28 rounded-lg shadow border border-gray-200 object-contain" />
        </template>
        </div>

        <!-- Link Eksternal -->
        <div>
        <label for="edit_external_link" class="block text-sm font-medium text-emerald-800 mb-1">Link
          Eksternal</label>
        <input type="url" name="external_link" id="edit_external_link" required placeholder="https://contoh.com"
          class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
        </div>

        <!-- Tombol -->
        <div class="flex justify-end space-x-3 pt-2">
        <button type="button" @click="openEditModalId = null"
          class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm text-gray-800 shadow">Batal</button>
        <button type="submit"
          class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium shadow">Simpan</button>
        </div>
      </form>
      </div>
    </div>


    <!-- Delete Modal -->
    <div x-show="openDeleteModalId !== null" x-cloak
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div @click.away="openDeleteModalId = null"
      class="bg-white rounded-lg p-6 w-full max-w-md shadow-2xl ring-1 ring-black ring-opacity-5"
      style="z-index: 9999;">
      <h2 class="text-xl font-semibold mb-4">Hapus Kartu Konten</h2>
      <p>Apakah Anda yakin ingin menghapus kartu konten ini?</p>
      <form :action="`{{ url('admin/cards') }}/${openDeleteModalId}`" method="POST" class="mt-4"
        @submit.prevent="submitDeleteForm">
        @csrf
        @method('DELETE')
        <div class="flex justify-end space-x-3">
        <button type="button" @click="openDeleteModalId = null"
          class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" tabindex="0">Batal</button>
        <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
          tabindex="0">Hapus</button>
        </div>
      </form>
      </div>
    </div>

    <!-- Validation Modal -->
    <div x-show="openValidationModal" x-cloak
      class="fixed inset-0 z-60 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
      <h3 class="text-lg font-semibold mb-4 text-red-600">Validasi Gagal</h3>
      <ul class="list-disc list-inside text-red-600">
        <template x-for="(messages, field) in errors" :key="field">
        <template x-for="message in messages" :key="message">
          <li x-text="message"></li>
        </template>
        </template>
      </ul>
      <div class="mt-6 flex justify-end">
        <button @click="closeValidationModal()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
        OK
        </button>
      </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div x-show="openSuccessModal" x-cloak
      class="fixed inset-0 z-60 flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl flex flex-col items-center space-y-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
      <h3 class="text-lg font-semibold text-green-600">Sukses</h3>
      <p x-text="successMessage" class="text-center"></p>
      <div class="mt-2">
        <button @click="closeSuccessModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        OK
        </button>
      </div>
      </div>
    </div>





    <script>
      function cardManager() {
      return {
        openCreateModal: false,
        openEditModalId: null,
        openDeleteModalId: null,
        cardData: {},
        errors: {},
        loading: false,
        openValidationModal: false,
        openSuccessModal: false,
        successMessage: '',

        openEditModal(id) {
        this.loading = true;
        fetch(`/admin/cards/${id}/edit`)
          .then(response => {
          if (!response.ok) throw new Error('Gagal mengambil data kartu.');
          return response.json();
          })
          .then(data => {
          this.cardData = data;
          this.openEditModalId = id;
          this.errors = {};
          })
          .catch(error => {
          alert(error.message);
          })
          .finally(() => {
          this.loading = false;
          });
        },

        openDeleteModal(id) {
        this.openDeleteModalId = id;
        },

        closeAllModals() {
        this.openCreateModal = false;
        this.openEditModalId = null;
        this.openDeleteModalId = null;
        this.errors = {};
        this.loading = false;
        this.openValidationModal = false;
        this.openSuccessModal = false;
        this.successMessage = '';
        },

        closeValidationModal() {
        this.openValidationModal = false;
        this.errors = {};
        },

        closeSuccessModal() {
        this.openSuccessModal = false;
        this.successMessage = '';
        window.location.reload();
        },

        submitCreateForm() {
        this.loading = true;
        this.errors = {};

        const form = this.$refs.createForm;
        const formData = new FormData(form);
        const storeUrl = document.querySelector('#store_route').value;

        fetch(storeUrl, {
          method: 'POST',
          headers: { 'Accept': 'application/json' },
          body: formData
        })
          .then(async response => {
          if (response.status === 422) {
            const data = await response.json();
            this.errors = data.errors || {};
            this.openValidationModal = true;
            throw new Error('validasi');
          }
          if (!response.ok) throw new Error('Gagal menyimpan data.');
          return response.json();
          })
          .then(data => {
          this.openSuccessModal = true;
          this.successMessage = data.message || 'Kartu berhasil ditambahkan.';
          this.openCreateModal = false;
          })
          .catch(error => {
          if (error.message !== 'validasi') {
            alert(error.message);
          }
          })
          .finally(() => {
          this.loading = false;
          });
        },

        submitEditForm() {
        this.loading = true;
        this.errors = {};
        const form = this.$refs.editForm;
        const formData = new FormData(form);
        formData.set('_method', 'PUT');

        fetch(`/admin/cards/${this.openEditModalId}`, {
          method: 'POST',
          headers: { 'Accept': 'application/json' },
          body: formData
        })
          .then(async response => {
          if (response.status === 422) {
            const data = await response.json();
            this.errors = data.errors || {};
            this.openValidationModal = true;
            throw new Error('validasi');
          }
          if (!response.ok) throw new Error('Gagal menyimpan data.');
          return response.json();
          })
          .then(data => {
          this.openSuccessModal = true;
          this.successMessage = data.message || 'Data berhasil disimpan.';
          })
          .catch(error => {
          if (error.message !== 'validasi') {
            alert(error.message);
          }
          })
          .finally(() => {
          this.loading = false;
          });
        },

        submitDeleteForm() {
        this.loading = true;
        this.errors = {};
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenMeta) {
          alert('CSRF token meta tag tidak ditemukan. Tambahkan <meta name="csrf-token" content="{{ csrf_token() }}"> di bagian <head>.');
          this.loading = false;
          return;
        }
        const csrfToken = csrfTokenMeta.getAttribute('content');

        fetch(`/admin/cards/${this.openDeleteModalId}`, {
          method: 'DELETE',
          headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
          }
        })
          .then(async response => {
          if (response.status === 422) {
            const data = await response.json();
            this.errors = data.errors || {};
            this.openValidationModal = true;
            throw new Error('validasi');
          }
          if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Gagal menghapus data.');
          }
          return response.json();
          })
          .then(data => {
          this.openSuccessModal = true;
          this.successMessage = data.message || 'Data berhasil dihapus.';
          })
          .catch(error => {
          if (error.message !== 'validasi') {
            alert(error.message);
          }
          })
          .finally(() => {
          this.loading = false;
          });
        },
      }
      }
    </script>

@endsection