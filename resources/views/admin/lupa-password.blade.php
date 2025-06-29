<!-- Meng-extend layout auth dasar untuk admin -->
@extends('admin.layouts.auth')

<!-- Menetapkan judul halaman -->
@section('title', 'Lupa Password Admin')

<!-- Memulai section content utama -->
@section('content')
    <!-- Container utama dengan flex layout -->
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-6">
        <!-- Container form dengan border dan shadow -->
        <div class="w-full max-w-6xl overflow-hidden border border-gray-200 rounded-xl shadow-lg flex flex-col md:flex-row">

            <!-- Kolom Kiri - Bagian dekoratif -->
            <div class="w-full md:w-1/2 text-white flex flex-col items-center px-10 pt-8 md:pt-14 pb-6 relative overflow-hidden">
                <!-- Background gradient dengan warna hijau -->
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-emerald-800">
                    <!-- Lingkaran dekoratif dengan efek transparan -->
                    <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay"></div>
                    <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay"></div>
                    <div class="absolute top-1/4 right-1/4 w-40 h-40 bg-white/5 rounded-full mix-blend-overlay"></div>
                </div>

                <!-- Motif batik sebagai elemen dekoratif -->
                <div class="absolute top-4 right-4 opacity-10 pointer-events-none z-0">
                    <img src="{{ url('images/motif-batik.png') }}" alt="Motif Batik" class="w-32 sm:w-40"
                        onerror="this.style.display='none'">
                </div>

                <!-- Konten utama kolom kiri -->
                <div class="relative z-10 w-full flex flex-col items-center justify-center h-full py-4 md:py-0">
                    <!-- Container untuk logo dan teks -->
                    <div class="flex flex-col items-center text-center">
                        <!-- Logo Karawang -->
                        <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang"
                            class="h-24 md:h-32 lg:h-36 drop-shadow-md">

                        <!-- Teks judul dan deskripsi -->
                        <div class="mt-4 md:mt-6 space-y-2">
                            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold">Portal Karawang</h2>
                            <p class="text-sm md:text-base lg:text-lg leading-relaxed">
                                Sistem Informasi Admin Resmi<br>
                                untuk Pengelolaan Data Karawang
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Wave animasi di bagian bawah -->
                <div class="absolute bottom-0 left-0 right-0 h-32 z-0">
                    <svg class="w-full h-full" viewBox="0 0 1440 120" preserveAspectRatio="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <!-- Gradient untuk wave -->
                            <linearGradient id="waveGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#059669" />
                                <stop offset="100%" stop-color="#047857" />
                            </linearGradient>
                        </defs>
                        <!-- Path wave dengan animasi -->
                        <path fill="url(#waveGradient)" fill-opacity="1"
                            d="M0,96L48,85.3C96,75,192,53,288,48C384,43,480,53,576,64C672,75,768,85,864,80C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,120L0,120Z">
                            <animate attributeName="d"
                                values="M0,96L48,85.3C96,75,192,53,288,48C384,43,480,53,576,64C672,75,768,85,864,80C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,120L0,120Z;
                                        M0,64L48,74.7C96,85,192,107,288,112C384,117,480,107,576,96C672,85,768,75,864,69.3C960,64,1056,64,1152,69.3C1248,75,1344,85,1392,90.7L1440,96L1440,120L0,120Z;
                                        M0,32L48,42.7C96,53,192,75,288,80C384,85,480,75,576,64C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L0,120Z;
                                        M0,96L48,85.3C96,75,192,53,288,48C384,43,480,53,576,64C672,75,768,85,864,80C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,120L0,120Z"
                                dur="15s" repeatCount="indefinite" />
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Kolom Kanan - Form lupa password -->
            <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-10 bg-white space-y-6">
                <!-- Judul form -->
                <h1 class="text-2xl sm:text-3xl font-bold text-emerald-800 text-center">Lupa Password Admin</h1>

                <!-- Menampilkan status/notifikasi jika ada -->
                @if (session('status'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow-sm text-center mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form lupa password -->
                <form id="forgotPasswordForm" action="{{ route('admin.kirimLinkResetPassword') }}" method="POST" class="space-y-4">
                    @csrf <!-- CSRF token untuk keamanan -->

                    <!-- Input email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition" />
                    </div>

                    <!-- Tombol submit -->
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                        Kirim Link Reset Password
                    </button>
                </form>

                <!-- Link kembali ke halaman login -->
                <p class="text-center text-sm text-gray-600">
                    <a href="{{ route('admin.login') }}" class="text-emerald-600 hover:text-emerald-800 font-medium">Kembali ke halaman login</a>
                </p>
            </div>
        </div>
    </div>
@endsection