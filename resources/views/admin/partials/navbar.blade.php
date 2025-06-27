<nav :class="sidebarOpen ? 'ml-64' : 'ml-16'"
  :style="sidebarOpen ? 'width: calc(100% - 16rem)' : 'width: calc(100% - 4rem)'"
  class="fixed top-0 right-0 z-50 bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-700 text-white flex items-center justify-between px-6 py-6 shadow-md transition-all duration-300">

  <div class="flex items-center gap-4 ml-auto">
    <!-- Toggle Sidebar Button -->
    {{-- <button @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar" class="focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" stroke="currentColor"
        stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="12" x2="21" y2="12" />
        <line x1="3" y1="6" x2="21" y2="6" />
        <line x1="3" y1="18" x2="21" y2="18" />
      </svg>
    </button> --}}

    <!-- User Menu -->
    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open" class="flex items-center focus:outline-none" aria-label="User Menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" stroke="currentColor"
          stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10 17l5-5-5-5" />
          <path d="M15 12H3" />
          <path d="M19 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" />
        </svg>
      </button>

      <div x-show="open" @click.outside="open = false"
        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
        <a href="{{ route('admin.logout') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-5 h-5 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3H9m9 0l-3-3m3 3l-3 3" />
          </svg>
          Logout
        </a>
      </div>
    </div>
  </div>
</nav>