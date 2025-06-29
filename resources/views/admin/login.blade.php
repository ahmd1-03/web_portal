<!DOCTYPE html>
<html lang="id">

<head>
    <!-- Mengatur karakter encoding dan responsivitas viewport -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul halaman -->
    <title>Login Admin - Portal Karawang</title>
    <!-- CSRF token untuk keamanan form Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mengimpor Tailwind CSS untuk styling -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Mengimpor font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Mengimpor SweetAlert2 untuk notifikasi popup -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mengimpor Animate.css untuk animasi SweetAlert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* Mengatur font default untuk seluruh halaman */
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Styling untuk input yang memiliki error */
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        /* Animasi fadeIn untuk pesan error */
        .error-message {
            animation: fadeIn 0.3s ease-out;
        }

        /* Keyframes untuk animasi fadeIn */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styling untuk spinner loading */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        /* Keyframes untuk animasi spinner */
        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Transisi untuk notifikasi sukses */
        .success-notification {
            transition: opacity 0.5s ease-out;
        }

        /* Styling untuk progress bar di bawah popup SweetAlert */
        .progress-bar {
            height: 3px;
            background-color: #10b981;
            width: 0;
            transition: width 2s ease-in-out;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">

        <!-- Bagian kiri (dekoratif) -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-emerald-600 to-emerald-800 text-white p-8 relative">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-10 right-10 w-32 h-32 bg-white/10 rounded-full"></div>
            </div>

            <div class="relative z-10 h-full flex flex-col items-center justify-center text-center">
                <img src="{{ asset('images/logoKrw.png') }}" alt="Logo" class="h-24 mb-6">
                <h2 class="text-2xl font-bold">Portal Karawang</h2>
                <p class="mt-2 text-emerald-100">Sistem Administrasi Resmi</p>
            </div>

            <!-- Animated wave at the bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-32 z-0">
                <svg class="w-full h-full" viewBox="0 0 1440 120" preserveAspectRatio="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="waveGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#059669" />
                            <stop offset="100%" stop-color="#047857" />
                        </linearGradient>
                    </defs>
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

        <!-- Bagian kanan (form login) -->
        <div class="w-full md:w-1/2 p-8">
            <!-- Judul form login -->
            <h1 class="text-2xl font-bold text-emerald-800 text-center mb-6">Login Admin</h1>

            <!-- Notifikasi sukses jika ada -->
            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 flex items-center success-notification">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form login dengan method POST -->
            <form id="loginForm" method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf

                <!-- Input untuk email/username -->
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                        Email atau Nama Pengguna
                    </label>
                    <div class="relative">
                        <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('login') input-error @enderror"
                            placeholder="user@example.com atau username">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- Pesan error untuk input login -->
                    @error('login')
                        <div class="text-red-600 text-sm mt-1 error-message flex items-start">
                            <svg class="h-4 w-4 mt-0.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Input untuk password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('password') input-error @enderror"
                            placeholder="Masukkan password">
                        <!-- Tombol untuk toggle visibilitas password -->
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3"
                            onclick="togglePassword()">
                            <svg id="eyeIcon" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg id="eyeSlashIcon" class="h-5 w-5 text-gray-400 hidden" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <!-- Pesan error untuk input password -->
                    @error('password')
                        <div class="text-red-600 text-sm mt-1 error-message flex items-start">
                            <svg class="h-4 w-4 mt-0.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Opsi remember dan lupa password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-700">Ingat saya</label>
                    </div>
                    <a href="{{ route('admin.lupaPassword') }}"
                        class="text-sm text-emerald-600 hover:text-emerald-800">Lupa
                        password?</a>
                </div>

                <!-- Tombol submit -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 px-4 rounded-lg font-medium transition duration-200 flex justify-center items-center">
                    <span id="btnText">Masuk</span>
                    <svg id="loadingIcon" class="hidden loading-spinner -ml-1 mr-3 h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </form>

            <!-- Link registrasi -->
            <div class="mt-4 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('admin.register') }}"
                    class="font-medium text-emerald-600 hover:text-emerald-800">Daftar
                    disini</a>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk toggle visibilitas password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            // Mengubah tipe input dari password ke text atau sebaliknya
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        // Handle form submission dengan AJAX
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Mendapatkan elemen form dan tombol
            const form = e.target;
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const loadingIcon = document.getElementById('loadingIcon');

            // Mengatur tombol ke state loading
            submitBtn.disabled = true;
            btnText.textContent = 'Memverifikasi...';
            loadingIcon.classList.remove('hidden');

            try {
                // Mengirim request AJAX ke server
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                // Parsing response JSON
                const data = await response.json();

                // Jika login berhasil
                if (response.ok && data.success) {
                    // Menampilkan popup SweetAlert dengan progress bar di bawah
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message || 'Login berhasil',
                        icon: 'success',
                        confirmButtonColor: '#10b981',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        },
                        // Menambahkan progress bar di bawah popup
                        didOpen: () => {
                            const popup = Swal.getPopup();
                            const progressBar = document.createElement('div');
                            progressBar.className = 'progress-bar';
                            popup.appendChild(progressBar);
                            // Animasi progress bar dari 0% ke 100%
                            setTimeout(() => {
                                progressBar.style.width = '100%';
                            }, 100);
                            // Redirect ke dashboard setelah progress bar selesai (2 detik)
                            setTimeout(() => {
                                window.location.href = data.redirect || '/admin/dashboard';
                            }, 2000);
                        },
                        // Menonaktifkan tombol OK agar redirect otomatis
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });
                } else {
                    // Menangani error dari server dengan validasi inline
                    if (data.errors) {
                        // Hapus error sebelumnya
                        document.querySelectorAll('.error-message').forEach(el => el.remove());
                        document.querySelectorAll('.input-error').forEach(el => {
                            el.classList.remove('input-error');
                        });

                        // Menampilkan error baru
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('input-error');

                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-red-600 text-sm mt-1 error-message flex items-start';
                                errorDiv.innerHTML = `
                                    <svg class="h-4 w-4 mt-0.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>${messages[0]}</span>
                                `;

                                input.parentNode.parentNode.appendChild(errorDiv);
                            }
                        }
                    }
                }
            } catch (error) {
                // Menangani error sistem dengan validasi inline
                console.error('Error:', error);
                // Hapus error sebelumnya
                document.querySelectorAll('.error-message').forEach(el => el.remove());
                document.querySelectorAll('.input-error').forEach(el => {
                    el.classList.remove('input-error');
                });

                // Tambahkan pesan error umum
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-600 text-sm mt-1 error-message flex items-start';
                errorDiv.innerHTML = `
                    <svg class="h-4 w-4 mt-0.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Terjadi kesalahan pada sistem</span>
                `;
                form.appendChild(errorDiv);
            } finally {
                // Mengembalikan tombol ke state normal
                submitBtn.disabled = false;
                btnText.textContent = 'Masuk';
                loadingIcon.classList.add('hidden');
            }
        });

        // Auto-hide notifikasi sukses setelah 5 detik
        const successNotification = document.querySelector('.bg-green-100');
        if (successNotification) {
            setTimeout(() => {
                successNotification.style.opacity = '0';
                setTimeout(() => {
                    successNotification.remove();
                }, 500);
            }, 5000);
        }
    </script>
</body>

</html>