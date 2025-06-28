@extends('admin.layouts.app')

@section('content')
    <main class="container mx-auto p-4">

        <!-- Selamat Datang -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-emerald-900 mb-2">Selamat Datang 👋</h2>
            <p class="text-emerald-700">Kelola informasi website portal Karawang melalui menu di bawah ini.</p>
        </div>

        <!-- Kartu Akses Menu -->
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
@endsection