/**
 * Profile Management with Alpine.js and SweetAlert
 * Provides consistent validation with card and user management
 */
import { showLoading, showSuccess, showError, showConfirm } from './sweetalert-config.js';

export function profileManager() {
    return {
        // Form state
        form: null,
        errors: {},
        loading: false,
        
        // User data
        userData: {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            profile_photo: null
        },
        
        // UI state
        previewPhoto: null,
        showPassword: false,
        showConfirmPassword: false,
        
        /**
         * Initialize the profile manager
         */
        init() {
            this.form = document.querySelector('form[action*="admin.profile.update"]');
            this.loadUserData();
            this.setupEventListeners();
        },
        
        /**
         * Load existing user data
         */
        loadUserData() {
            const nameInput = document.querySelector('input[name="name"]');
            const emailInput = document.querySelector('input[name="email"]');
            
            if (nameInput) this.userData.name = nameInput.value;
            if (emailInput) this.userData.email = emailInput.value;
        },
        
        /**
         * Setup event listeners
         */
        setupEventListeners() {
            // File input change
            const fileInput = document.getElementById('profile_photo');
            if (fileInput) {
                fileInput.addEventListener('change', (e) => this.handleFileChange(e));
            }
            
            // Form submission
            if (this.form) {
                this.form.addEventListener('submit', (e) => this.handleSubmit(e));
            }
        },
        
        /**
         * Handle file input change
         */
        handleFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    showError('Error', 'Ukuran file maksimum 2MB');
                    e.target.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showError('Error', 'File harus berupa gambar');
                    e.target.value = '';
                    return;
                }
                
                this.userData.profile_photo = file;
                
                // Preview image
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewPhoto = e.target.result;
                    const img = document.querySelector('img[alt="Profile Photo"]');
                    if (img) {
                        img.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        },
        
        /**
         * Validate form data
         */
        validateForm() {
            this.errors = {};
            
            // Name validation
            if (!this.userData.name || this.userData.name.trim() === '') {
                this.errors.name = ['Nama lengkap wajib diisi'];
            } else if (this.userData.name.length < 3) {
                this.errors.name = ['Nama minimal 3 karakter'];
            }
            
            // Email validation
            if (!this.userData.email || this.userData.email.trim() === '') {
                this.errors.email = ['Email wajib diisi'];
            } else if (!this.isValidEmail(this.userData.email)) {
                this.errors.email = ['Format email tidak valid'];
            }
            
            // Password validation (optional)
            if (this.userData.password) {
                if (this.userData.password.length < 8) {
                    this.errors.password = ['Password minimal 8 karakter'];
                }
                
                if (this.userData.password !== this.userData.password_confirmation) {
                    this.errors.password_confirmation = ['Konfirmasi password tidak cocok'];
                }
            }
            
            return Object.keys(this.errors).length === 0;
        },
        
        /**
         * Email validation helper
         */
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        /**
         * Handle form submission
         */
        async handleSubmit(e) {
            e.preventDefault();
            
            if (!this.validateForm()) {
                this.displayValidationErrors();
                return;
            }
            
            const confirmed = await showConfirm(
                'Konfirmasi Perubahan',
                'Apakah Anda yakin ingin menyimpan perubahan pada profil?'
            );
            
            if (!confirmed.isConfirmed) {
                return;
            }
            
            this.loading = true;
            showLoading('Memperbarui Profil...', 'Mohon tunggu sebentar');
            
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
                
                const response = await fetch(this.form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showSuccess('Berhasil!', data.message || 'Profil berhasil diperbarui');
                    
                    // Update UI elements
                    this.updateUI(data.data);
                    
                    // Clear password fields
                    this.clearPasswordFields();
                    
                    // Clear file input
                    this.clearFileInput();
                    
                } else {
                    if (data.errors) {
                        this.errors = data.errors;
                        this.displayValidationErrors();
                    } else {
                        showError('Gagal', data.message || 'Terjadi kesalahan');
                    }
                }
                
            } catch (error) {
                console.error('Error:', error);
                showError('Error', 'Terjadi kesalahan pada server');
            } finally {
                this.loading = false;
            }
        },
        
        /**
         * Display validation errors
         */
        displayValidationErrors() {
            // Clear existing error messages
            const existingErrors = document.querySelectorAll('.validation-error');
            existingErrors.forEach(error => error.remove());
            
            // Display new errors
            Object.keys(this.errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'validation-error text-red-500 text-xs mt-1';
                    errorDiv.textContent = this.errors[field][0];
                    input.parentNode.appendChild(errorDiv);
                    
                    // Add error styling
                    input.classList.add('border-red-500', 'focus:ring-red-500');
                }
            });
        },
        
        /**
         * Update UI elements after successful update
         */
        updateUI(data) {
            // Update navbar name
            const navbarName = document.querySelector('#navbar-name');
            if (navbarName && data.name) {
                navbarName.textContent = data.name;
            }
            
            // Update sidebar name
            const sidebarName = document.querySelector('#sidebar-name');
            if (sidebarName && data.name) {
                sidebarName.textContent = data.name;
            }
            
            // Update profile photo if changed
            if (data.profile_url) {
                const profileImg = document.querySelector('img[alt="Profile Photo"]');
                if (profileImg) {
                    profileImg.src = data.profile_url;
                }
                
                // Update all profile photos in the UI
                const allProfileImgs = document.querySelectorAll('.profile-photo');
                allProfileImgs.forEach(img => {
                    img.src = data.profile_url;
                });
            }
        },
        
        /**
         * Clear password fields
         */
        clearPasswordFields() {
            this.userData.password = '';
            this.userData.password_confirmation = '';
            
            const passwordFields = document.querySelectorAll('input[type="password"]');
            passwordFields.forEach(field => {
                field.value = '';
            });
        },
        
        /**
         * Clear file input
         */
        clearFileInput() {
            const fileInput = document.getElementById('profile_photo');
            if (fileInput) {
                fileInput.value = '';
            }
            this.userData.profile_photo = null;
        }
    };
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const profileContainer = document.querySelector('[x-data*="profileManager"]');
    if (profileContainer) {
        Alpine.data('profileManager', profileManager);
    }
});
