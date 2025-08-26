// Import library dan modul yang diperlukan
import "./bootstrap"; // Import file bootstrap.js untuk inisialisasi framework
import Swal from 'sweetalert2'; // Import SweetAlert2
import { userManager } from "./userManager"; // Import modul manajemen user
import { cardManager } from "./cardManager"; // Import modul manajemen card
import { TimeUpdater } from "./timeUpdater"; // Import modul untuk update waktu real-time

// Ekspos SweetAlert ke global window
window.Swal = Swal;

// Ekspos modul ke global window agar bisa diakses dari mana saja
window.userManager = userManager; // Membuat userManager tersedia di global scope
window.cardManager = cardManager; // Membuat cardManager tersedia di global scope
window.TimeUpdater = TimeUpdater; // Membuat TimeUpdater tersedia di global scope

/**
 * Wrapper terpusat untuk SweetAlert dengan animasi dan style yang konsisten
 * @param {Object} options - Opsi untuk SweetAlert
 * @param {string} options.icon - Jenis ikon ('success', 'error', dll)
 * @param {string} options.title - Judul notifikasi
 * @param {string} options.text - Isi pesan notifikasi
 * @param {number} [options.timer] - Timer auto close dalam milidetik (opsional)
 * @param {boolean} [options.timerProgressBar] - Tampilkan progress bar timer (default false)
 * @param {string} [options.redirectUrl] - URL untuk redirect setelah timer habis (opsional)
 * @param {boolean} [options.showConfirmButton] - Tampilkan tombol konfirmasi (default true)
 * @returns {Promise} - Promise yang resolve ketika SweetAlert ditutup
 */
export function showSweetAlert({
    icon, // Jenis ikon: success, error, warning, info, question
    title, // Judul notifikasi
    text, // Isi pesan notifikasi
    timer, // Waktu auto close (ms)
    timerProgressBar = false, // Tampilkan progress bar timer
    redirectUrl, // URL untuk redirect
    showConfirmButton = true, // Tampilkan tombol konfirmasi
}) {
    // Memanggil SweetAlert dengan konfigurasi
    return Swal.fire({
        icon,
        title,
        text,
        timer,
        timerProgressBar,
        showConfirmButton,
        // Warna tombol sesuai jenis ikon
        confirmButtonColor: icon === "success" ? "#10b981" : "#dc2626", // Hijau untuk sukses, merah untuk error
        // Animasi saat muncul
        showClass: {
            popup: "animate__animated animate__fadeInDown", // Animasi fade in dari atas
        },
        // Animasi saat menghilang
        hideClass: {
            popup: "animate__animated animate__fadeOutUp", // Animasi fade out ke atas
        },
        // Pengaturan interaksi
        allowOutsideClick: !timer, // Izinkan klik di luar jika tidak ada timer
        allowEscapeKey: !timer, // Izinkan escape key jika tidak ada timer
    }).then(() => {
        // Jika ada redirectUrl, arahkan ke URL tersebut setelah notifikasi ditutup
        if (redirectUrl) {
            window.location.href = redirectUrl;
        }
    });
}
