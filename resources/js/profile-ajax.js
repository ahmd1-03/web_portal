// Profile AJAX Form Handler with SweetAlert
import { showLoading, showSuccess, showError } from './sweetalert-config.js';

class ProfileAjaxHandler {
    constructor() {
        this.form = document.querySelector('form[action*="admin.profile.update"]');
        this.init();
    }

    init() {
        if (!this.form) return;

        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.setupFilePreview();
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this.form);
        formData.append('_method', 'PUT');
        
        try {
            showLoading('Memperbarui Profil...', 'Mohon tunggu sebentar');
            
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                showSuccess('Berhasil!', data.message);
                
                // Update UI secara real-time
                this.updateUI(data.data);
                
                // Update foto profil jika ada
                if (data.data.profile_url) {
                    this.updateProfilePhoto(data.data.profile_url);
                }
                
                // Clear password fields
                this.clearPasswordFields();
                
            } else {
                // Handle validation errors
                if (data.errors) {
                    this.displayValidationErrors(data.errors);
                } else {
                    showError('Gagal', data.message || 'Terjadi kesalahan');
                }
            }
            
        } catch (error) {
            console.error('Error:', error);
            showError('Error', 'Terjadi kesalahan pada server');
        }
    }

    updateUI(data) {
        // Update nama dan email di UI
        const nameElement = document.querySelector('#navbar-name');
        if (nameElement) {
            nameElement.textContent = data.name;
        }
        
        const sidebarName = document.querySelector('#sidebar-name');
        if (sidebarName) {
            sidebarName.textContent = data.name;
        }
    }

    updateProfilePhoto(profileUrl) {
        const profileImg = document.querySelector('img[alt="Profile Photo"]');
        if (profileImg) {
            profileImg.src = profileUrl;
        }
    }

    clearPasswordFields() {
        const passwordFields = this.form.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            field.value = '';
        });
    }

    clearValidationErrors() {
        const errorMessages = this.form.querySelectorAll('.text-red-500');
        errorMessages.forEach(error => error.remove());
        
        const inputs = this.form.querySelectorAll('input');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
        });
    }

    setupFilePreview() {
        const fileInput = document.getElementById('profile_photo');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.querySelector('img[alt="Profile Photo"]');
                        if (img) {
                            img.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ProfileAjaxHandler();
});
