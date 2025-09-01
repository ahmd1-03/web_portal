<!-- Meng-extend layout auth dasar untuk admin -->
@extends('admin.layouts.auth')

<!-- Menetapkan judul halaman -->
@section('title', 'Verifikasi Kode Reset Password')

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

            <!-- Kolom Kanan - Form verifikasi kode -->
            <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-10 bg-white space-y-6">
                <!-- Judul form verifikasi kode -->
                <h1 class="text-2xl sm:text-3xl font-bold text-emerald-800 text-center">Verifikasi Kode</h1>

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
                        Kode verifikasi telah dikirim ke email:
                    </p>
                    <p class="font-semibold text-emerald-600">{{ $email }}</p>
                </div>

                <!-- Form verifikasi kode -->
                <form id="verifyCodeForm" method="POST" action="{{ route('admin.verifyCode') }}" class="space-y-4">
                    @csrf <!-- CSRF token untuk keamanan -->

                    <!-- Input email (hidden) -->
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Input kode verifikasi -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Verifikasi</label>
                        <div class="relative">
                            <input id="code" type="text" name="code" maxlength="6" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm text-center text-2xl font-mono tracking-widest" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <!-- Ikon kode -->
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan 6 digit kode yang dikirim ke email Anda</p>
                    </div>

                    <!-- Tombol verifikasi -->
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                        Verifikasi Kode
                    </button>

                    <!-- Tombol kirim ulang kode -->
                    <button type="button" id="resendCodeBtn"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition duration-300 text-sm">
                        Kirim Ulang Kode
                    </button>

                    <!-- Link kembali ke halaman lupa password -->
                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('admin.lupaPassword') }}" class="text-emerald-600 hover:text-emerald-800 font-medium">
                            Kembali ke halaman lupa password
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Script yang dijalankan setelah DOM selesai dimuat
        document.addEventListener('DOMContentLoaded', function () {
            const codeInput = document.getElementById('code');
            const form = document.getElementById('verifyCodeForm');
            const resendBtn = document.getElementById('resendCodeBtn');

            // Auto-focus pada input kode
            codeInput.focus();

            // Handle input kode - hanya angka dan auto-format
            codeInput.addEventListener('input', function (e) {
                // Hanya izinkan angka
                this.value = this.value.replace(/[^0-9]/g, '');

                // Batasi panjang maksimal 6 karakter
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
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
                                text: data.message || 'Kode verifikasi berhasil',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#059669',
                                customClass: {
                                    popup: 'custom-popup'
                                }
                            }).then(() => {
                                // Redirect ke halaman new password
                                window.location.href = data.redirect;
                            });
                        } else {
                            // Handle error response
                            let errorMessage = data.message || 'Terjadi kesalahan saat verifikasi kode';

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

            // Handle resend code
            resendBtn.addEventListener('click', function () {
                const formData = new FormData();
                formData.append('email', '{{ $email }}');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                // Disable button sementara
                this.disabled = true;
                this.textContent = 'Mengirim...';

                fetch('{{ route("admin.resendCode") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Kode baru telah dikirim',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#059669',
                                customClass: {
                                    popup: 'custom-popup'
                                }
                            });
                        } else {
                            let errorMessage = data.message || 'Gagal mengirim kode baru';

                            if (data.errors) {
                                errorMessage = Object.values(data.errors).join('<br>');
                            }

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
                    })
                    .finally(() => {
                        // Re-enable button
                        this.disabled = false;
                        this.textContent = 'Kirim Ulang Kode';
                    });
            });
        });
    </script>
@endsection
