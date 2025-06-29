@extends('admin.layouts.auth') {{-- Menggunakan template layout admin --}}

@section('title', 'Registrasi Admin') {{-- Judul halaman --}}

@section('content')
    <!-- Memastikan library SweetAlert dan Tailwind CSS terload -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Container utama untuk halaman registrasi -->
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-4">
        <!-- Card utama dengan layout responsive (row di desktop, column di mobile) -->
        <div class="w-full max-w-4xl overflow-hidden border border-gray-200 rounded-lg shadow-md flex flex-col md:flex-row">

            <!-- Kolom Kiri - Untuk tampilan visual/branding -->
            <div class="w-full md:w-1/2 text-white flex flex-col items-center px-6 pt-6 md:pt-10 pb-4 relative overflow-hidden">
                <!-- Background gradient dengan efek visual -->
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-emerald-800">
                    <!-- Lingkaran dekoratif dengan efek transparan -->
                    <div class="absolute -top-16 -left-16 w-48 h-48 bg-white/10 rounded-full mix-blend-overlay"></div>
                    <div class="absolute bottom-8 right-8 w-24 h-24 bg-white/10 rounded-full mix-blend-overlay"></div>
                    <div class="absolute top-1/4 right-1/4 w-32 h-32 bg-white/5 rounded-full mix-blend-overlay"></div>
                </div>

                <!-- Konten utama kolom kiri -->
                <div class="relative z-10 w-full flex flex-col items-center justify-center h-full py-3 md:py-0">
                    <div class="flex flex-col items-center text-center">
                        <!-- Logo Karawang -->
                        <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang"
                            class="h-16 md:h-20 lg:h-24 drop-shadow-md">

                        <!-- Teks branding -->
                        <div class="mt-3 md:mt-4 space-y-1">
                            <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Portal Karawang</h2>
                            <p class="text-xs md:text-sm lg:text-base leading-relaxed">
                                Sistem Informasi Admin Resmi<br>
                                untuk Pengelolaan Data Karawang
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Wave animasi di bagian bawah -->
                <div class="absolute bottom-0 left-0 right-0 h-24 z-0">
                    <svg class="w-full h-full" viewBox="0 0 1440 120" preserveAspectRatio="none">
                        <!-- Gradient untuk wave -->
                        <defs>
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

            <!-- Divider antara kolom kiri dan kanan (hanya tampil di desktop) -->
            <div class="w-full md:w-px md:h-auto hidden md:block bg-gray-200"></div>

            <!-- Kolom Kanan - Untuk form registrasi -->
            <div class="w-full md:w-1/2 p-4 sm:p-6 md:p-8 bg-white space-y-4">
                <!-- Judul form -->
                <h1 class="text-xl sm:text-2xl font-bold text-emerald-800 text-center">Registrasi Admin</h1>

                <!-- Form registrasi dengan method POST -->
                <form id="registrationForm" action="{{ route('admin.storeAdmin') }}" method="POST" class="space-y-3">
                    @csrf {{-- Token CSRF untuk keamanan --}}

                    <!-- Input Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition text-sm" />
                            <!-- Icon user di sebelah kanan input -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Tempat untuk menampilkan error validasi -->
                        <p class="text-red-500 text-xs mt-1 hidden" id="name-error"></p>
                    </div>

                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition text-sm" />
                            <!-- Icon email di sebelah kanan input -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Tempat untuk menampilkan error validasi -->
                        <p class="text-red-500 text-xs mt-1 hidden" id="email-error"></p>
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                        <!-- Checklist validasi password -->
                        <div id="password-checklist" class="text-xs mb-2 space-y-0.5">
                            <p id="min-length" class="flex items-center"><span class="mr-1">•</span> Minimal 8 karakter</p>
                            <p id="uppercase" class="flex items-center"><span class="mr-1">•</span> Huruf besar</p>
                            <p id="lowercase" class="flex items-center"><span class="mr-1">•</span> Huruf kecil</p>
                            <p id="number" class="flex items-center"><span class="mr-1">•</span> Angka</p>
                            <p id="special-char" class="flex items-center"><span class="mr-1">•</span> Simbol khusus (@#$%&,
                                dll)</p>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition text-sm pr-8" />
                            <!-- Tombol toggle visibility password -->
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-2 password-toggle"
                                data-target="password">
                                <!-- Icon mata (show password) -->
                                <svg class="h-4 w-4 text-gray-400 eye-icon" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <!-- Icon mata tertutup (hide password) - awalnya hidden -->
                                <svg class="h-4 w-4 text-gray-400 eye-slash-icon hidden" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <!-- Tempat untuk menampilkan error validasi -->
                        <p class="text-red-500 text-xs mt-1 hidden" id="password-error"></p>
                    </div>

                    <!-- Input Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">Konfirmasi
                            Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 shadow-sm transition text-sm pr-8" />
                            <!-- Tombol toggle visibility password -->
                            <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-2 password-toggle"
                                data-target="password_confirmation">
                                <svg class="h-4 w-4 text-gray-400 eye-icon" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="h-4 w-4 text-gray-400 eye-slash-icon hidden" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <!-- Tempat untuk menampilkan error validasi -->
                        <p class="text-red-500 text-xs mt-1 hidden" id="password_confirmation-error"></p>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" id="submitButton"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 rounded-lg transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center text-sm"
                        disabled>
                        <span id="submitText">Daftar Admin Baru</span>
                        <!-- Loading spinner (awalnya hidden) -->
                        <svg id="loadingSpinner" class="hidden animate-spin h-4 w-4 ml-2 text-white" viewBox="0 0 24 24"
                            fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </form>

                <!-- Link ke halaman login -->
                <p class="text-center text-xs text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('admin.login') }}" class="text-emerald-600 hover:text-emerald-800 font-medium">Masuk
                        di sini</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk validasi dan interaksi form -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Regex untuk validasi password
            const passwordRegex = {
                minLength: /.{8,}/, // Minimal 8 karakter
                uppercase: /[A-Z]/, // Harus ada huruf besar
                lowercase: /[a-z]/, // Harus ada huruf kecil
                number: /[0-9]/, // Harus ada angka
                specialChar: /[@#$%&]/ // Harus ada simbol khusus
            };

            // Elemen-elemen penting
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const submitButton = document.getElementById('submitButton');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            // Elemen checklist validasi password
            const checklist = {
                minLength: document.getElementById('min-length'),
                uppercase: document.getElementById('uppercase'),
                lowercase: document.getElementById('lowercase'),
                number: document.getElementById('number'),
                specialChar: document.getElementById('special-char')
            };

            // Fungsi untuk validasi password secara real-time
            function validatePassword() {
                const password = passwordInput.value;
                const isValid = {
                    minLength: passwordRegex.minLength.test(password),
                    uppercase: passwordRegex.uppercase.test(password),
                    lowercase: passwordRegex.lowercase.test(password),
                    number: passwordRegex.number.test(password),
                    specialChar: passwordRegex.specialChar.test(password)
                };

                // Update tampilan checklist
                for (const [key, element] of Object.entries(checklist)) {
                    element.classList.toggle('text-green-500', isValid[key]); // Hijau jika valid
                    element.classList.toggle('text-red-500', !isValid[key]); // Merah jika tidak valid
                }

                // Aktifkan tombol submit hanya jika semua kriteria terpenuhi
                const allValid = Object.values(isValid).every(v => v);
                submitButton.disabled = !allValid;
            }

            // Event listener untuk validasi password saat mengetik
            passwordInput.addEventListener('input', validatePassword);

            // Fungsi toggle visibility password
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function () {
                    const input = document.getElementById(this.dataset.target);
                    const eyeIcon = this.querySelector('.eye-icon');
                    const eyeSlashIcon = this.querySelector('.eye-slash-icon');
                    const isPassword = input.type === 'password';

                    // Toggle tipe input dan icon
                    input.type = isPassword ? 'text' : 'password';
                    eyeIcon.classList.toggle('hidden', !isPassword);
                    eyeSlashIcon.classList.toggle('hidden', isPassword);
                    // Tambahkan efek transisi
                    eyeIcon.classList.add('transition', 'duration-200');
                    eyeSlashIcon.classList.add('transition', 'duration-200');
                });
            });

            // Handle submit form dengan AJAX
            const form = document.getElementById('registrationForm');

            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah submit default

                // Bersihkan pesan error sebelumnya
                document.querySelectorAll('.text-red-500').forEach(el => {
                    if (el.id.includes('-error')) {
                        el.classList.add('hidden');
                        el.innerHTML = '';
                    }
                });

                // Tampilkan loading spinner
                submitText.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');
                submitButton.disabled = true;

                // Kirim data form
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest', // Header untuk identifikasi request AJAX
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // Token CSRF
                    }
                })
                    .then(response => {
                        // Handle error validasi (422)
                        if (response.status === 422) {
                            return response.json().then(errors => {
                                throw errors;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Sembunyikan loading spinner
                        submitText.classList.remove('hidden');
                        loadingSpinner.classList.add('hidden');
                        submitButton.disabled = false;

                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Registrasi admin berhasil',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#059669',
                            customClass: {
                                popup: 'max-w-sm',
                                title: 'text-base',
                                content: 'text-xs'
                            }
                        }).then(() => {
                            // Redirect ke halaman login setelah sukses
                            window.location.href = "{{ route('admin.login') }}";
                        });
                    })
                    .catch(error => {
                        // Sembunyikan loading spinner
                        submitText.classList.remove('hidden');
                        loadingSpinner.classList.add('hidden');
                        submitButton.disabled = false;

                        // Tampilkan error validasi
                        if (error.errors) {
                            for (const [field, messages] of Object.entries(error.errors)) {
                                const errorElement = document.getElementById(`${field}-error`);
                                if (errorElement) {
                                    errorElement.innerHTML = messages.join('<br>');
                                    errorElement.classList.remove('hidden');
                                }
                            }
                        } else {
                            // Tampilkan error umum
                            Swal.fire({
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat registrasi',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc2626',
                                customClass: {
                                    popup: 'max-w-sm',
                                    title: 'text-base',
                                    content: 'text-xs'
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection