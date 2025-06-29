<nav :class="$store.sidebar.open ? 'ml-64' : 'ml-16'"
  :style="$store.sidebar.open ? 'width: calc(100% - 16rem)' : 'width: calc(100% - 4rem)'"
  class="fixed top-0 right-0 z-50 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white flex items-center justify-between px-6 py-6 shadow-md transition-all duration-300">

  <div class="flex items-center gap-4 ml-auto">

    <!-- Dropdown Profil Pengguna -->
    <div x-data="{ open: false }" class="relative">
      <!-- Tombol pemicu dropdown -->
      <button @click="open = !open"
        class="flex items-center gap-3 focus:outline-none text-white hover:text-gray-100 transition">

        <!-- Avatar (inisial pengguna atau gambar) -->
        <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-sm font-semibold">
          {{ Auth::guard('admin')->user()->name[0] ?? 'A' }}
        </div>

        <!-- Nama pengguna -->
        <span class="text-sm font-medium hidden sm:inline">
          {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
        </span>

        <!-- Ikon panah bawah -->
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <!-- Dropdown menu -->
      <div x-show="open" @click.outside="open = false" x-transition
        class="absolute right-0 mt-2 w-52 bg-white text-gray-800 rounded-md shadow-lg py-2 z-50">

        <!-- Header kecil -->
        <div class="px-4 py-2 border-b border-gray-100">
          <p class="text-sm font-semibold">
            {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
          </p>
          <p class="text-xs text-gray-500 truncate">
            {{ Auth::guard('admin')->user()->email ?? '' }}
          </p>
        </div>

        <!-- Menu logout -->
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit"
            class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
            <!-- Ikon Logout -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path d="M17 16l4-4m0 0l-4-4m4 4H7" />
              <path d="M13 4H6a2 2 0 00-2 2v12a2 2 0 002 2h7" />
            </svg>
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>