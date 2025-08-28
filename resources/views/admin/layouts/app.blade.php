<!DOCTYPE html>
<html lang="id" x-data x-cloak>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Admin Panel') - Website Portal Karawang</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.store('sidebar', {
        open: true // Sidebar terbuka default
      });
    });
  </script>

  <style>
    [x-cloak] {
      display: none !important;
    }

    #splash-screen {
      position: fixed;
      inset: 0;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 99999;
      pointer-events: all;
      opacity: 1;
      transition: opacity 0.8s ease;
    }

    #splash-screen.fade-out {
      opacity: 0;
      pointer-events: none;
    }

    .loader {
      width: 60px;
      aspect-ratio: 2;
      --_g: no-repeat radial-gradient(circle closest-side, #10b981 90%, #0000);
      background: var(--_g) 0% 50%, var(--_g) 50% 50%, var(--_g) 100% 50%;
      background-size: calc(100% / 3) 50%;
      animation: l3 1.2s infinite linear;
    }

    @keyframes l3 {
      20% {
        background-position: 0% 0%, 50% 50%, 100% 50%
      }

      40% {
        background-position: 0% 100%, 50% 0%, 100% 50%
      }

      60% {
        background-position: 0% 50%, 50% 100%, 100% 0%
      }

      80% {
        background-position: 0% 50%, 50% 50%, 100% 100%
      }
    }
  </style>
</head>

<body class="bg-white text-gray-800 flex min-h-screen">
  <!-- Splash screen -->
  <div id="splash-screen" x-data x-init="
    setTimeout(() => $el.classList.add('fade-out'), 2000);
    setTimeout(() => $el.style.display = 'none', 2800);
  ">
    <div class="loader"></div>
  </div>

  @include('admin.partials.sidebar')

  <div class="flex-1 flex flex-col min-h-screen">
    @include('admin.partials.navbar')
    <main :class="$store.sidebar.open ? 'ml-64' : 'ml-16'"
      class="flex-1 pt-24 pr-6 pl-0 pb-6 overflow-auto transition-all duration-300">
      @yield('content')
    </main>
  </div>

  <!-- tempat untuk script tambahan -->
  @stack('scripts')

  <!-- Activity Monitor untuk logout otomatis -->
  @if(auth()->guard('admin')->check())
    @vite(['resources/js/activityMonitor.js'])
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        window.activityMonitor = new ActivityMonitor();
      });
    </script>
  @endif
</body>

</html>