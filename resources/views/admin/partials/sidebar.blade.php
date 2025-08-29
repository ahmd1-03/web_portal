<!-- ===================== BAGIAN SIDEBAR ADMIN ===================== -->
<!-- Komponen sidebar dengan toggle state dan animasi transisi -->
<div :class="$store.sidebar.open ? 'w-64' : 'w-16'"
    class="fixed top-0 left-0 h-full bg-gradient-to-b from-emerald-900 to-emerald-700 text-white shadow-lg transition-all duration-300 z-40 flex flex-col"
    :class="{'-translate-x-full md:translate-x-0': !$store.sidebar.open}">

    <!-- ===================== HEADER SIDEBAR ===================== -->
    <!-- Bagian header sidebar dengan logo dan tombol toggle -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-emerald-600">
        <div class="flex items-center gap-1" x-show="$store.sidebar.open" x-transition.opacity.duration.300ms>
            <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-10 w-10 rounded" />
            <div>
                <h2 class="font-bold text-lg select-none">KARAWANG</h2>
                <p class="text-xs text-emerald-200 select-none">Dashboard Admin Website Portal</p>
            </div>
        </div>

        <!-- Tombol toggle sidebar -->
        <button @click="$store.sidebar.open = !$store.sidebar.open; saveSidebarState()"
            class="text-white focus:outline-none p-2 rounded hover:bg-emerald-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
    </div>

    <!-- ===================== MENU NAVIGASI ===================== -->
    <!-- Daftar menu navigasi untuk admin -->
    <nav class="flex flex-col mt-6 space-y-3 px-2">
        
        <!-- ========== MENU LIHAT WEBSITE ========== -->
        <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-2 rounded transition hover:bg-emerald-700
           {{ request()->is('/') ? 'bg-emerald-900 text-emerald-100' : 'text-white' }}"
            :class="$store.sidebar.open ? 'justify-start' : 'justify-center'">
            <svg class="w-5 h-5 {{ request()->is('/') ? 'fill-emerald-300' : 'fill-white' }}" viewBox="0 0 24 24"
                fill="currentColor">
                <path
                    d="M12 4.5C7.305 4.5 3.5 7.805 3.5 12c0 4.195 3.805 7.5 8.5 7.5s8.5-3.305 8.5-7.5c0-4.195-3.805-7.5-8.5-7.5zm0 12a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9z" />
                <circle cx="12" cy="12" r="2.25" />
            </svg>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms>Lihat Website</span>
        </a>

        <!-- ========== MENU HOME/DASHBOARD ========== -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded transition hover:bg-emerald-700
           {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-900 text-emerald-100' : 'text-white' }}"
            :class="$store.sidebar.open ? 'justify-start' : 'justify-center'">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'fill-emerald-300' : 'fill-white' }}"
                viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M3 9.75L12 3l9 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 20.25v-10.5zM12 5.25L6 9.75v7.5h12v-7.5l-6-4.5z" />
            </svg>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms>Home</span>
        </a>

        <!-- ========== MENU MANAJEMEN KARTU ========== -->
        <a href="{{ route('admin.cards.index') }}" class="flex items-center gap-3 px-3 py-2 rounded transition hover:bg-emerald-700
           {{ request()->routeIs('admin.cards.index') ? 'bg-emerald-900 text-emerald-100' : 'text-white' }}"
            :class="$store.sidebar.open ? 'justify-start' : 'justify-center'">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.cards.index') ? 'fill-emerald-300' : 'fill-white' }}"
                viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zm0-10v2h14V7H7z" />
            </svg>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms>Manajemen Kartu</span>
        </a>

        <!-- ========== MENU MANAJEMEN PENGGUNA ========== -->
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded transition hover:bg-emerald-700
           {{ request()->routeIs('admin.users.index') ? 'bg-emerald-900 text-emerald-100' : 'text-white' }}"
            :class="$store.sidebar.open ? 'justify-start' : 'justify-center'">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.index') ? 'fill-emerald-300' : 'fill-white' }}"
                viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms>Manajemen Pengguna</span>
        </a>

        <!-- ========== MENU PENGATURAN PROFIL ========== -->
        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded transition hover:bg-emerald-700
           {{ request()->routeIs('admin.profile.edit') ? 'bg-emerald-900 text-emerald-100' : 'text-white' }}"
            :class="$store.sidebar.open ? 'justify-start' : 'justify-center'">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.profile.edit') ? 'fill-emerald-300' : 'fill-white' }}"
                viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
            <span x-show="$store.sidebar.open" x-transition.opacity.duration.300ms>Pengaturan Profil</span>
        </a>
    </nav>
</div>

<!-- ===================== BAGIAN JAVASCRIPT ===================== -->
<!-- Script untuk mengelola state sidebar dengan Alpine.js -->
<script>
    // Inisialisasi Alpine.js store untuk sidebar
    document.addEventListener('alpine:init', () => {
        Alpine.store('sidebar', {
            open: false, // Default tertutup
            init() {
                // Mengambil status sidebar dari localStorage
                const savedState = localStorage.getItem('sidebarOpen');
                this.open = savedState ? JSON.parse(savedState) : false;
            }
        });
    });

    // Fungsi untuk menyimpan status sidebar ke localStorage
    function saveSidebarState() {
        const sidebarOpen = Alpine.store('sidebar').open;
        localStorage.setItem('sidebarOpen', JSON.stringify(sidebarOpen));
    }
</script>