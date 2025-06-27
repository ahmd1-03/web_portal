<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Website Portal Karawang</title>

  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />

  <!-- AlpineJS -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />

  <style>
    body { font-family: 'Poppins', sans-serif; }

    /* Custom scrollbar kecil */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-thumb {
      background-color: #065f46; /* hijau gelap yang lembut */
      border-radius: 3px;
    }

    /* Fokus input search dengan gradasi yang lembut */
    input:focus {
      outline: none;
      box-shadow: 0 0 6px 3px rgba(5, 150, 105, 0.5);
      border-color: #059669; /* hijau sedang */
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Navbar: Fixed atas dengan gradasi hijau lembut dan warna teks cerah -->
  <nav class="fixed top-0 left-0 right-0 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 shadow-md px-5 py-3 z-50 flex items-center justify-between">
    <div class="flex items-center">
      <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-12 mr-5" />
      <div>
        <h1 class="text-white text-2xl sm:text-3xl font-bold tracking-wide">Karawang</h1>
        <p class="text-emerald-200 text-sm font-light">Portal Resmi Informasi Karawang</p>
      </div>
    </div>
    @auth('admin')

    {{-- button untuk kembali ke admin, button ii hnya untuk amdin --}}
  <a href="{{ route('admin.dashboard') }}" 
   class="bg-white text-emerald-900 px-4 py-2 rounded shadow hover:bg-emerald-100 transition flex items-center gap-2" 
   aria-label="Kembali ke Dashboard Admin" 
   title="Kembali ke Dashboard Admin">
  <svg xmlns="http://www.w3.org/2000/svg" 
       class="h-6 w-6" 
       fill="none" 
       stroke="currentColor" 
       stroke-width="2" 
       stroke-linecap="round" 
       stroke-linejoin="round" 
       viewBox="0 0 24 24" 
       role="img" 
       aria-hidden="true">
    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
    <polyline points="16 17 21 12 16 7" />
    <line x1="21" y1="12" x2="9" y2="12" />
  </svg>
 
</a>


    @endauth
  </nav>

  <!-- Hero Section dengan SVG Wave Background -->
  <section class="relative pt-40 pb-28 px-4 text-center overflow-hidden bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700">
    <!-- Teks Sambutan -->
    <h1 class="text-4xl sm:text-5xl font-extrabold text-emerald-100 mb-4 relative z-10" data-aos="fade-down" style="text-shadow: 0 2px 6px rgba(0,0,0,0.3);">
      Selamat Datang
    </h1>
    <p class="text-emerald-200 text-lg max-w-xl mx-auto relative z-10" data-aos="fade-up" style="text-shadow: 0 1px 3px rgba(0,0,0,0.2);">
      Ini adalah portal resmi informasi Karawang.
    </p>

    <!-- SVG Wave Background di bawah teks -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] pointer-events-none" style="height: 120px;">
      <svg
        class="relative block w-full h-full"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 120"
        preserveAspectRatio="none"
      >
        <defs>
          <!-- Gradient yang lebih lembut dan natural -->
          <linearGradient id="wave-gradient" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0%" stop-color="#065f46" /> <!-- hijau gelap -->
            <stop offset="100%" stop-color="#10b981" /> <!-- hijau cerah -->
          </linearGradient>
        </defs>
        <path fill="url(#wave-gradient)" d="M0,0 C480,120 960,0 1440,120 L1440,120 L0,120 Z"></path>
      </svg>
    </div>
  </section>

  <!-- Main Content -->
  <main class="px-4 max-w-7xl mx-auto relative z-10">

    <!-- Form Pencarian dengan icon button di dalam input -->
    <form method="GET" action="{{ url('/') }}" class="mx-auto my-16 max-w-xs sm:max-w-md md:max-w-xl" data-aos="zoom-in">
      <div class="relative w-full">
        <!-- Input Search -->
        <input
          type="text"
          name="search"
          placeholder="Cari konten..."
          value="{{ old('search', $search ?? '') }}"
          class="w-full rounded-full px-4 py-3 pr-14 border border-emerald-700 shadow-sm text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition"
        />
        <!-- Tombol icon Search di dalam input kanan -->
        <button type="submit"
          class="absolute top-1/2 right-1.5 -translate-y-1/2 bg-gradient-to-r from-emerald-900 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white rounded-full p-2 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-emerald-600 transition"
          aria-label="Cari"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </button>
      </div>
    </form>

    <!-- Bagian Kartu Konten -->
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 pb-24" aria-live="polite">
      @forelse ($cards as $card)
        <article tabindex="0" data-aos="fade-up"
          class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 hover:scale-[1.015] flex flex-col overflow-hidden">
          <img src="{{ $card->image_url }}" alt="Logo {{ $card->title }}" class="h-24 object-contain bg-gray-100 p-4" />
          <div class="p-4 flex flex-col flex-grow">
            <h2 class="text-lg font-semibold text-emerald-900 mb-1">{{ $card->title }}</h2>
            <p class="text-emerald-700 text-sm flex-grow">{{ $card->description }}</p>
            <a href="{{ $card->external_link }}" target="_blank" rel="noopener noreferrer"
              class="mt-4 inline-flex items-center gap-2 text-white font-medium px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-900 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 transition"
            >
              Baca Selengkapnya
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 1 1 .708-.708l4 4a.5.5 0 0 1 0 
                  .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
              </svg>
            </a>
          </div>
        </article>
      @empty
        <p class="text-center text-emerald-700 col-span-full">Tidak ada konten ditemukan.</p>
      @endforelse
    </section>
  </main>

  <!-- Footer dengan warna gradasi lembut senada navbar -->
  <footer class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-emerald-100 mt-20 py-6">
    <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center text-center gap-4">
      <p class="text-sm select-none">&copy; {{ date('Y') }} Pemerintah Kabupaten Karawang. Semua Hak Dilindungi.</p>
      <div class="flex gap-6 text-sm">
        <a href="{{ route('privacy') }}" class="hover:text-white transition">Kebijakan Privasi</a>
        <a href="{{ route('terms') }}" class="hover:text-white transition">Syarat & Ketentuan</a>
      </div>
    </div>
  </footer>

  <!-- AOS Init -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true,
    });
  </script>
</body>
</html>
