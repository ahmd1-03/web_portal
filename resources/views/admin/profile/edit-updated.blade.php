@extends('admin.layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
    <div class="container mx-auto px-4 py-8 -mt-12 md:-mt-14">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 pb-4 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-slate-800">Pengaturan Akun</h1>
                <p class="text-slate-500 mt-1">Kelola informasi profil, foto, dan password Anda.</p>
            </div>

            <div x-data="profileManager()" x-init="init()" class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="md:col-span-1">
                    <h2 class="text-xl font-semibold text-slate-700 mb-4">Foto Profil</h2>
                    <p class="text-sm text-slate-500 mb-4">Unggah foto baru untuk personalisasi akun Anda. Ukuran terbaik 1:1.</p>
                    <div class="flex flex-col items-center">
                        <label for="profile_photo" class="cursor-pointer group">
                            <div class="relative w-48 h-48 mb-4">
                                <img :src="previewPhoto || (userData.profile ? '/storage/' + userData.profile : '/images/default-avatar.png')" 
                                     alt="Profile Photo"
                                     class="w-full h-full rounded-full object-cover border-4 border-slate-300 transition-all duration-300 group-hover:border-indigo-500">
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <span class="text-white text-sm font-medium">Ganti Foto</span>
                                </div>
                            </div>
                        </label>
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" @change="handleFileChange">
                        <span x-show="errors.profile_photo" class="text-red-500 text-xs mt-2 text-center" x-text="errors.profile_photo"></span>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <h2 class="text-xl font-semibold text-slate-700 mb-4">Informasi Pribadi</h2>
                    <form @submit.prevent="handleSubmit" class="space-y-6 mb-8">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" x-model="userData.name"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200"
                                required>
                            <span x-show="errors.name" class="text-red-500 text-xs mt-1" x-text="errors.name"></span>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" x-model="userData.email"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200"
                                required>
                            <span x-show="errors.email" class="text-red-500 text-xs mt-1" x-text="errors.email"></span>
                        </div>
                    </form>

                    <div class="pt-8 border-t border-gray-200">
                        <h2 class="text-xl font-semibold text-slate-700 mb-4">Ubah Password</h2>
                        <div class="space-y-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                                <input type="password" name="password" id="password" x-model="userData.password"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200">
                                <span x-show="errors.password" class="text-red-500 text-xs mt-1" x-text="errors.password"></span>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" x-model="userData.password_confirmation"
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors duration-200">
                                <span x-show="errors.password_confirmation" class="text-red-500 text-xs mt-1" x-text="errors.password_confirmation"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-12 pt-6 border-t border-gray-200">
                <button type="button" @click="handleSubmit" :disabled="loading"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50">
                    <span x-show="loading" class="mr-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('profileManager', () => ({
                    form: null,
                    errors: {},
                    loading: false,
                    userData: {
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                        profile_photo: null
                    },
                    previewPhoto: null,

                    init() {
                        this.form = document.querySelector('form[action*="admin.profile.update"]');
                        this.loadUserData();
                        this.setupEventListeners();
                    },

                    loadUserData() {
                        const nameInput = document.querySelector('input[name="name"]');
                        const emailInput = document.querySelector('input[name="email"]');
                        
                        if (nameInput) this.userData.name = nameInput.value;
                        if (emailInput) this.userData.email = emailInput.value;
                    },

                    setupEventListeners() {
                        const fileInput = document.getElementById('profile_photo');
                        if (fileInput) {
                            fileInput.addEventListener('change', (e) => this.handleFileChange(e));
                        }
                    },

                    handleFileChange(e) {
                        const file = e.target.files[0];
                        if (file) {
                            if (file.size > 2 * 1024 * 1024) {
                                this.errors.profile_photo = 'Ukuran file maksimum 2MB';
                                e.target.value = '';
                                return;
                            }
                            
                            if (!file.type.startsWith('image/')) {
                                this.errors.profile_photo = 'File harus berupa gambar';
                                e.target.value = '';
                                return;
                            }
                            
                            this.userData.profile_photo = file;
                            
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previewPhoto = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    },

                    validateForm() {
                        this.errors = {};

                        if (!this.userData.name || this.userData.name.trim() === '') {
                            this.errors.name = 'Nama lengkap wajib diisi';
                        } else if (this.userData.name.length < 3) {
                            this.errors.name = 'Nama minimal 3 karakter';
                        }

                        if (!this.userData.email || this.userData.email.trim() === '') {
                            this.errors.email = 'Email wajib diisi';
                        } else if (!this.isValidEmail(this.userData.email)) {
                            this.errors.email = 'Format email tidak valid';
                        }

                        if (this.userData.password && this.userData.password.length < 8) {
                            this.errors.password = 'Password minimal 8 karakter';
                        }

                        if (this.userData.password && this.userData.password !== this.userData.password_confirmation) {
                            this.errors.password_confirmation = 'Konfirmasi password tidak cocok';
                        }

                        return Object.keys(this.errors).length === 0;
                    },

                    isValidEmail(email) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        return emailRegex.test(email);
                    },

                    async handleSubmit() {
                        if (!this.validateForm()) {
                            this.displayValidationErrors();
                            return;
                        }

                        const confirmed = await Swal.fire({
                            title: 'Konfirmasi Perubahan',
                            text: 'Apakah Anda yakin ingin menyimpan perubahan pada profil?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, lanjutkan',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33'
                        });

                        if (!confirmed.isConfirmed) {
                            return;
                        }

                        this.loading = true;
                        Swal.fire({
                            title: 'Memperbarui Profil...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        try {
                            const formData = new FormData();
                            formData.append('name', this.userData.name);
                            formData.append('email', this.userData.email);
                            
                            if (this.userData.password) {
                                formData.append('password', this.userData.password);
                                formData.append('password_confirmation', this.userData.password_confirmation);
                            }
                            
                            if (this.userData.profile_photo) {
                                formData.append('profile_photo', this.userData.profile_photo);
                            }
                            
                            formData.append('_method', 'PUT');
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                            const response = await fetch('{{ route('admin.profile.update') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Profil berhasil diperbarui',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                
                                // Update UI elements
                                this.updateUI(data.data);
                                this.clearPasswordFields();
                                this.clearFileInput();
                            } else {
                                if (data.errors) {
                                    this.errors = data.errors;
                                    this.displayValidationErrors();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message || 'Terjadi kesalahan'
                                    });
                                }
                            }
                            
                        } catch (error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server'
                            });
                        } finally {
                            this.loading = false;
                        }
                    },

                    displayValidationErrors() {
                        const existingErrors = document.querySelectorAll('.validation-error');
                        existingErrors.forEach(error => error.remove());

                        Object.keys(this.errors).forEach(field => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                const errorDiv = document.createElement('p');
                                errorDiv.className = 'validation-error text-red-500 text-xs mt-1';
                                errorDiv.textContent = this.errors[field];
                                input.parentNode.appendChild(errorDiv);
                                input.classList.add('border-red-500', 'focus:ring-red-500');
                            }
                        });
                    },

                    updateUI(data) {
                        const navbarName = document.querySelector('#navbar-name');
                        if (navbarName && data.name) {
                            navbarName.textContent = data.name;
                        }

                        const sidebarName = document.querySelector('#sidebar-name');
                        if (sidebarName && data.name) {
                            sidebarName.textContent = data.name;
                        }

                        if (data.profile_url) {
                            const profileImg = document.querySelector('img[alt="Profile Photo"]');
