<!-- Meng-extend layout auth dasar untuk admin -->
@extends('admin.layouts.auth')

<!-- Menetapkan judul halaman -->
@section('title', 'Password Baru')

<!-- Memulai section content utama -->
@section('content')
    <!-- Container utama dengan flex layout -->
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-6">
        <!-- Container form dengan border dan shadow -->
        <div class="w-full max-w-6xl overflow-hidden border border-gray-200 rounded-xl shadow-lg flex flex-col md:flex-row">

            <!-- Kolom Kiri - Bagian dekoratif -->
            <div
                class="w-full md:w-1/2 text-white flex flex-col items-center px-10 pt-8 md:pt-14 pb-6 relative overflow-hidden">
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

                    <!-- Tempat untuk ilustrasi (saat ini dikomentari) -->
                    <div class="mt-6 md:mt-8 w-full hidden md:flex justify-center">
                        {{-- <img src="{{ url('images/ilustrasi.png') }}" alt="Ilustrasi"
                            class="w-full max-w-xs lg:max-w-sm animate-float" onerror="this.style.display='none'"> --}}
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
                        {{-- <path fill="url(#waveGradient)" fill-opacity="1"
                            d="M0,96L48,85.3C96,75,192,53,288,48C384,43,480,53,576,64C672,75,768,85,864,80C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,120L0,120Z">
                            <animate attributeName="d"
                                values="M0,96L48,85.3C96,75,192,53,288,48C384,43,480,53,576,64C672,75,768,85,864,80C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,120L0,120Z;
                                            M0,64L48,74.7C96,85,192,107,288,112C384,117,480,107,576,96C672,85,768,75,864,69.3C960,64,1056,64,1152,69.3C1248,75,1344,85,1392,90.7L1440,96L1440,120L0,120Z;
                                            M0,32L48,42.7C96,53,192,75,288,80C384,85,480,75,576,64C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L0,120Z;
                                            M0,96L48,85.3C96,75,192,53,288,48C384,43,480,53,576,64C672,75,768,85,864,80C960,75,1056,53,1152,48C1248,43,1344,53,1392,58.7L1440,64L1440,120L0,120Z"
                                dur="15s" repeatCount="indefinite" />
                        </path> --}}
                    </svg>
                </div>
            </div>

            <!-- Kolom Kanan - Form password baru -->
            <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-10 bg-white space-y-6">
                <!-- Judul form password baru -->
                <h1 class="text-2xl sm:text-3xl font-bold text-emerald-800 text-center">Password Baru</h1>

                <!-- Menampilkan status/notifikasi jika ada -->
                @if (session('status'))
                    <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Menampilkan error validasi jika ada -->
                @if ($errors->any())
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg shadow-sm">
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Informasi email -->
                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Masukkan password baru untuk akun:
                    </p>
                    <p class="font-semibold text-emerald-600">{{ $email }}</p>
                </div>

                <!-- Form password baru -->
                <form id="newPasswordForm" method="POST" action="{{ route('admin.updatePassword') }}" class="space-y-4">
                    @csrf <!-- CSRF token untuk keamanan -->

                    <!-- Input email (hidden) -->
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Input password baru -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" />
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <!-- Ikon mata tertutup -->
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    <!-- Input konfirmasi password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" />
                            <button type="button" id="toggleConfirmPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <!-- Ikon mata tertutup -->
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Ulangi password yang sama</p>
                    </div>

                    <!-- Tombol update password -->
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                        Update Password
                    </button>

                    <!-- Link kembali ke halaman login -->
                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('admin.login') }}" class="text-emerald-600 hover:text-emerald-800 font-medium">
                            Kembali ke halaman login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Script yang dijalankan setelah DOM selesai dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
            const form = document.getElementById('newPasswordForm');

            // Auto-focus pada input password
            passwordInput.focus();

            // Toggle password visibility untuk password baru
            togglePasswordBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Update ikon
                const icon = this.querySelector('svg');
                if (type === 'password') {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
                }
            });

            // Toggle password visibility untuk konfirmasi password
            toggleConfirmPasswordBtn.addEventListener('click', function () {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);

                // Update ikon
                const icon = this.querySelector('svg');
                if (type === 'password') {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
                }
            });

            // Validasi real-time untuk konfirmasi password
            confirmPasswordInput.addEventListener('input', function () {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Handle form submission dengan AJAX dan SweetAlert
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah submit form default

                const formData = new FormData(form);

                // Kirim data form dengan fetch API
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tampilkan notifikasi sukses
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Password berhasil diubah',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#059669',
                                customClass: {
                                    popup: 'custom-popup'
                                }
                            }).then(() => {
                                // Redirect ke halaman login
                                window.location.href = data.redirect;
                            });
                        } else {
                            // Handle error response
                            let errorMessage = data.message || 'Terjadi kesalahan saat mengubah password';

                            if (data.errors) {
                                errorMessage = Object.values(data.errors).join('<br>');
                            }

                            // Tampilkan notifikasi error
                            Swal.fire({
                                title: 'Gagal!',
                                html: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc2626',
                                customClass: {
                                    popup: 'custom-popup'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        // Handle error jaringan atau server
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada sistem',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc2626',
                            customClass: {
                                popup: 'custom-popup'
                            }
                        });
                    });
            });
        });
    </script>
@endsection
