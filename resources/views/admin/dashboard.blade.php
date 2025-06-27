<!-- File: dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin - Website Portal Karawang</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- AlpineJS -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 text-gray-800 flex" x-data="{ sidebarOpen: true }">

  <!-- Sidebar -->
  <div :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }"
    class="fixed top-0 left-0 h-full bg-gradient-to-b from-emerald-900 to-emerald-700 text-white shadow-lg transition-all duration-300 ease-in-out z-40">

    <!-- Header Sidebar -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-emerald-600">
      <div class="flex items-center gap-3" x-show="sidebarOpen">
        <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-10 w-10 rounded" />
        <div>
          <h2 class="font-bold text-lg leading-tight">KARAWANG</h2>
          <p class="text-xs text-emerald-200">Dashboard Admin Website Portal</p>
        </div>
      </div>
      <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
      </button>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex flex-col mt-6 space-y-3 px-3">
      <a href="{{ url('/') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition"
        :class="{ 'justify-center': !sidebarOpen }">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 24 24">
          <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
        </svg>
        <span x-show="sidebarOpen">Lihat Website</span>
      </a>
      <a href="{{ route('admin.cards.index') }}"
        class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition"
        :class="{ 'justify-center': !sidebarOpen }">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 24 24">
          <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zm0-10v2h14V7H7z" />
        </svg>
        <span x-show="sidebarOpen">Manajemen Kartu</span>
      </a>
      <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition"
        :class="{ 'justify-center': !sidebarOpen }">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 24 24">
          <path
            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
        </svg>
        <span x-show="sidebarOpen">Manajemen Pengguna</span>
      </a>
    </nav>
  </div>

  <!-- Konten Utama -->
  <div :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="flex-1 flex flex-col min-h-screen transition-all duration-300">

    <!-- Navbar -->
    <nav
      class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white px-6 py-6 shadow-md flex justify-between items-center">
      <!-- Tombol Toggle Sidebar (mobile) -->
      <button @click="sidebarOpen = !sidebarOpen" class="md:hidden focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
      </button>

      <h1 class="text-lg sm:text-xl font-semibold tracking-wide">Dashboard Admin</h1>

      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="focus:outline-none flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5.121 17.804A9 9 0 1118.88 6.196 9 9 0 015.12 17.804z" />
            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </button>
        <div x-show="open" @click.outside="open = false"
          class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded-md shadow-lg py-2 z-50">
          <a href="{{ route('admin.logout') }}" class="block px-4 py-2 hover:bg-gray-100 transition">Logout</a>
        </div>
      </div>
    </nav>

    <!-- Isi Dashboard -->
    <main class="p-6 flex-1 overflow-auto">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-emerald-900 mb-2">Selamat Datang 👋</h2>
        <p class="text-emerald-700">Kelola informasi website portal Karawang melalui menu di bawah ini.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Manajemen Kartu -->
        <a href="{{ route('admin.cards.index') }}"
          class="group bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition transform hover:-translate-y-1">
          <div class="flex items-center gap-4 mb-3">
            <div class="bg-emerald-100 text-emerald-800 p-3 rounded-full">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M5 3h14a2 2 0 012 2v16l-7-4-7 4V5a2 2 0 012-2z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-emerald-800">Manajemen Kartu</h3>
          </div>
          <p class="text-sm text-emerald-700">Atur konten utama di halaman portal.</p>
        </a>

        <!-- Manajemen Pengguna -->
        <a href="{{ route('admin.users.index') }}"
          class="group bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition transform hover:-translate-y-1">
          <div class="flex items-center gap-4 mb-3">
            <div class="bg-emerald-100 text-emerald-800 p-3 rounded-full">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-emerald-800">Manajemen Pengguna</h3>
          </div>
          <p class="text-sm text-emerald-700">Kelola admin yang dapat mengakses dashboard.</p>
        </a>
      </div>
    </main>
  </div>

</body>

</html>