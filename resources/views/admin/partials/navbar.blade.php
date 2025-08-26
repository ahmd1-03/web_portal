<nav :class="$store.sidebar.open ? 'ml-64' : 'ml-16'"
    :style="$store.sidebar.open ? 'width: calc(100% - 16rem)' : 'width: calc(100% - 4rem)'"
    class="fixed top-0 right-0 z-50 bg-white text-gray-800 flex items-center justify-between px-4 py-3 shadow-md transition-all duration-300">

    <div class="flex items-center gap-3 ml-auto">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-2 focus:outline-none hover:text-gray-600 transition">

                <!-- Avatar huruf (default) -->
                <div class="w-7 h-7 bg-emerald-600 rounded-full flex items-center justify-center text-xs font-semibold text-white navbar-avatar"
                    @if(Auth::guard('admin')->user()->profile) style="display:none;" @endif>
                    {{ Auth::guard('admin')->user()->name[0] ?? 'A' }}
                </div>

                <!-- Foto profil -->
                <img src="{{ Auth::guard('admin')->user()->profile ? Storage::url(Auth::guard('admin')->user()->profile) : '' }}"
                    alt="Profile Photo" class="w-7 h-7 rounded-full object-cover navbar-photo"
                    @if(!Auth::guard('admin')->user()->profile) style="display:none;" @endif>

                <!-- Nama pengguna -->
                <span class="text-xs font-medium hidden sm:inline navbar-username">
                    {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                </span>

                <!-- Ikon panah -->
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg py-2 z-50">
                <div class="px-3 py-2 border-b border-gray-100">
                    <p class="text-xs font-semibold navbar-username">
                        {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-[11px] text-gray-500 truncate navbar-email">
                        {{ Auth::guard('admin')->user()->email ?? '' }}
                    </p>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 px-3 py-2 text-xs text-red-600 hover:bg-gray-100 w-full text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7" />
                            <path d="M13 4H6a2 2 0 00-2 2v12a2 2 0 002 2h7" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        // Setelah AJAX update, jalankan fungsi untuk toggle navbar foto/avatar
        function updateNavbarProfile(name, email, profile_url) {
            document.querySelectorAll('.navbar-username').forEach(el => el.textContent = name);
            document.querySelectorAll('.navbar-email').forEach(el => el.textContent = email);

            if (profile_url) {
                document.querySelectorAll('.navbar-photo').forEach(img => {
                    img.src = profile_url;
                    img.style.display = 'block';
                });
                document.querySelectorAll('.navbar-avatar').forEach(el => el.style.display = 'none');
            } else {
                document.querySelectorAll('.navbar-photo').forEach(img => img.style.display = 'none');
                document.querySelectorAll('.navbar-avatar').forEach(el => el.style.display = 'flex');
            }
        }
    </script>
@endpush