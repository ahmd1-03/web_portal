<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true }" x-cloak>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Panel') - Website Portal Karawang</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 text-gray-800 flex min-h-screen">

  <!-- Sidebar -->
  @include('admin.partials.sidebar')

  <!-- Main Section -->
  <div class="flex-1 flex flex-col min-h-screen">

    <!-- Navbar -->
    @include('admin.partials.navbar')

    <!-- Page content -->
    <main :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="flex-1 pt-24 p-6 overflow-auto transition-all duration-300">
      @yield('content')
    </main>

  </div>

</body>

</html>