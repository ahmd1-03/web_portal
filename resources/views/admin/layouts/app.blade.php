<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Admin Panel') - Website Portal Karawang</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex" x-data="{ sidebarOpen: true }">

  <!-- Sidebar -->
  @include('admin.partials.sidebar')

  <!-- Main content -->
  <div class="flex-1 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white flex items-center justify-between px-6 py-4 shadow-md">
      <button @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar" class="md:hidden focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
      </button>
      <h1 class="text-lg font-semibold">@yield('page_title', 'Dashboard Admin')</h1>
      <div class="flex items-center gap-4">
        <a href="{{ url('/') }}" class="bg-white text-emerald-700 px-4 py-2 rounded shadow hover:bg-emerald-100 transition" aria-label="Kembali ke Halaman Utama Website Portal Karawang">
          Lihat Website
        </a>
        <!-- User menu -->
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open" aria-haspopup="true" aria-expanded="open" class="focus:outline-none flex items-center gap-2">
            <span class="sr-only">User menu</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5.121 17.804A9 9 0 1118.88 6.196 9 9 0 015.12 17.804z" />
              <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </button>
          <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
            <a href="{{ route('admin.logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page content -->
    <main :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="flex-1 p-6 overflow-auto transition-all duration-300">
      @yield('content')
    </main>
  </div>

</body>
</html>
