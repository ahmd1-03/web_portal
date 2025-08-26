// Profile AJAX Form Handler with SweetAlert - FIXED VERSION
import { showLoading, showSuccess, showError } from './sweetalert-config.js';

document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile AJAX script loaded');
    
    const form = document.getElementById('profile-form');
    if (!form) {
        console.error('Form dengan ID "profile-form" tidak ditemukan');
        return;
    }

    console.log('Form ditemukan:', form);

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Form submit triggered');
        
        const formData = new FormData(form);
        formData.append('_method', 'PUT');
        
        try {
            showLoading('Memperbarui Profil...', 'Mohon tunggu sebentar');
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            console.log('Response received:', response);
            const data = await response.json();
            console.log('Data received:', data);
            
            if (data.success) {
                showSuccess('Berhasil!', data.message);
                
                // Update UI secara real-time
                updateUI(data.data);
                
                // Update foto profil jika ada
                if (data.data.profile_url) {
                    updateProfilePhoto(data.data.profile_url);
                }
                
                // Clear password fields
                clearPasswordFields();
                
            } else {
                // Handle validation errors
                if (data.errors) {
                    displayValidationErrors(data.errors);
                } else {
                    showError('Gagal', data.message || 'Terjadi kesalahan');
                }
            }
            
        } catch (error) {
            console.error('Error:', error);
            showError('Error', 'Terjadi kesalahan pada server');
        }
    });

    // Setup file preview
    const fileInput = document.getElementById('profile_photo');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.querySelector('img[alt="Profile Photo"]');
                    if (img) {
                        img.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function updateUI(data) {
        console.log('Updating UI with:', data);
        
        // Update nama di navbar jika ada
        const navbarName = document.querySelector('#navbar-name');
        if (navbarName) {
            navbarName.textContent = data.name;
        }
        
        // Update nama di sidebar jika ada
        const sidebarName = document.querySelector('#sidebar-name');
        if (sidebarName) {
            sidebarName.textContent = data.name;
        }
    }

    function updateProfilePhoto(profileUrl) {
        console.log('Updating profile photo:', profileUrl);
        const profileImg = document.querySelector('img[alt="Profile Photo"]');
        if (profileImg) {
            profileImg.src = profileUrl;
        }
    }

    function clearPasswordFields() {
        const passwordFields = form.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            field.value = '';
        });
    }

    function displayValidationErrors(errors) {
        console.log('Displaying validation errors:', errors);
        
        // Clear previous errors
        clearValidationErrors();
        
        // Display new errors
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                const errorDiv = document.createElement('p');
                errorDiv.className = 'text-red-500 text-xs mt-1';
                errorDiv.textContent = errors[field][0];
                input.parentNode.appendChild(errorDiv);
                input.classList.add('border-red-500');
            }
        });
    }

    function clearValidationErrors() {
        const errorMessages = form.querySelectorAll('.text-red-500');
        errorMessages.forEach(error => error.remove());
        
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
        });
    }
});
