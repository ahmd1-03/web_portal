<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $page->title }} - Portal Karawang</title>
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

  <nav class="fixed top-0 left-0 right-0 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 shadow-md px-5 py-3 z-50 flex items-center">
    <a href="{{ route('home') }}" class="text-white text-2xl font-bold tracking-wide">Portal Karawang</a>
  </nav>

  <main class="pt-24 max-w-4xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-emerald-900 mb-6" data-aos="fade-down">{{ $page->title }}</h1>
    <article class="prose max-w-none text-emerald-800" data-aos="fade-up">
      {!! $page->content !!}
    </article>
  </main>

  <footer class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-emerald-100 mt-20 py-6 text-center">
    &copy; {{ date('Y') }} Pemerintah Kabupaten Karawang. Semua Hak Dilindungi.
  </footer>

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
