// Profile Manager - Handles AJAX form submission and UI updates
class ProfileManager {
    constructor() {
        this.form = document.getElementById('profileForm');
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

        try {
            // Show loading state
            this.setLoadingState(true);

            const response = await fetch(this.form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                this.handleSuccess(data);
            } else {
                this.handleError(data);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('error', 'Error', 'Terjadi kesalahan pada server');
        } finally {
            this.setLoadingState(false);
        }
    }

    handleSuccess(data) {
        this.showAlert('success', 'Berhasil', data.message);

        // Update form fields with new data
        this.updateFormFields(data.data);

        // Update profile photo in form
        if (data.data.profile_url) {
            this.updateProfilePhoto(data.data.profile_url);
        }

        // Update navbar
        this.updateNavbar(data.data);

        // Clear password fields
        this.clearPasswordFields();
    }

    handleError(data) {
        let errors = '';
        if (data.errors) {
            Object.values(data.errors).forEach(err => errors += `<li>${err}</li>`);
        }
        this.showAlert('error', 'Gagal', `<ul style="text-align:left">${errors || data.message}</ul>`);
    }

    updateFormFields(data) {
        // Update name field
        const nameField = document.getElementById('name');
        if (nameField) {
            nameField.value = data.name;
        }

        // Update email field
        const emailField = document.getElementById('email');
        if (emailField) {
            emailField.value = data.email;
        }
    }

    updateProfilePhoto(profileUrl) {
        const profileImage = document.getElementById('profileImage');
        const profileAvatar = document.getElementById('profileAvatar');

        if (profileImage && profileAvatar) {
            profileImage.src = profileUrl;
            profileImage.classList.remove('hidden');
            profileAvatar.classList.add('hidden');
        }
    }

    updateNavbar(data) {
        // Update navbar username
        document.querySelectorAll('.navbar-username').forEach(el => {
            el.textContent = data.name;
        });

        // Update navbar email
        document.querySelectorAll('.navbar-email').forEach(el => {
            el.textContent = data.email;
        });

        // Update navbar photo
        if (data.profile_url) {
            document.querySelectorAll('.navbar-photo').forEach(img => {
                img.src = data.profile_url;
                img.classList.remove('hidden');
            });
            document.querySelectorAll('.navbar-avatar').forEach(el => {
                el.classList.add('hidden');
            });
        }
    }

    clearPasswordFields() {
        const passwordFields = this.form.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            field.value = '';
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
                        const img = document.getElementById('profileImage');
                        const avatar = document.getElementById('profileAvatar');
                        if (img && avatar) {
                            img.src = e.target.result;
                            img.classList.remove('hidden');
                            avatar.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    setLoadingState(loading) {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = loading;
            submitBtn.textContent = loading ? 'Menyimpan...' : 'Simpan Perubahan';
        }
    }

    showAlert(icon, title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: icon,
                title: title,
                html: message,
                confirmButtonColor: icon === 'success' ? '#4f46e5' : '#ef4444'
            });
        } else {
            alert(`${title}: ${message}`);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ProfileManager();
});
