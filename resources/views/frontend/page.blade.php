<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Admin Panel') - Website Portal Karawang</title>

  <!-- Load CSS dan JS dengan Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    /* Mencegah scroll di seluruh body dan html */
    html, body {
      overflow: hidden;
      height: 100%;
    }

    /* Tinggi sidebar agar penuh dan tidak ikut scroll */
    .sidebar {
      height: 100vh;
    }

    /* Navbar tetap di atas dan tidak ikut scroll */
    .navbar-fixed {
      height: 64px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 50;
    }

    /* Konten scrollable hanya bagian utama */
    .content-scrollable {
      overflow-y: auto;
      height: calc(100vh - 64px); /* dikurangi tinggi navbar */
    }

    /* Wrapper konten utama agar tidak ketindihan navbar */
    .content-wrapper {
      margin-top: 64px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 0;
    }

    /* Animasi smooth saat buka/tutup sidebar */
    .sidebar-transition {
      transition: transform 0.3s ease, width 0.3s ease;
    }
  </style>
</head>
<body class="bg-gray-100 flex flex-col h-screen" x-data="{ sidebarOpen: true }">

  <!-- Wrapper sidebar dan konten utama -->
  <div class="flex flex-1 overflow-hidden">

    <!-- Sidebar -->
    <div :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full w-16': !sidebarOpen }"
         class="sidebar sidebar-transition fixed md:relative z-40 bg-gradient-to-b from-emerald-900 to-emerald-700 text-white shadow-lg">

      <!-- Header Sidebar -->
      <div class="flex items-center justify-between px-4 py-4 border-b border-emerald-600">
        <div class="flex items-center gap-3" x-show="sidebarOpen" x-transition>
          <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-10 w-10 rounded" />
          <div>
            <h2 class="font-bold text-lg leading-tight">KARAWANG</h2>
            <p class="text-xs text-emerald-200">Dahboard Admin Website Portal</p>
          </div>
        </div>
        <!-- Tombol toggle sidebar -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
               stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>
      </div>

      <!-- Menu Navigasi -->
      <nav class="flex flex-col mt-6 space-y-3 px-4">
        <!-- Link Lihat Website -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition"
           :class="{ 'justify-center': !sidebarOpen }">
          <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
          </svg>
          <span x-show="sidebarOpen" x-transition>Lihat Website</span>
        </a>

        <!-- Link Manajemen Kartu -->
        <a href="{{ route('admin.cards.index') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition"
           :class="{ 'justify-center': !sidebarOpen }">
          <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
            <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zm0-10v2h14V7H7z" />
          </svg>
          <span x-show="sidebarOpen" x-transition>Manajemen Kartu</span>
        </a>

        <!-- Link Manajemen Pengguna -->
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition"
           :class="{ 'justify-center': !sidebarOpen }">
          <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
          </svg>
          <span x-show="sidebarOpen" x-transition>Manajemen Pengguna</span>
        </a>
      </nav>
    </div>

    <!-- Konten utama -->
    <div class="flex-1 flex flex-col content-wrapper">
      
      <!-- Navbar (tetap di atas) -->
      <nav class="navbar-fixed bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white flex items-center justify-between px-6 shadow-md">
        <!-- Tombol toggle sidebar untuk mobile -->
        <button @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar" class="md:hidden focus:outline-none">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
               stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>
        <!-- Judul halaman -->
        <h1 class="text-lg font-semibold">@yield('page_title', 'Dashboard Admin')</h1>
        <!-- Menu user dropdown -->
        <div class="flex items-center gap-4">
          <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="focus:outline-none flex items-center gap-2">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                   stroke-linecap="round" stroke-linejoin="round">
                <path d="M5.121 17.804A9 9 0 1118.88 6.196 9 9 0 015.12 17.804z" />
                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </button>
            <!-- Dropdown logout -->
            <div x-show="open" @click.outside="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
              <a href="{{ route('admin.logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
            </div>
          </div>
        </div>
      </nav>

      <!-- Konten halaman (scrollable) -->
      <main class="content-scrollable p-6">
        @yield('content')
      </main>
    </div>
  </div>

</body>
</html>
