<!DOCTYPE html>
<html lang="id">

<head>
  <!-- Meta Tag Dasar -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portal Resmi Kabupaten Karawang</title>
  <meta name="description" content="Portal resmi informasi dan layanan publik Kabupaten Karawang">

  <!-- CSS Eksternal -->
  <!-- AOS CSS untuk animasi scroll -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts - Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />

  <!-- CSS Internal -->
  <style>
    /* Gaya dasar untuk body */
    body {
      font-family: 'Poppins', sans-serif;
      scroll-behavior: smooth;
      /* Scroll halus */
    }

    /* Gaya scrollbar kustom */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #065f46;
      /* Warna emerald */
      border-radius: 4px;
    }

    ::-webkit-scrollbar-track {
      background: #f0fdf4;
      /* Warna background */
    }

    /* Efek focus untuk input pencarian */
    .search-input:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.3);
      /* Shadow hijau */
      border-color: #059669;
      /* Border hijau */
    }

    /* Efek hover untuk card */
    .card-hover {
      transition: all 0.3s ease;
    }

    .card-hover:hover {
      transform: translateY(-4px);
      /* Efek mengambang */
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Container untuk wave SVG */
    .wave-container {
      height: 120px;
      overflow: hidden;
    }

    .wave-container svg {
      width: 100%;
      height: 100%;
    }

    /* Animasi untuk empty state */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    .float-animation {
      animation: float 3s ease-in-out infinite;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <!-- Navbar -->
  <nav
    class="fixed top-0 left-0 right-0 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 shadow-md px-5 py-3 z-50 flex items-center justify-between">
    <!-- Logo dan Nama Website -->
    <div class="flex items-center">
      <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Kabupaten Karawang" class="h-14 mr-3" width="56"
        height="56">
      <div>
        <h1 class="text-white text-2xl sm:text-3xl font-bold tracking-wide">Karawang</h1>
        <p class="text-emerald-200 text-xs leading-tight">Portal Informasi Karawang</p>
      </div>
    </div>

    <!-- Tombol Dashboard Admin (hanya muncul jika admin login) -->
    @auth('admin')
    <a href="{{ route('admin.dashboard') }}"
      class="bg-white text-emerald-900 px-4 py-2 rounded-lg shadow hover:bg-emerald-100 transition flex items-center gap-2"
      aria-label="Kembali ke Dashboard Admin">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
      stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9" />
      </svg>
      {{-- <span class="hidden sm:inline">Dashboard</span> --}}
    </a>
  @endauth
  </nav>

  <!-- Hero Section -->
  <section
    class="relative pt-40 pb-28 px-4 text-center overflow-hidden bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700">
    <div class="max-w-4xl mx-auto">
      <!-- Judul Hero -->
      <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4" data-aos="fade-down">
        Website Portal Karawang
      </h1>
      <!-- Subjudul Hero -->
      <p class="text-emerald-100 text-lg md:text-xl max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
        Temukan informasi dan layanan publik Kabupaten Karawang
      </p>
    </div>

    <!-- Wave Animation dengan Gradien dan Efek Halus -->
    <div class="wave-container absolute bottom-0 left-0 w-full h-32 overflow-hidden">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none" class="h-full w-full">
        <defs>
          <!-- Gradien untuk wave -->
          <linearGradient id="waveGradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#064e3b" /> <!-- Warna hijau tua -->
            <stop offset="50%" stop-color="#047857" /> <!-- Warna hijau medium -->
            <stop offset="100%" stop-color="#059669" /> <!-- Warna hijau muda -->
          </linearGradient>
        </defs>
        <!-- Path wave dengan animasi -->
        <path fill="url(#waveGradient)"
          d="M0,46L48,53C96,60,192,74,288,69C384,64,480,40,576,40C672,40,768,64,864,74C960,85,1056,83,1152,74C1248,64,1344,48,1392,40L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z">
          <!-- Animasi morphing untuk efek alami -->
          <animate attributeName="d" dur="18s" repeatCount="indefinite"
            values="M0,46L48,53C96,60,192,74,288,69C384,64,480,40,576,40C672,40,768,64,864,74C960,85,1056,83,1152,74C1248,64,1344,48,1392,40L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z;
                          M0,60L48,53C96,46,192,32,288,32C384,32,480,46,576,53C672,60,768,60,864,53C960,46,1056,32,1152,32C1248,32,1344,46,1392,53L1440,60L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z;
                          M0,32L48,40C96,48,192,64,288,69C384,74,480,69,576,60C672,51,768,37,864,32C960,27,1056,32,1152,40C1248,48,1344,60,1392,64L1440,69L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z;
                          M0,46L48,53C96,60,192,74,288,69C384,64,480,40,576,40C672,40,768,64,864,74C960,85,1056,83,1152,74C1248,64,1344,48,1392,40L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" />
        </path>
      </svg>
    </div>
  </section>

  <!-- Main Content -->
  <main class="px-4 max-w-7xl mx-auto relative z-10 mt-8">
    <!-- Search Form -->
    <form method="GET" action="{{ url('/') }}" class="mx-auto my-12 md:my-16 max-w-xl" data-aos="zoom-in">
      <div class="relative w-full">
        <label for="search" class="sr-only">Cari konten</label>
        <input type="text" id="search" name="search" placeholder="Cari konten..."
          value="{{ old('search', $search ?? '') }}"
          class="search-input w-full rounded-full px-5 py-3 pr-14 border-2 border-emerald-600 shadow-sm text-gray-800 transition">
        <button type="submit"
          class="absolute top-1/2 right-2 -translate-y-1/2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-full p-2 hover:from-emerald-600 hover:to-emerald-500 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </button>
      </div>
    </form>

    <!-- Cards Grid -->
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 pb-24" aria-live="polite">
      @forelse ($cards as $card)
      <!-- Card Item -->
      <article tabindex="0" data-aos="fade-up"
      class="bg-white rounded-xl shadow-md hover:shadow-lg transition hover:scale-[1.01] flex flex-col overflow-hidden card-hover">
      <div class="w-full">
        <img src="{{ asset('storage/' . $card->image_url) }}" alt="Logo {{ $card->title }}"
        class="w-full max-h-40 object-cover bg-gray-100" />
      </div>
      <div class="p-3 flex flex-col flex-grow">
        <h2 class="text-sm font-semibold text-emerald-900 mb-1">{{ $card->title }}</h2>
        <p class="text-emerald-700 text-xs mb-3 line-clamp-3">{{ $card->description }}</p>
        <a href="{{ $card->external_link }}" target="_blank" rel="noopener noreferrer"
        class="mt-auto inline-flex items-center gap-1 text-white font-medium px-3 py-1 rounded bg-gradient-to-r from-emerald-900 to-emerald-700 text-xs hover:from-emerald-700 hover:to-emerald-600">
        Baca Selengkapnya...
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
          d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 1 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
        </svg>
        </a>
      </div>
      </article>
    @empty
      <!-- Empty State -->
      <div class="col-span-full text-center py-16" data-aos="zoom-in" data-aos-duration="800">
      <div class="relative inline-block mb-6">
        <!-- Icon dengan animasi mengambang -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 float-animation" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <!-- Efek ping subtle -->
        <div class="absolute inset-0 rounded-full bg-emerald-100 opacity-0 animate-ping -z-10"
        style="animation-delay: 1s;"></div>
      </div>

      <h3 class="text-xl font-medium text-gray-600 mb-2" data-aos="fade-up" data-aos-delay="200">
        Oops! Tidak ada konten ditemukan
      </h3>
      <p class="text-gray-400 max-w-md mx-auto" data-aos="fade-up" data-aos-delay="300">
        Kami tidak dapat menemukan apa yang Anda cari. Coba kata kunci lain atau lihat rekomendasi kami.
      </p>

      @if(request()->has('search'))
      <div class="mt-6" data-aos="fade-up" data-aos-delay="400">
      <a href="{{ url('/') }}"
      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-emerald-700 to-emerald-600 hover:from-emerald-800 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-1">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
      </svg>
      Kembali ke halaman utama
      </a>
      </div>
    @endif

      <!-- Dekorasi bouncing dots -->
      <div class="mt-12 flex justify-center space-x-4 opacity-50">
        <div class="w-3 h-3 rounded-full bg-emerald-700 animate-bounce" style="animation-delay: 0.1s"></div>
        <div class="w-3 h-3 rounded-full bg-emerald-600 animate-bounce" style="animation-delay: 0.2s"></div>
        <div class="w-3 h-3 rounded-full bg-emerald-500 animate-bounce" style="animation-delay: 0.3s"></div>
      </div>
      </div>
    @endforelse
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-emerald-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-4 md:mb-0">
          <p class="text-sm">&copy; {{ date('Y') }} Pemerintah Kabupaten Karawang. Semua Hak Dilindungi.</p>
        </div>
        <div class="flex gap-6">
          <a href="{{ route('privacy') }}" class="text-sm hover:text-white transition">Kebijakan Privasi</a>
          <a href="{{ route('terms') }}" class="text-sm hover:text-white transition">Syarat & Ketentuan</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Inisialisasi AOS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    // Inisialisasi library AOS untuk animasi scroll
    AOS.init({
      duration: 600, // Durasi animasi
      easing: 'ease-out-quad', // Efek easing
      once: true, // Animasi hanya sekali
      offset: 100 // Offset trigger
    });
  </script>
</body>

</html>