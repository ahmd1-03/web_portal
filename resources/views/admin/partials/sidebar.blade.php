<!-- Sidebar Admin -->
<div :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }"
     class="fixed top-0 left-0 h-full bg-gradient-to-b from-emerald-900 to-emerald-700 text-white shadow-lg transition-all duration-300 ease-in-out z-40">

    <!-- Header Sidebar -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-emerald-600">

        <div class="flex items-center gap-3" x-show="sidebarOpen">
            <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-10 w-10 rounded" />
            <div>
                <h2 class="font-bold text-lg leading-tight">Karawang</h2>
                <p class="text-xs text-emerald-200">Portal Resmi</p>
            </div>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
            <!-- Icon hamburger -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
    </div>

    <!-- Menu Navigasi -->
   <nav class="flex flex-col mt-6 space-y-3 px-4">
        <a href="{{ url('/') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition" :class="{ 'justify-center': !sidebarOpen }">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <span x-show="sidebarOpen">Lihat Website</span>
        </a>
        <a href="{{ route('admin.cards.index') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition" :class="{ 'justify-center': !sidebarOpen }">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zm0-10v2h14V7H7z"/>
            </svg>
            <span x-show="sidebarOpen">Manajemen Kartu</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 hover:bg-emerald-800 px-3 py-2 rounded transition" :class="{ 'justify-center': !sidebarOpen }">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <span x-show="sidebarOpen">Manajemen Pengguna</span>
        </a>
    </nav>
</div>
