<!-- Meng-extend layout auth dasar untuk admin -->
@extends('admin.layouts.auth')

<!-- Menetapkan judul halaman -->
@section('title', 'Lupa Password Admin')

<!-- Memulai section content utama -->
@section('content')
    <!-- Container utama dengan flex layout -->
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">
        <!-- Container form dibuat lebih panjang tapi tidak terlalu lebar -->
        <div class="w-full max-w-3xl overflow-hidden border border-gray-200 rounded-xl shadow-lg flex flex-col md:flex-row">

            <!-- Kolom Kiri - Bagian dekoratif -->
            <div class="w-full md:w-1/2 text-white flex flex-col items-center px-8 pt-12 pb-12 relative overflow-hidden">
                <!-- Background gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-emerald-800">
                    <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay"></div>
                    <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/10 rounded-full mix-blend-overlay"></div>
                </div>

                <!-- Motif batik -->
                <div class="absolute top-4 right-4 opacity-10 pointer-events-none z-0">
                    <img src="{{ url('images/motif-batik.png') }}" alt="Motif Batik" class="w-28 sm:w-36"
                        onerror="this.style.display='none'">
                </div>

                <!-- Konten utama kolom kiri -->
                <div class="relative z-10 w-full flex flex-col items-center text-center h-full justify-center space-y-6">
                    <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-32 md:h-40 drop-shadow-md">
                    <h2 class="text-2xl md:text-3xl font-bold">Portal Karawang</h2>
                    <p class="text-sm md:text-base leading-relaxed">
                        Sistem Informasi Admin Resmi<br>
                        untuk Pengelolaan Data Karawang
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan - Form lupa password -->
            <div class="w-full md:w-1/2 p-8 md:py-12 md:px-10 bg-white flex flex-col justify-center">
                <!-- Judul dan deskripsi form -->
                <div class="text-center mb-6">
                    <h1 class="text-2xl sm:text-3xl font-bold text-emerald-800">Lupa Password?</h1>
                    <p class="text-gray-500 mt-2 text-sm">Masukkan email Anda untuk menerima kode reset password.</p>
                </div>

                <!-- Menampilkan status/notifikasi jika ada -->
                @if (session('status'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow-sm text-sm mb-6 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Form lupa password -->
                <form id="forgotPasswordForm" action="{{ route('admin.kirimLinkResetPassword') }}" method="POST"
                    class="space-y-6">
                    @csrf

                    <!-- Input email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                class="w-full px-4 py-3 pr-10 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition @error('email') border-red-500 bg-red-50 @enderror"
                                placeholder="anda@email.com" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tombol submit -->
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Kirim Kode Reset</span>
                    </button>
                </form>

                <!-- Link kembali ke halaman login -->
                <div class="text-center text-sm text-gray-600 pt-4">
                    <a href="{{ route('admin.login') }}"
                        class="text-emerald-600 hover:text-emerald-800 font-medium inline-flex items-center gap-1 hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection