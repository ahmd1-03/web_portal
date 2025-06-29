export function userManager() {
    return {
        // Status modal untuk create, edit, dan delete
        openCreateModal: false, // Modal tambah pengguna
        openEditModalId: null, // Modal edit pengguna, menyimpan ID pengguna yang diedit
        openDeleteModalId: null, // Modal hapus pengguna, menyimpan ID pengguna yang akan dihapus
        searching: false, // Status pencarian sedang berlangsung
        searchQuery: "", // Query pencarian pengguna

        // Data form untuk create dan edit pengguna
        createForm: {
            name: "", // Nama pengguna baru
            email: "", // Email pengguna baru
            password: "", // Password pengguna baru
            password_confirmation: "", // Konfirmasi password pengguna baru
        },
        editForm: {
            name: "", // Nama pengguna yang diedit
            email: "", // Email pengguna yang diedit
            password: "", // Password baru (jika diubah)
            password_confirmation: "", // Konfirmasi password baru
        },
        userData: {}, // Data pengguna yang sedang diedit atau dihapus

        // Status validasi form dan loading
        errors: {}, // Menyimpan error validasi dari server atau client
        loading: false, // Status loading saat proses async (fetch)

        // Status visibilitas password di form create dan edit
        showPassword: false, // Tampilkan password di form create
        showPasswordConfirmation: false, // Tampilkan konfirmasi password di form create
        showEditPassword: false, // Tampilkan password di form edit
        showEditPasswordConfirmation: false, // Tampilkan konfirmasi password di form edit

        // Status validasi password dan pesan validasi untuk create dan edit
        passwordValid: false, // Password create valid atau tidak
        passwordValidationMessage: "", // Pesan validasi password create
        passwordConfirmationValid: false, // Konfirmasi password create valid atau tidak
        passwordConfirmationMessage: "", // Pesan validasi konfirmasi password create
        editPasswordValid: false, // Password edit valid atau tidak
        editPasswordValidationMessage: "", // Pesan validasi password edit
        editPasswordConfirmationValid: false, // Konfirmasi password edit valid atau tidak
        editPasswordConfirmationMessage: "", // Pesan validasi konfirmasi password edit

        // Fungsi inisialisasi komponen saat pertama kali dimuat
        init() {
            // Mengambil parameter pencarian dari URL jika ada
            const urlParams = new URLSearchParams(window.location.search);
            this.searchQuery = urlParams.get("search") || "";
        },

        // Fungsi untuk menangani pencarian pengguna dengan AJAX
        async handleSearch() {
            this.searching = true;

            try {
                // Mengambil data pencarian dari server
                const response = await fetch(
                    `${window.location.pathname}?search=${this.searchQuery}`,
                    {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest", // Header untuk identifikasi request AJAX
                        },
                    }
                );

                if (!response.ok) throw new Error("Gagal melakukan pencarian");

                // Parsing response dan update tabel
                const data = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, "text/html");
                const newTable = doc.querySelector("tbody");

                if (newTable) {
                    document.querySelector("tbody").innerHTML =
                        newTable.innerHTML;
                }
            } catch (error) {
                // Menampilkan error menggunakan SweetAlert
                await Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message,
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                    timer: undefined,
                    timerProgressBar: false,
                    showClass: {
                        popup: "animate__animated animate__fadeInDown",
                    },
                    hideClass: {
                        popup: "animate__animated animate__fadeOutUp",
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        Swal.hideLoading();
                    },
                });
            } finally {
                this.searching = false;
            }
        },

        // Fungsi untuk toggle visibility password
        togglePasswordVisibility(fieldId) {
            const input = document.getElementById(fieldId);
            if (input) {
                // Toggle antara type password dan text
                input.type = input.type === "password" ? "text" : "password";

                // Update state sesuai dengan field yang diubah
                if (fieldId === "create_password")
                    this.showPassword = !this.showPassword;
                if (fieldId === "create_password_confirmation")
                    this.showPasswordConfirmation =
                        !this.showPasswordConfirmation;
                if (fieldId === "edit_password")
                    this.showEditPassword = !this.showEditPassword;
                if (fieldId === "edit_password_confirmation")
                    this.showEditPasswordConfirmation =
                        !this.showEditPasswordConfirmation;
            }
        },

        // Fungsi validasi password untuk form create
        validatePassword() {
            const password = this.createForm.password;

            // Jika password kosong, reset validasi
            if (!password) {
                this.passwordValid = false;
                this.passwordValidationMessage = "";
                return;
            }

            // Kriteria validasi password:
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);

            // Kumpulkan pesan error jika ada
            let messages = [];
            if (!hasMinLength) messages.push("Minimal 8 karakter");
            if (!hasUpperCase) messages.push("Harus mengandung huruf besar");
            if (!hasLowerCase) messages.push("Harus mengandung huruf kecil");

            // Set status validasi dan pesan
            if (messages.length > 0) {
                this.passwordValid = false;
                this.passwordValidationMessage =
                    "Password harus: " + messages.join(", ");
            } else {
                this.passwordValid = true;
                this.passwordValidationMessage = "Password valid!";
            }
        },

        // Fungsi validasi konfirmasi password untuk form create
        validatePasswordConfirmation() {
            const password = this.createForm.password;
            const confirmation = this.createForm.password_confirmation;

            // Jika konfirmasi kosong, reset validasi
            if (!confirmation) {
                this.passwordConfirmationValid = false;
                this.passwordConfirmationMessage = "";
                return;
            }

            // Cek kecocokan password dengan konfirmasi
            if (password === confirmation) {
                this.passwordConfirmationValid = true;
                this.passwordConfirmationMessage = "Password cocok!";
            } else {
                this.passwordConfirmationValid = false;
                this.passwordConfirmationMessage = "Password tidak cocok!";
            }
        },

        // Fungsi validasi password untuk form edit
        validateEditPassword() {
            const password = this.editForm.password;

            // Jika password kosong (tidak diubah), dianggap valid
            if (!password) {
                this.editPasswordValid = true;
                this.editPasswordValidationMessage = "";
                return;
            }

            // Kriteria validasi sama dengan form create
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);

            let messages = [];
            if (!hasMinLength) messages.push("Minimal 8 karakter");
            if (!hasUpperCase) messages.push("Harus mengandung huruf besar");
            if (!hasLowerCase) messages.push("Harus mengandung huruf kecil");

            if (messages.length > 0) {
                this.editPasswordValid = false;
                this.editPasswordValidationMessage =
                    "Password harus: " + messages.join(", ");
            } else {
                this.editPasswordValid = true;
                this.editPasswordValidationMessage = "Password valid!";
            }
        },

        // Fungsi validasi konfirmasi password untuk form edit
        validateEditPasswordConfirmation() {
            const password = this.editForm.password;
            const confirmation = this.editForm.password_confirmation;

            // Jika password kosong (tidak diubah), dianggap valid
            if (!password) {
                this.editPasswordConfirmationValid = true;
                this.editPasswordConfirmationMessage = "";
                return;
            }

            // Jika konfirmasi kosong, reset validasi
            if (!confirmation) {
                this.editPasswordConfirmationValid = false;
                this.editPasswordConfirmationMessage = "";
                return;
            }

            // Cek kecocokan password dengan konfirmasi
            if (password === confirmation) {
                this.editPasswordConfirmationValid = true;
                this.editPasswordConfirmationMessage = "Password cocok!";
            } else {
                this.editPasswordConfirmationValid = false;
                this.editPasswordConfirmationMessage = "Password tidak cocok!";
            }
        },

        // Fungsi untuk menutup semua modal
        closeAllModals() {
            this.openCreateModal = false;
            this.openEditModalId = null;
            this.openDeleteModalId = null;
            this.errors = {}; // Reset error validasi
        },

        // Fungsi untuk membuka modal edit dan mengambil data pengguna
        async openEditModal(id) {
            this.loading = true;
            this.errors = {}; // Reset error validasi

            try {
                // Ambil data pengguna dari server
                const response = await fetch(`/admin/users/${id}/edit`);
                if (!response.ok)
                    throw new Error("Gagal mengambil data pengguna.");

                // Set data ke form edit
                const data = await response.json();
                this.userData = data;
                this.editForm = {
                    name: data.name,
                    email: data.email,
                    password: "",
                    password_confirmation: "",
                };
                this.openEditModalId = id; // Buka modal edit
            } catch (error) {
                // Tampilkan error jika terjadi masalah
                await Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message,
                    showConfirmButton: true,
                    confirmButtonText: "OK",
                    timer: undefined,
                    timerProgressBar: false,
                    showClass: {
                        popup: "animate__animated animate__fadeInDown",
                    },
                    hideClass: {
                        popup: "animate__animated animate__fadeOutUp",
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        Swal.hideLoading();
                    },
                });
            } finally {
                this.loading = false;
            }
        },

        // Fungsi untuk membuka modal delete konfirmasi
        openDeleteModal(id) {
            this.openDeleteModalId = id;
            // Ambil data pengguna dari row tabel
            const row = event.target.closest("tr");
            if (row) {
                this.userData = {
                    name: row.cells[1].textContent,
                    email: row.cells[2].textContent,
                };
            }
        },

        // Fungsi untuk submit form create
        async submitCreateForm() {
            this.loading = true;
            this.errors = {};

            // Validasi konfirmasi password di client side
            if (
                this.createForm.password !==
                this.createForm.password_confirmation
            ) {
                this.errors.password_confirmation = [
                    "Konfirmasi password tidak cocok.",
                ];
                this.loading = false;
                return;
            }

            try {
                // Kirim data ke server
                const response = await fetch(
                    document.querySelector("#store_route").value,
                    {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        },
                        body: new FormData(this.$refs.createForm),
                    }
                );

                const data = await response.json();

                // Handle error validasi dari server
                if (response.status === 422) {
                    this.errors = data.errors || {};
                    await Swal.fire({
                        icon: "error",
                        title: "Error Validasi",
                        text: "Periksa kembali input Anda.",
                        confirmButtonText: "Kembali ke Form",
                    });
                    // Biarkan modal tetap terbuka untuk perbaikan
                    this.openCreateModal = true;
                    return;
                }

                // Handle error lainnya
                if (!response.ok) {
                    await Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "Gagal menyimpan data.",
                        confirmButtonText: "Kembali ke Form",
                    });
                    this.openCreateModal = true;
                    return;
                }

                // Tutup modal dan tampilkan pesan sukses
                this.openCreateModal = false;

                await Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: data.message || "Pengguna berhasil ditambahkan!",
                    showConfirmButton: false,
                    timer: 2000,
                });

                // Reset form dan refresh tabel
                this.createForm = {
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                };

                await this.refreshUserTable();
            } catch (error) {
                await Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message,
                    confirmButtonText: "Kembali ke Form",
                });
                this.openCreateModal = true;
            } finally {
                this.loading = false;
            }
        },

        // Fungsi untuk submit form edit
        async submitEditForm() {
            this.loading = true;
            this.errors = {};

            // Validasi client side untuk field wajib
            if (!this.editForm.name || this.editForm.name.trim() === "") {
                this.errors.name = ["Nama wajib diisi."];
            }
            if (!this.editForm.email || this.editForm.email.trim() === "") {
                this.errors.email = ["Email wajib diisi."];
            }
            // Validasi konfirmasi password jika password diubah
            if (
                this.editForm.password &&
                this.editForm.password !== this.editForm.password_confirmation
            ) {
                this.errors.password_confirmation = [
                    "Konfirmasi password tidak cocok.",
                ];
            }

            // Jika ada error validasi, hentikan proses
            if (Object.keys(this.errors).length > 0) {
                this.loading = false;
                return;
            }

            try {
                // Siapkan FormData untuk dikirim
                const formData = new FormData(this.$refs.editForm);
                // Jika password tidak diubah, hapus field password dari FormData
                if (!this.editForm.password) {
                    formData.delete("password");
                    formData.delete("password_confirmation");
                }

                // Kirim data ke server
                const response = await fetch(
                    `/admin/users/${this.openEditModalId}`,
                    {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        },
                        body: formData,
                    }
                );

                const data = await response.json();

                // Handle error validasi dari server
                if (response.status === 422) {
                    this.errors = data.errors || {};
                    return;
                }

                // Handle error lainnya
                if (!response.ok) {
                    await Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "Gagal menyimpan data.",
                        confirmButtonText: "Kembali ke Form",
                    });
                    return;
                }

                // Tutup modal dan tampilkan pesan sukses
                this.openEditModalId = null;

                await Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: data.message || "Data pengguna berhasil diperbarui!",
                    showConfirmButton: false,
                    timer: 2000,
                });

                // Refresh tabel
                await this.refreshUserTable();
            } catch (error) {
                await Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message,
                    confirmButtonText: "Kembali ke Form",
                });
                // Biarkan modal tetap terbuka
                this.openEditModalId = this.openEditModalId;
            } finally {
                this.loading = false;
            }
        },

        // Fungsi untuk submit form delete
        async submitDeleteForm() {
            this.loading = true;
            this.errors = {};

            try {
                // Kirim request delete ke server
                const response = await fetch(
                    `/admin/users/${this.openDeleteModalId}`,
                    {
                        method: "DELETE",
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        },
                    }
                );

                const data = await response.json();

                // Handle error validasi
                if (response.status === 422) {
                    this.errors = data.errors || {};
                    return;
                }

                // Handle error lainnya
                if (!response.ok) {
                    throw new Error(data.message || "Gagal menghapus data.");
                }

                // Tampilkan pesan sukses
                await Swal.fire({
                    icon: "success",
                    title: "Sukses",
                    text: data.message || "Pengguna berhasil dihapus!",
                    showConfirmButton: false,
                    timer: 2000,
                });

                // Tutup modal dan refresh tabel
                this.openDeleteModalId = null;
                await this.refreshUserTable();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.message,
                });
            } finally {
                this.loading = false;
            }
        },

        // Fungsi untuk merefresh tabel pengguna tanpa reload halaman
        async refreshUserTable() {
            try {
                // Reset ke halaman pertama untuk memastikan data baru/update termasuk
                const url = new URL(window.location.href);
                url.searchParams.set("page", "1");

                // Ambil data terbaru dari server
                const response = await fetch(url.toString(), {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (!response.ok) throw new Error("Gagal memuat data pengguna");

                // Parsing response dan update tabel
                const data = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, "text/html");
                const newTable = doc.querySelector("tbody");
                const newTotal = doc.querySelector("#totalCount");

                // Update tabel jika ada perubahan
                if (newTable) {
                    document.querySelector("tbody").innerHTML =
                        newTable.innerHTML;
                }

                // Update total count jika ada
                if (newTotal) {
                    const totalElement = document.querySelector("#totalCount");
                    if (totalElement) {
                        totalElement.innerHTML = newTotal.innerHTML;
                    }
                }
            } catch (error) {
                console.error("Error refreshing table:", error);
            }
        },
    };
}
