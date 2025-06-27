@extends('admin.layouts.app')

@section('content')
    <div class="p-4 transition-all duration-300" x-data="{ ...userManager(), sidebarOpen: sidebarOpen }" x-cloak
        @keydown.escape.window="closeAllModals()">


        <!-- Judul Halaman -->
        <h1 class="text-4xl font-extrabold mb-12 text-emerald-900 text-center tracking-wide">
            Manajemen Pengguna Admin
        </h1>

        <!-- Baris Utama: Tombol Tambah, Total Pengguna, dan Pencarian -->
        <div class="mb-6 flex items-center justify-between flex-wrap gap-4">

            <!-- Grup Kiri: Tambah + Total -->
            <div class="flex items-center gap-4 flex-wrap">

                <!-- Tombol Tambah -->
                <button @click="openCreateModal = true" class="flex items-center gap-2 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white 
                px-5 py-2.5 rounded shadow-md text-sm font-semibold whitespace-nowrap transition 
                hover:-translate-y-0.5 hover:brightness-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengguna Baru
                </button>

                <!-- Kartu Total Pengguna -->
                <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-900 to-emerald-700 text-white px-5 py-2.5 
                rounded shadow-md text-sm font-medium whitespace-nowrap">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m8-4a4 4 0 10-8 0 4 4 0 008 0zM17 11a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Total Pengguna: {{ $usersCount ?? $users->total() }}
                </div>

            </div>

            <!-- Form Pencarian -->
            <form method="GET" action="{{ url()->current() }}" class="relative max-w-sm w-full sm:w-auto">
                <div class="relative w-full">
                    <input type="text" name="search" placeholder="Cari pengguna..." value="{{ request('search') }}" class="w-full rounded-full px-3 py-2 pr-12 border border-emerald-700 shadow-sm 
                    text-sm text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition" />

                    <button type="submit" class="absolute top-1/2 right-1 -translate-y-1/2 bg-gradient-to-r from-emerald-900 to-emerald-700 
                    hover:from-emerald-700 hover:to-emerald-600 text-white rounded-full p-1.5 
                    flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-emerald-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
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

        <!-- Tabel Pengguna -->
        <table class="w-full border border-emerald-300 rounded-lg overflow-hidden">
            <thead class="bg-emerald-700 text-white">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Dibuat Pada</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr class="border-b border-emerald-300 hover:bg-emerald-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="p-3 text-center space-x-2">
                            <button @click="openEditModal({{ $user->id }})" class="text-emerald-600 hover:text-emerald-800"
                                title="Edit">
                                <!-- SVG icon edit -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    viewBox="0 0 24 24">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                </svg>
                            </button>
                            <button @click="openDeleteModal({{ $user->id }})" class="text-red-600 hover:text-red-800"
                                title="Hapus">
                                <!-- SVG icon trash -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path
                                        d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5-3h4a2 2 0 0 1 2 2v1H8V5a2 2 0 0 1 2-2z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>

        <!-- Modal Create -->
        <div x-cloak>
            <div x-show="openCreateModal" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div @click.away="openCreateModal = false" x-transition
                    class="bg-white rounded-xl p-6 w-full max-w-lg shadow-xl space-y-4">
                    <h2 class="text-2xl font-bold text-emerald-700">Tambah Pengguna Admin</h2>

                    <!-- URL route disimpan di input hidden agar JS bisa baca -->
                    <input type="hidden" id="store_route" value="{{ route('admin.users.store') }}">
                    <form x-ref="createForm" @submit.prevent="submitCreateForm" class="space-y-4">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-emerald-800 mb-1">Nama</label>
                            <input type="text" name="name" id="name" required placeholder="Masukkan nama pengguna"
                                class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-emerald-800 mb-1">Email</label>
                            <input type="email" name="email" id="email" required placeholder="Masukkan email pengguna"
                                class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-emerald-800 mb-1">Password</label>
                            <input type="password" name="password" id="password" required placeholder="Masukkan password"
                                class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
                        </div>

                        <!-- Tombol -->
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" @click="openCreateModal = false"
                                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 shadow">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white"
                                :disabled="loading">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="openEditModalId !== null" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div @click.away="openEditModalId = null" x-transition
                class="bg-white rounded-xl p-6 w-full max-w-lg shadow-xl space-y-4">
                <h2 class="text-2xl font-bold text-emerald-700">Edit Pengguna Admin</h2>

                <form :action="`{{ url('admin/users') }}/${openEditModalId}`" method="POST" x-ref="editForm"
                    @submit.prevent="submitEditForm" class="space-y-4" novalidate>
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-emerald-800 mb-1">Nama</label>
                        <input type="text" name="name" id="edit_name" required x-model="userData.name"
                            class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-emerald-800 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" required x-model="userData.email"
                            class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-emerald-800 mb-1">Password</label>
                        <input type="password" name="password" id="edit_password"
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                            class="w-full bg-emerald-50 border border-emerald-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-400 focus:outline-none shadow-sm placeholder:text-emerald-500" />
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end space-x-3 pt-2">
                        <button type="button" @click="openEditModalId = null"
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-sm text-gray-800 shadow">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white"
                            :disabled="loading">Simpan</button>
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
                <h2 class="text-xl font-semibold mb-4">Hapus Pengguna Admin</h2>
                <p>Apakah Anda yakin ingin menghapus pengguna admin ini?</p>
                <form :action="`{{ url('admin/users') }}/${openDeleteModalId}`" method="POST" class="mt-4"
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

        <!-- Modal Validasi -->
        <div x-show="openValidationModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                <h2 class="text-xl font-semibold text-red-600 mb-4">Validasi Gagal</h2>
                <ul class="list-disc list-inside text-red-500">
                    <template x-for="(messages, field) in errors" :key="field">
                        <template x-for="message in messages">
                            <li x-text="message"></li>
                        </template>
                    </template>
                </ul>
                <div class="flex justify-end mt-4">
                    <button @click="closeValidationModal"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">OK</button>
                </div>
            </div>
        </div>

        <!-- Modal Sukses -->
        <div x-show="openSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                <h2 class="text-xl font-semibold text-emerald-700 mb-4">Sukses</h2>
                <p x-text="successMessage" class="text-emerald-600"></p>
                <div class="flex justify-end mt-4">
                    <button @click="closeSuccessModal"
                        class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">OK</button>
                </div>
            </div>
        </div>


        <script>
            function userManager() {
                return {
                    openCreateModal: false,
                    openEditModalId: null,
                    openDeleteModalId: null,
                    userData: {},
                    errors: {},
                    loading: false,
                    openValidationModal: false,
                    openSuccessModal: false,
                    successMessage: '',

                    openEditModal(id) {
                        this.loading = true;
                        fetch(`/admin/users/${id}/edit`)
                            .then(response => {
                                if (!response.ok) throw new Error('Gagal mengambil data pengguna.');
                                return response.json();
                            })
                            .then(data => {
                                this.userData = data;
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
                        console.log('Form create dikirim');
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
                                this.successMessage = data.message || 'Pengguna berhasil ditambahkan.';
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

                        fetch(`/admin/users/${this.openEditModalId}`, {
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

                        fetch(`/admin/users/${this.openDeleteModalId}`, {
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