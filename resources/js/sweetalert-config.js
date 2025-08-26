// SweetAlert2 Configuration and Helper Functions
import Swal from 'sweetalert2';

// Default configuration
const swalConfig = {
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    allowOutsideClick: false,
    allowEscapeKey: false,
    customClass: {
        confirmButton: 'px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700',
        cancelButton: 'px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400'
    }
};

// Show loading state
export const showLoading = (title = 'Memproses...', text = 'Mohon tunggu sebentar') => {
    Swal.fire({
        title,
        text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
};

// Show success notification
export const showSuccess = (title, text = '', timer = 2000) => {
    Swal.fire({
        icon: 'success',
        title,
        text,
        timer,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        customClass: {
            popup: 'colored-toast'
        }
    });
};

// Show error notification
export const showError = (title, text = '') => {
    Swal.fire({
        icon: 'error',
        title,
        text,
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33'
    });
};

// Show confirmation dialog
export const showConfirm = (title, text, confirmText = 'Ya, lanjutkan') => {
    return Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        ...swalConfig
    });
};

// Handle AJAX response with SweetAlert
export const handleAjaxResponse = async (response, successCallback = null) => {
    if (response.success) {
        showSuccess(response.message || 'Operasi berhasil');
        if (successCallback) {
            successCallback(response);
        }
    } else {
        showError('Gagal', response.message || 'Terjadi kesalahan');
    }
};

// Handle AJAX error
export const handleAjaxError = (error) => {
    console.error('AJAX Error:', error);
    showError('Error', error.message || 'Terjadi kesalahan pada server');
};

// Export Swal for direct usage
export { Swal };
