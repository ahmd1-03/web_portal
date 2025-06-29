@extends('admin.layouts.app')

@section('content')
  <!-- Main Container with sidebar state management -->
  {{-- <div class="p-4 transition-all duration-300" x-data="userManager()" x-cloak
    :class="sidebarOpen ? 'lg:ml-64 md:ml-16' : 'ml-0'"> --}}

    {{-- <div class="p-4 transition-all duration-300" x-data="userManager()" x-cloak
    :class="$store.sidebar.open ? 'ml-64' : 'ml-16'"> --}}

    {{-- <div class="transition-all duration-300" x-data="userManager()" x-cloak> --}}

      <div class="p-4 transition-all duration-300" x-data="userManager()" x-cloak>


      <!-- SweetAlert2 for notifications -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Page Title with Gradient Text -->
        <div>
          <h1
          class="text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">
          Manajemen Pengguna Admin</h1>
          <p class="text-sm text-gray-600 mt-1">Kelola data pengguna dengan hak akses admin</p>
        </div>

        <!-- Search Form with loading state -->
        <form method="GET" action="{{ url()->current() }}" class="relative w-full md:w-64"
          x-data="{ searching: false }" @submit.prevent="handleSearch">
          <div class="relative">
          <input type="text" name="search" placeholder="Cari pengguna..." value="{{ request('search') }}"
            x-model="searchQuery" x-on:input.debounce.500ms="searching = true" class="w-full text-sm rounded-lg px-4 py-2 pr-10 border border-gray-300 shadow-sm 
      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300
      hover:border-emerald-300 hover:shadow-md" />
          <button type="submit" class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-400 
      hover:text-emerald-600 flex items-center justify-center focus:outline-none transition-all duration-300">
            <!-- Search icon or loading spinner -->
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
        </div>

        <!-- Action Cards Grid - Responsive layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-6">
        <!-- Add User Button (Clickable) -->
        <div @click="openCreateModal = true" class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl shadow-sm p-4 border border-emerald-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 cursor-pointer
      hover:-translate-y-1 transform">
          <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500 flex items-center justify-center text-white group-hover:from-emerald-600 group-hover:to-teal-600 transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
          <div>
            <h3 class="font-medium text-gray-800 group-hover:text-emerald-700 transition-colors duration-300">
            Tambah
            Pengguna Baru</h3>
            <p class="text-sm text-gray-500 group-hover:text-gray-600 transition-colors duration-300">Buat akun
            admin baru</p>
          </div>
          </div>
        </div>

        <!-- Total Users (Static - No click effects) -->
        <div
          class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-sm p-4 border border-blue-100 hover:shadow-md transition-all duration-300">
          <div class="flex items-center gap-4">
          <div
            class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m8-4a4 4 0 10-8 0 4 4 0 008 0zM17 11a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <div>
            <h3 class="font-medium text-gray-800">Total Pengguna</h3>
            <p id="totalCount" class="text-2xl font-bold text-blue-700">{{ $usersCount ?? $users->total() }}</p>
          </div>
          </div>
        </div>
        </div>
      </div>

      <!-- Success Notification -->
      @if(session('success'))
      <div
      class="mb-6 p-4 text-sm bg-gradient-to-r from-emerald-100 to-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-lg transition-all duration-300 flex items-center shadow-sm">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      {{ session('success') }}
      </div>
    @endif

      <!-- Users Table Section -->
      <div
        class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
        <!-- Responsive Table Container -->
        <div class="overflow-x-auto">
        <table class="w-full min-w-full">
          <thead class="bg-gradient-to-r from-emerald-600 to-teal-600">
          <tr>
            <!-- Table Headers -->
            <th
            class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
            No</th>
            <th
            class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
            Nama</th>
            <th
            class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
            Email</th>
            <th
            class="px-4 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-white uppercase tracking-wider">
            Tanggal Dibuat</th>
            <th
            class="px-4 py-3 sm:px-6 sm:py-4 text-right text-xs font-medium text-white uppercase tracking-wider">
            Aksi</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
          @foreach($users as $index => $user)
        <tr
        class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-teal-50 transition-colors duration-150 group">
        <!-- Row Number -->
        <td
        class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-500 group-hover:text-gray-700 transition-colors duration-300">
        {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
        </td>

        <!-- User Name -->
        <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
        <div
          class="text-sm font-medium text-gray-900 group-hover:text-emerald-700 transition-colors duration-300">
          {{ $user->name }}
        </div>
        </td>

        <!-- User Email -->
        <td class="px-4 py-3 sm:px-6 sm:py-4">
        <div
          class="text-sm text-gray-500 max-w-xs truncate group-hover:text-gray-700 transition-colors duration-300">
          {{ $user->email }}
        </div>
        </td>

        <!-- Created At -->
        <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
        <div class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors duration-300">
          {{ $user->created_at->format('d/m/Y') }}
        </div>
        </td>

        <!-- Action Buttons -->
        <td class="px-4 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex justify-end space-x-2">
          <!-- Edit Button -->
          <button @click="openEditModal({{ $user->id }})"
          class="flex items-center gap-2 px-2 py-1 rounded-lg transition-all duration-300 bg-blue-600 text-white hover:bg-blue-700 group/edit"
          title="Edit">
          <svg xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 group-hover/edit:scale-110 transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          viewBox="0 0 24 24">
          <path d="M12 20h9" />
          <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
          </svg>
          </button>

          <!-- Delete Button -->
          <button @click="openDeleteModal({{ $user->id }})"
          class="flex items-center gap-2 px-2 py-1 rounded-lg transition-all duration-300 bg-red-600 text-white hover:bg-red-700 group/delete"
          title="Hapus">
          <svg xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 group-hover/delete:scale-110 transition-transform duration-300" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          viewBox="0 0 24 24">
          <polyline points="3 6 5 6 21 6" />
          <path
          d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5-3h4a2 2 0 0 1 2 2v1H8V5a2 2 0 0 1 2-2z" />
          </svg>
          </button>
        </div>
        </td>
        </tr>
      @endforeach
          </tbody>
        </table>
        </div>

        <!-- Pagination with responsive padding -->
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
        {{ $users->onEachSide(1)->links() }}
        </div>
      </div>

      <!-- Create User Modal -->
      <div x-show="openCreateModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="openCreateModal = false" x-transition
        class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto border-2 border-emerald-200">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-t-xl">
          <h2 class="text-xl font-bold text-white">Tambah Pengguna Admin</h2>
          <p class="mt-1 text-sm text-emerald-100">Isi form berikut untuk menambahkan pengguna admin baru</p>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <input type="hidden" id="store_route" value="{{ route('admin.users.store') }}">

          <form x-ref="createForm" @submit.prevent="submitCreateForm" class="space-y-5">
          @csrf

          <!-- Name Field -->
          <div>
            <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
              class="text-red-500">*</span></label>
            <input type="text" name="name" id="create_name" required placeholder="Masukkan nama lengkap"
            x-model="createForm.name"
            :class="errors.name ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300
      hover:border-emerald-300 hover:shadow-md">
            <template x-if="errors.name">
            <p class="mt-2 text-sm text-red-600" x-text="errors.name[0]"></p>
            </template>
          </div>

          <!-- Email Field -->
          <div>
            <label for="create_email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
              class="text-red-500">*</span></label>
            <input type="email" name="email" id="create_email" required placeholder="Masukkan alamat email"
            x-model="createForm.email"
            :class="errors.email ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300
      hover:border-emerald-300 hover:shadow-md">
            <template x-if="errors.email">
            <p class="mt-2 text-sm text-red-600" x-text="errors.email[0]"></p>
            </template>
          </div>

          <!-- Password Field with Preview -->
          <div x-data="{ showPassword: false }">
            <label for="create_password" class="block text-sm font-medium text-gray-700 mb-2">Password <span
              class="text-red-500">*</span></label>
            <div class="relative">
            <input :type="showPassword ? 'text' : 'password'" name="password" id="create_password" required
              placeholder="Minimal 8 karakter" x-model="createForm.password"
              :class="errors.password ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 pr-10
      hover:border-emerald-300 hover:shadow-md">
            <button type="button" @click="showPassword = !showPassword"
              class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 transition-all duration-300">
              <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.066 7.5a10.523 10.523 0 01-4.064 5.942m-3.058-3.058a4 4 0 01-5.656-5.656m2.121 5.656a4 4 0 01-5.657-5.657" />
              </svg>
              <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            </div>
            <template x-if="errors.password">
            <p class="mt-2 text-sm text-red-600" x-text="errors.password[0]"></p>
            </template>
          </div>

          <!-- Confirm Password Field -->
          <div x-data="{ showConfirmPassword: false }">
            <label for="create_password_confirmation"
            class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span
              class="text-red-500">*</span></label>
            <div class="relative">
            <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation"
              id="create_password_confirmation" required placeholder="Ketik ulang password"
              x-model="createForm.password_confirmation"
              :class="errors.password_confirmation ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-emerald-500 focus:border-emerald-500'"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 pr-10
      hover:border-emerald-300 hover:shadow-md">
            <button type="button" @click="showConfirmPassword = !showConfirmPassword"
              class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 transition-all duration-300">
              <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.066 7.5a10.523 10.523 0 01-4.064 5.942m-3.058-3.058a4 4 0 01-5.656-5.656m2.121 5.656a4 4 0 01-5.657-5.657" />
              </svg>
              <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            </div>
            <template x-if="errors.password_confirmation">
            <p class="mt-2 text-sm text-red-600" x-text="errors.password_confirmation[0]"></p>
            </template>
          </div>
          </form>
        </div>

        <!-- Modal Footer -->
        <div
          class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
          <button type="button" @click="openCreateModal = false" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition-all duration-300
      hover:-translate-y-0.5 hover:shadow-md">
          Batal
          </button>
          <button type="button" @click="submitCreateForm" :disabled="loading" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white shadow-md text-sm font-medium transition-all duration-300
      disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px]
      hover:-translate-y-0.5 hover:shadow-lg">
          <span x-show="!loading">Simpan</span>
          <span x-show="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
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

      <!-- Edit User Modal -->
      <div x-show="openEditModalId !== null" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="openEditModalId = null" x-transition
        class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto border-2 border-blue-200">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-xl">
          <h2 class="text-xl font-bold text-white">Edit Pengguna Admin</h2>
          <p class="mt-1 text-sm text-blue-100">Perbarui informasi pengguna admin</p>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <form x-ref="editForm" @submit.prevent="submitEditForm" class="space-y-5">
          @csrf
          @method('PUT')

          <!-- Name Field -->
          <div>
            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
              class="text-red-500">*</span></label>
            <input type="text" name="name" id="edit_name" required x-model="userData.name"
            :class="errors.name ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300
      hover:border-blue-300 hover:shadow-md">
            <template x-if="errors.name">
            <p class="mt-2 text-sm text-red-600" x-text="errors.name[0]"></p>
            </template>
          </div>

          <!-- Email Field -->
          <div>
            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email <span
              class="text-red-500">*</span></label>
            <input type="email" name="email" id="edit_email" required x-model="userData.email"
            :class="errors.email ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
            class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300
      hover:border-blue-300 hover:shadow-md">
            <template x-if="errors.email">
            <p class="mt-2 text-sm text-red-600" x-text="errors.email[0]"></p>
            </template>
          </div>

          <!-- Password Field -->
          <div x-data="{ showEditPassword: false }">
            <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
            <input :type="showEditPassword ? 'text' : 'password'" name="password" id="edit_password"
              placeholder="Kosongkan jika tidak ingin mengubah" x-model="userData.password"
              :class="errors.password ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'"
              class="w-full px-4 py-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 pr-10
      hover:border-blue-300 hover:shadow-md">
            <button type="button" @click="showEditPassword = !showEditPassword"
              class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 transition-all duration-300">
              <svg x-show="!showEditPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.066 7.5a10.523 10.523 0 01-4.064 5.942m-3.058-3.058a4 4 0 01-5.656-5.656m2.121 5.656a4 4 0 01-5.657-5.657" />
              </svg>
              <svg x-show="showEditPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
            </div>
            <template x-if="errors.password">
            <p class="mt-2 text-sm text-red-600" x-text="errors.password[0]"></p>
            </template>
          </div>
          </form>
        </div>

        <!-- Modal Footer -->
        <div
          class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
          <button type="button" @click="openEditModalId = null" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition-all duration-300
      hover:-translate-y-0.5 hover:shadow-md">
          Batal
          </button>
          <button type="button" @click="submitEditForm" :disabled="loading" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-md text-sm font-medium transition-all duration-300
      disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px]
      hover:-translate-y-0.5 hover:shadow-lg">
          <span x-show="!loading">Simpan Perubahan</span>
          <span x-show="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
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

      <!-- Delete Confirmation Modal -->
      <div x-show="openDeleteModalId !== null" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/50"
        x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-100"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="openDeleteModalId = null" x-transition
        class="bg-white rounded-xl shadow-2xl w-full max-w-md border-2 border-red-200">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 p-6 bg-gradient-to-r from-red-600 to-pink-600 rounded-t-xl">
          <div class="flex items-center gap-3">
          <div class="p-2 rounded-full bg-white/20 text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-white">Hapus Pengguna Admin</h2>
            <p class="text-sm text-red-100">Aksi ini tidak dapat dibatalkan</p>
          </div>
          </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
          <div class="flex">
            <div class="ml-2">
            <p class="text-sm font-medium text-red-800" x-text="'Nama: ' + userData.name"></p>
            <p class="text-sm text-red-700 mt-1" x-text="'Email: ' + userData.email"></p>
            </div>
          </div>
          </div>
          <p class="mt-4 text-sm text-gray-600">Anda yakin ingin menghapus pengguna admin ini? Semua data yang
          terkait
          akan
          dihapus secara permanen.</p>
        </div>

        <!-- Modal Footer -->
        <div
          class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-b-xl">
          <button type="button" @click="openDeleteModalId = null" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 shadow-sm text-sm font-medium transition-all duration-300
      hover:-translate-y-0.5 hover:shadow-md">
          Batal
          </button>
          <button type="button" @click="submitDeleteForm" :disabled="loading" class="px-4 py-2 rounded-lg bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white shadow-md text-sm font-medium transition-all duration-300
      disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[100px]
      hover:-translate-y-0.5 hover:shadow-lg">
          <span x-show="!loading">Hapus</span>
          <span x-show="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
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