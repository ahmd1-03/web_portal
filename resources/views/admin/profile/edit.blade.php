<!-- ===================== BAGIAN LAYOUT UTAMA ===================== -->
<!-- Extends layout utama admin -->
@extends('admin.layouts.app')

<!-- ===================== BAGIAN JUDUL HALAMAN ===================== -->
<!-- Section untuk judul halaman -->
@section('title', 'Pengaturan Profil')

<!-- ===================== BAGIAN KONTEN UTAMA ===================== -->
<!-- Section untuk konten utama halaman -->
@section('content')
    <!-- Container utama untuk halaman pengaturan profil -->
    <div class="container mx-auto px-4 py-8 -mt-12 md:-mt-14">
        <div class="max-w-4xl mx-auto">

            <!-- ===================== HEADER HALAMAN ===================== -->
            <!-- Header dengan judul dan deskripsi halaman -->
            <div class="mb-8 pb-4 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-slate-800">Pengaturan Akun</h1>
                <p class="text-slate-500 mt-1">Kelola informasi profil, foto, dan password Anda.</p>
            </div>

            <!-- ===================== FORM PENGATURAN PROFIL ===================== -->
            <!-- Form untuk mengupdate data profil admin -->
            <form id="profileForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Grid layout untuk form -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                    <!-- ===================== BAGIAN FOTO PROFIL ===================== -->
                    <!-- Kolom untuk upload dan preview foto profil -->
                    <div class="md:col-span-1">
                        <h2 class="text-xl font-semibold text-slate-700 mb-4">Foto Profil</h2>
                        <p class="text-sm text-slate-500 mb-4">Unggah foto baru untuk personalisasi akun Anda. Ukuran
                            terbaik 1:1.</p>

                        <!-- Container untuk preview foto profil -->
                        <div class="flex flex-col items-center">
                            <label for="profile_photo" class="cursor-pointer group">
                                <div class="relative w-48 h-48 mb-4">
                                    <!-- Kondisional tampilkan foto profil atau avatar default -->
                                    @if(auth()->user()->profile)
                                        <!-- Tampilkan gambar profil jika ada -->
                                        <img id="profileImage" src="{{ Storage::url(auth()->user()->profile) }}"
                                            alt="Foto Profil"
                                            class="w-full h-full rounded-full object-cover border-4 border-slate-300 transition-all duration-300 group-hover:border-indigo-500">
                                        <!-- Avatar default (tersembunyi) -->
                                        <div id="profileAvatar"
                                            class="hidden w-full h-full rounded-full bg-slate-100 flex items-center justify-center border-4 border-slate-300">
                                            <span
                                                class="text-4xl text-slate-400">{{ strtoupper(auth()->user()->name[0] ?? 'A') }}</span>
                                        </div>
                                    @else
                                        <!-- Tampilkan avatar default jika tidak ada foto profil -->
                                        <div id="profileAvatar"
                                            class="w-full h-full rounded-full bg-slate-100 flex items-center justify-center border-4 border-slate-300">
                                            <span
                                                class="text-4xl text-slate-400">{{ strtoupper(auth()->user()->name[0] ?? 'A') }}</span>
                                        </div>
                                        <!-- Gambar profil (tersembunyi) -->
                                        <img id="profileImage"
                                            class="hidden w-full h-full rounded-full object-cover border-4 border-slate-300"
                                            alt="Foto Profil">
                                    @endif

                                    <!-- Overlay untuk hover effect -->
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="text-white text-sm font-medium">Ganti Foto</span>
                                    </div>
                                </div>
                            </label>
                            <!-- Input file untuk upload foto (tersembunyi) -->
                            <input type="file" name="profile_photo" id="profile_photo" class="hidden">
                        </div>
                    </div>

                    <!-- ===================== BAGIAN INFORMASI PRIBADI ===================== -->
                    <!-- Kolom untuk informasi pribadi dan perubahan password -->
                    <div class="md:col-span-2">
                        <h2 class="text-xl font-semibold text-slate-700 mb-4">Informasi Pribadi</h2>

                        <!-- Form field untuk informasi pribadi -->
                        <div class="space-y-6 mb-8">
                            <!-- Field input untuk nama lengkap -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                    required>
                            </div>

                            <!-- Field input untuk email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                    required>
                            </div>
                        </div>

                        <!-- ===================== BAGIAN UBAH PASSWORD ===================== -->
                        <!-- Section untuk perubahan password -->
                        <div class="pt-8 border-t border-gray-200">
                            <h2 class="text-xl font-semibold text-slate-700 mb-4">Ubah Password</h2>

                            <!-- Form field untuk perubahan password -->
                            <div class="space-y-6">
                                <!-- Field input untuk password baru -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password
                                        Baru</label>
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                                </div>

                                <!-- Field input untuk konfirmasi password -->
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

                <!-- ===================== BAGIAN TOMBOL SIMPAN ===================== -->
                <!-- Container untuk tombol submit form dengan loading state -->
                <div class="flex justify-end mt-12 pt-6 border-t border-gray-200">
                <button type="submit" id="saveProfileBtn"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 flex justify-center items-center">
                    <!-- Ikon save FontAwesome -->
                    <i class="fas fa-save mr-2"></i>
                    <!-- Teks tombol yang akan berubah saat loading -->
                    <span id="saveProfileBtnText">Simpan Perubahan</span>
                    <!-- SVG Spinner loading - tersembunyi secara default -->
                    <svg id="saveProfileLoadingIcon" class="hidden loading-spinner -ml-1 mr-3 h-5 w-5 text-white transition-opacity duration-200"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <!-- Lingkaran luar dengan opacity rendah -->
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <!-- Path untuk animasi loading dengan efek melingkar -->
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== BAGIAN SCRIPTS ===================== -->
    <!-- ===================== BAGIAN STYLES ===================== -->
    <!-- Push styles ke stack styles untuk animasi tombol loading -->
    @push('styles')
        <style>
            /* Styling untuk tombol loading - memberikan efek visual saat proses submit */
            .btn-loading {
                background-color: #4f46e5 !important;
                transform: scale(0.98);
                transition: all 0.2s ease-in-out;
            }

            .btn-loading:hover {
                background-color: #4338ca !important;
                transform: scale(0.98);
            }

            /* Animasi pulse untuk tombol loading - memberikan efek berkedip halus */
            @keyframes pulse-loading {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.8;
                }
            }

            /* Styling untuk spinner loading - animasi putaran untuk indikator loading */
            .loading-spinner {
                animation: spin 1s linear infinite;
            }

            /* Keyframes untuk animasi spinner - rotasi 360 derajat */
            @keyframes spin {
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush

    <!-- Push scripts ke stack scripts -->
    @push('scripts')
        <!-- Library SweetAlert untuk notifikasi popup -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // ===================== PREVIEW FOTO PROFIL =====================
            // Event listener untuk preview foto sebelum upload - menampilkan gambar yang dipilih tanpa upload
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

            // ===================== SUBMIT FORM AJAX =====================
            // Event listener untuk submit form dengan AJAX - mengirim data tanpa reload halaman
            document.getElementById('profileForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                // Ambil elemen tombol dan ikon loading untuk state management
                const saveBtn = document.getElementById('saveProfileBtn');
                const saveBtnText = document.getElementById('saveProfileBtnText');
                const saveLoadingIcon = document.getElementById('saveProfileLoadingIcon');

                // Tampilkan loading state pada tombol dengan animasi smooth
                saveBtn.disabled = true;
                saveBtn.classList.add('btn-loading');
                saveBtn.style.animation = 'pulse-loading 1.5s ease-in-out infinite';
                saveBtnText.style.opacity = '0';
                setTimeout(() => {
                    saveBtnText.textContent = 'Menyimpan...';
                    saveBtnText.style.opacity = '1';
                    saveLoadingIcon.classList.remove('hidden');
                }, 150);

                // Kirim request AJAX ke server dengan CSRF token
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

                        // Handle response sukses - tampilkan notifikasi dan update UI
                        if (res.ok && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                confirmButtonColor: '#4f46e5'
                            });

                            // Update navbar tanpa reload halaman - real-time update
                            document.querySelectorAll('.navbar-username').forEach(el => el.textContent = data.data.name);
                            document.querySelectorAll('.navbar-email').forEach(el => el.textContent = data.data.email);

                            // Update foto profil di navbar jika ada perubahan
                            if (data.data.profile_url) {
                                document.querySelectorAll('.navbar-photo').forEach(img => {
                                    img.src = data.data.profile_url;
                                    img.classList.remove('hidden');
                                });
                                document.querySelectorAll('.navbar-avatar').forEach(el => el.classList.add('hidden'));
                            }
                        } else {
                            // Handle error response - tampilkan pesan error
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
                        // Handle network error - tampilkan pesan error koneksi
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan server.',
                            confirmButtonColor: '#ef4444'
                        });
                    })
                    .finally(() => {
                        // Mengembalikan tombol ke state normal dengan animasi smooth
                        setTimeout(() => {
                            // Sembunyikan spinner dan ubah teks dengan transisi
                            saveLoadingIcon.classList.add('hidden');
                            saveBtnText.style.opacity = '0';

                            setTimeout(() => {
                                saveBtnText.textContent = 'Simpan Perubahan';
                                saveBtnText.style.opacity = '1';
                                saveBtn.disabled = false;
                                saveBtn.classList.remove('btn-loading');
                                saveBtn.style.animation = '';
                            }, 150);
                        }, 300);
                    });
            });
        </script>
    @endpush
@endsection