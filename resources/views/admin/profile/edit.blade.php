@extends('admin.layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
    <div class="container mx-auto px-4 py-8 -mt-12 md:-mt-14">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 pb-4 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-slate-800">Pengaturan Akun</h1>
                <p class="text-slate-500 mt-1">Kelola informasi profil, foto, dan password Anda.</p>
            </div>

            <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Foto Profil -->
                    <div class="md:col-span-1">
                        <h2 class="text-xl font-semibold text-slate-700 mb-4">Foto Profil</h2>
                        <p class="text-sm text-slate-500 mb-4">Unggah foto baru untuk personalisasi akun Anda. Ukuran
                            terbaik 1:1.</p>
                        <div class="flex flex-col items-center">
                            <label for="profile_photo" class="cursor-pointer group">
                                <div class="relative w-48 h-48 mb-4">
                                    @if(auth()->user()->profile)
                                        <img id="profileImage" src="{{ Storage::url(auth()->user()->profile) }}"
                                            alt="Profile Photo"
                                            class="w-full h-full rounded-full object-cover border-4 border-slate-300 transition-all duration-300 group-hover:border-indigo-500">
                                        <div id="profileAvatar"
                                            class="hidden w-full h-full rounded-full bg-slate-100 flex items-center justify-center border-4 border-slate-300">
                                            <span
                                                class="text-4xl text-slate-400">{{ strtoupper(auth()->user()->name[0] ?? 'A') }}</span>
                                        </div>
                                    @else
                                        <div id="profileAvatar"
                                            class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center border-4 border-slate-300">
                                            <span
                                                class="text-4xl text-slate-400">{{ strtoupper(auth()->user()->name[0] ?? 'A') }}</span>
                                        </div>
                                        <img id="profileImage"
                                            class="hidden w-full h-full rounded-full object-cover border-4 border-slate-300"
                                            alt="Profile Photo">
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="text-white text-sm font-medium">Ganti Foto</span>
                                    </div>
                                </div>
                            </label>
                            <input type="file" name="profile_photo" id="profile_photo" class="hidden">
                        </div>
                    </div>

                    <!-- Informasi Pribadi -->
                    <div class="md:col-span-2">
                        <h2 class="text-xl font-semibold text-slate-700 mb-4">Informasi Pribadi</h2>
                        <div class="space-y-6 mb-8">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                    required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                    required>
                            </div>
                        </div>

                        <!-- Ubah Password -->
                        <div class="pt-8 border-t border-gray-200">
                            <h2 class="text-xl font-semibold text-slate-700 mb-4">Ubah Password</h2>
                            <div class="space-y-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password
                                        Baru</label>
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                                </div>
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end mt-12 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Preview foto sebelum upload
            document.getElementById('profile_photo').addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = document.getElementById('profileImage');
                        const avatar = document.getElementById('profileAvatar');
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        avatar.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Submit AJAX
            document.getElementById('profileForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                    .then(async res => {
                        const data = await res.json();

                        if (res.ok && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            });

                            // Update navbar tanpa reload
                            document.querySelectorAll('.navbar-username').forEach(el => el.textContent = data.data.name);
                            document.querySelectorAll('.navbar-email').forEach(el => el.textContent = data.data.email);

                            if (data.data.profile_url) {
                                document.querySelectorAll('.navbar-photo').forEach(img => {
                                    img.src = data.data.profile_url;
                                    img.classList.remove('hidden');
                                });
                                document.querySelectorAll('.navbar-avatar').forEach(el => el.classList.add('hidden'));
                            }
                        } else {
                            let errors = '';
                            if (data.errors) {
                                Object.values(data.errors).forEach(err => errors += `<li>${err}</li>`);
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: `<ul style="text-align:left">${errors || data.message}</ul>`,
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan server.',
                            confirmButtonColor: '#ef4444'
                        });
                    });
            });
        </script>
    @endpush
@endsection