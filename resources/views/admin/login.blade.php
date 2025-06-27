<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Admin - Website Portal Karawang</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-700 min-h-screen flex items-center justify-center">

    <!-- Container Login -->
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm">

        <!-- Logo dan Judul -->
        <div class="flex flex-col items-center mb-6">
            <!-- Logo -->
            <img src="{{ asset('images/logoKrw.png') }}" alt="Logo Karawang" class="h-16 mb-4">
            <!-- Judul Login -->
            <h1 class="text-2xl font-bold text-emerald-800">Login Admin</h1>
            <p class="text-sm text-emerald-600">Portal Resmi Informasi Karawang</p>
        </div>

        <!-- Tampilkan Pesan Error Jika Ada -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md mb-4 text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ url('/admin/login') }}" aria-label="Form login admin">
            @csrf

            <!-- Input Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-emerald-900">Email</label>
                <input id="email" type="email" name="email" required autofocus
                    class="mt-1 w-full px-4 py-2 border border-emerald-500 rounded-md shadow-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none text-emerald-800" />
            </div>

            <!-- Input Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-emerald-900">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 w-full px-4 py-2 border border-emerald-500 rounded-md shadow-sm focus:ring-2 focus:ring-emerald-600 focus:outline-none text-emerald-800" />
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full bg-emerald-800 text-white font-semibold py-2 rounded-md hover:bg-emerald-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>
