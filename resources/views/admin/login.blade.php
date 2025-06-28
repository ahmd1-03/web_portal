<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Admin - Website Portal Karawang</title>

    <!-- Load Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Load font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />

    <style>
        /* Terapkan font Poppins untuk seluruh halaman */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-700 min-h-screen flex items-center justify-center">
    <!--
        Latar belakang menggunakan gradient hijau emerald,
        halaman menggunakan flexbox untuk men-center container login secara vertikal dan horizontal
    -->

    <!-- Container utama login -->
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm">
        <!--
            Background putih, padding 8,
            rounded sudut besar, shadow untuk efek kedalaman,
            maksimal lebar 24rem (sm),
            dan lebar penuh jika di layar kecil
        -->

        <!-- Logo dan judul login -->
        <div class="flex flex-col items-center mb-6">
            <!-- Logo Karawang -->
            <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-16 mb-4" />
            <!-- Judul halaman -->
            <h1 class="text-2xl font-bold text-emerald-800">Login Admin</h1>
            <!-- Deskripsi kecil -->
            <p class="text-sm text-emerald-600">Portal Resmi Informasi Karawang</p>
        </div>

        <!-- Pesan error jika ada kesalahan login -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md mb-4 text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Notifikasi sukses login -->
        @if (session('success'))
            <div id="successNotification" class="bg-green-100 text-green-700 px-4 py-2 rounded-md mb-4 text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form login -->
        <form method="POST" action="{{ url('/admin/login') }}" aria-label="Form login admin">
            @csrf <!-- Token keamanan CSRF -->

            <!-- Input untuk email atau username -->
            <div class="mb-4">
                <label for="login" class="block text-sm font-medium text-emerald-900">Email atau Nama Pengguna</label>
                <input id="login" type="text" name="login" required autofocus class="mt-1 w-full px-4 py-2 border border-emerald-500 rounded-md shadow-sm
                    focus:ring-2 focus:ring-emerald-600 focus:outline-none text-emerald-800" />
            </div>

            <!-- Input password dengan tombol toggle icon mata -->
            <div class="mb-6 relative">
                <label for="password" class="block text-sm font-medium text-emerald-900 mb-1">Password</label>
                <!--
                    Input password diberi padding kanan besar (pr-12)
                    supaya ada ruang untuk tombol toggle di dalam input sebelah kanan
                -->
                <input id="password" type="password" name="password" required class="w-full px-4 py-2 pr-12 border border-emerald-500 rounded-md shadow-sm
                    focus:ring-2 focus:ring-emerald-600 focus:outline-none text-emerald-800" />
                <!-- Tombol toggle untuk tampilkan/ sembunyikan password -->
                <button type="button" id="togglePassword" aria-label="Tampilkan sembunyikan password"
                    class="absolute inset-y-11 right-3 flex items-center text-emerald-600 hover:text-emerald-900 focus:outline-none">
                    <!-- Icon mata buka -->
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
                            9.542 7-1.274 4.057-5.065 7-9.542
                            7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Google reCAPTCHA widget (komentar, aktifkan jika perlu) -->
            <div class="mb-6">
                {{-- <div class="g-recaptcha" data-sitekey="6Lf2cXArAAAAADNOorD59PfTn2H5pssmgJFFwmEKM"></div> --}}
            </div>

            <!-- Checkbox Remember Me -->
            <div class="mb-6 flex items-center">
                <input id="remember" type="checkbox" name="remember"
                    class="mr-2 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded" />
                <label for="remember" class="text-sm text-emerald-900 select-none cursor-pointer">Ingat saya</label>
            </div>

            <!-- Tombol submit login -->
            <button type="submit" class="w-full bg-emerald-800 text-white font-semibold py-2 rounded-md
                hover:bg-emerald-700 transition duration-200 focus:outline-none focus:ring-2
                focus:ring-offset-2 focus:ring-emerald-600">
                Masuk
            </button>
        </form>
    </div>

    <!-- Script toggle show/hide password -->
    <script>
        // Ambil elemen tombol dan input password serta icon SVG
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        // Event klik toggle untuk mengganti tipe input password/text
        togglePassword.addEventListener('click', function () {
            // Cek tipe saat ini, jika 'password' ganti ke 'text', jika 'text' ganti ke 'password'
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Ganti icon sesuai tipe input (mata terbuka/tutup)
            if (type === 'password') {
                // Icon mata terbuka
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943
                        9.542 7-1.274 4.057-5.065 7-9.542
                        7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            } else {
                // Icon mata tertutup (dengan garis silang)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.944-9.543-7a9.97
                        9.97 0 012.563-4.306m1.557-1.557A9.956
                        9.956 0 0112 5c4.478 0 8.269 2.944 9.543
                        7a10.025 10.025 0 01-4.132 5.411m-2.31-1.766a3
                        3 0 10-4.243-4.243" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3l18 18" />
                `;
            }
        });
    </script>
</body>

</html>