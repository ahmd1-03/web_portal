/**
 * Manajemen kartu konten dengan Alpine.js
 * @returns {Object} Objek Alpine.js untuk mengelola CRUD kartu
 */
export function cardManager(initialPagination = {from: 0, to: 0, total: 0}) {
    return {

        pagination: {
            from: initialPagination.from,
            to: initialPagination.to,
            total: initialPagination.total,
        },
        
        // Status modal
        openCreateModal: false,
        openEditModalId: null,
        openDeleteModalId: null,
        cardData: {},
        errors: {},
        loading: false,
        searchQuery: "",
        searching: false,
        currentPage: 1,
        perPage: 10, // Default 10 per halaman
        pollingIntervalId: null,
        createForm: {
            title: "",
            description: "",
            external_link: "",
            is_active: true, // Default aktif saat create

        
        },
        preview: null,
        previewEdit: null,

        

        /**
         * Inisialisasi komponen
         */
        init() {
            const urlParams = new URLSearchParams(window.location.search);
            this.searchQuery = urlParams.get("search") || "";
            this.currentPage = parseInt(urlParams.get("page")) || 1;
            this.perPage = parseInt(urlParams.get("per_page")) || 10;

            // Set event listener untuk pagination
            this.addPaginationListeners();
        },

        /**
         * Hentikan polling saat komponen dihancurkan
         */
        destroy() {
            if (this.pollingIntervalId) {
                clearInterval(this.pollingIntervalId);
            }
        },

        /**
         * Menangani pencarian kartu dengan AJAX
         * @returns {Promise<void>}
         */
        async handleSearch() {
            this.searching = true;
            this.currentPage = 1; // Reset ke halaman 1 saat pencarian baru
            try {
                await this.refreshCardTable();
            } catch (error) {
                this.showError(error.message);
            } finally {
                this.searching = false;
            }
        },

        /**
         * Menangani perubahan jumlah data per halaman
         * @returns {Promise<void>}
         */
        async changePerPage() {
            this.currentPage = 1; // Reset ke halaman 1 saat mengubah perPage
            try {
                await this.refreshCardTable();
            } catch (error) {
                this.showError(error.message);
            }
        },

        /**
         * Mengatur halaman saat ini saat mengklik tautan pagination
         * @param {number} page - Nomor halaman yang dipilih
         */
        setPage(page) {
            this.currentPage = page;
            this.refreshCardTable();
        },

        /**
         * Menampilkan pesan error menggunakan SweetAlert2
         * @param {string} message - Pesan error yang ditampilkan
         */
        showError(message) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: message,
            });
        },

        /**
         * Menampilkan pesan sukses menggunakan SweetAlert2
         * @param {string} message - Pesan sukses yang ditampilkan
         * @returns {Promise}
         */
        showSuccess(message) {
            return Swal.fire({
                icon: "success",
                title: "Sukses",
                text: message,
                showConfirmButton: false,
                timer: 2000,
            });
        },

        /**
         * Menutup semua modal dan mereset state terkait
         */
        closeAllModals() {
            this.openCreateModal = false;
            this.openEditModalId = null;
            this.openDeleteModalId = null;
            this.errors = {};
            this.preview = null;
            this.previewEdit = null;
        },

        /**
         * Membuka modal edit dan mengambil data kartu
         * @param {number} id - ID kartu yang akan diedit
         * @returns {Promise<void>}
         */
        async openEditModal(id) {
            this.loading = true;
            this.errors = {};
            try {
                const response = await fetch(`/admin/cards/${id}/edit`);
                if (!response.ok)
                    throw new Error("Gagal mengambil data kartu.");
                const data = await response.json();
                if (data.image_url && !data.image_url.startsWith("http")) {
                    data.image_url = `${window.location.origin}/storage/${data.image_url}`;
                }
                this.cardData = data;
                this.openEditModalId = id;
                this.previewEdit = null;
                if (this.$refs.editImageInput) {
                    this.$refs.editImageInput.value = null;
                }
            } catch (error) {
                this.showError(error.message);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Membuka modal konfirmasi hapus dan mengatur data kartu
         * @param {number} id - ID kartu yang akan dihapus
         * @param {Event} event - Event klik untuk mengambil data dari tabel
         */
        openDeleteModal(id, event) {
            this.openDeleteModalId = id;
            if (event) {
                const row = event.target.closest("tr");
                if (row) {
                    this.cardData = {
                        title: row.cells[1].textContent,
                        description: row.cells[2].textContent,
                        external_link:
                            row.cells[3].querySelector("a")?.href || "",
                        image_url: row.cells[4].querySelector("img")?.src || "",
                        is_active:
                            row.cells[3].querySelector("span").textContent ===
                            "Aktif",
                    };
                }
            }
        },

        /**
         * Mengirimkan form tambah kartu dengan validasi dan AJAX
         * @returns {Promise<void>}
         */
        async submitCreateForm() {
            this.errors = {};
            const fileInput = this.$refs.createForm.querySelector(
                'input[name="image_url"]'
            );
            const hasImage =
                fileInput && fileInput.files && fileInput.files.length > 0;
            const isActive = this.$refs.createForm.querySelector(
                'input[name="is_active"]'
            ).checked;

            // Validasi form
            if (!this.createForm.title || this.createForm.title.trim() === "") {
                this.errors.title = ["Nama/Judul Kartu wajib diisi."];
            }
            if (
                !this.createForm.description ||
                this.createForm.description.trim() === ""
            ) {
                this.errors.description = ["Deskripsi wajib diisi."];
            }
            if (!hasImage) {
                this.errors.image_url = ["Gambar harus diunggah."];
            } else if (fileInput.files[0].size > 2 * 1024 * 1024) {
                this.errors.image_url = ["Ukuran file maksimum 2MB."];
            }

            if (Object.keys(this.errors).length > 0) {
                return;
            }

            this.loading = true;
            try {
                const formData = new FormData(this.$refs.createForm);
                formData.append("is_active", isActive ? "1" : "0");

                const storeUrl = document.querySelector("#store_route").value;
                const response = await fetch(storeUrl, {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: formData,
                });
                const data = await response.json();

                if (response.status === 422) {
                    this.errors = data.errors || {};
                    return;
                }
                if (!response.ok) {
                    throw new Error(data.message || "Gagal menyimpan data.");
                }

                this.openCreateModal = false;
                await this.showSuccess(
                    data.message || "Kartu berhasil ditambahkan!"
                );
                this.createForm = {
                    title: "",
                    description: "",
                    external_link: "",
                };
                this.preview = null;
                if (fileInput) fileInput.value = null;
                await this.refreshCardTable();
            } catch (error) {
                this.showError(error.message);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Mengirimkan form edit kartu dengan validasi dan AJAX
         * @returns {Promise<void>}
         */
        async submitEditForm() {
            this.errors = {};
            this.loading = true;

            const isActive = this.$refs.editForm.querySelector(
                'input[name="is_active"]'
            ).checked;

            // Validasi form
            if (!this.cardData.title || this.cardData.title.trim() === "") {
                this.errors.title = ["Nama/Judul Kartu wajib diisi."];
            }
            if (
                !this.cardData.description ||
                this.cardData.description.trim() === ""
            ) {
                this.errors.description = ["Deskripsi wajib diisi."];
            }
            const hasOldImage =
                this.cardData.image_url &&
                this.cardData.image_url.trim() !== "";
            const hasNewImage = this.previewEdit !== null;
            if (!hasOldImage && !hasNewImage) {
                this.errors.image_url = ["Gambar harus diunggah."];
            } else if (
                hasNewImage &&
                this.$refs.editImageInput.files[0].size > 2 * 1024 * 1024
            ) {
                this.errors.image_url = ["Ukuran file maksimum 2MB."];
            }

            if (Object.keys(this.errors).length > 0) {
                this.loading = false;
                return;
            }

            try {
                const formData = new FormData(this.$refs.editForm);
                formData.append("is_active", isActive ? "1" : "0");

                formData.forEach((value, key) => {
                    if (value === "" || value === null || value === undefined) {
                        formData.delete(key);
                    }
                });
                formData.set("_method", "PUT");

                const response = await fetch(
                    `/admin/cards/${this.openEditModalId}`,
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

                if (response.status === 422) {
                    this.errors = data.errors || {};
                    return;
                }
                if (!response.ok) {
                    throw new Error(data.message || "Gagal menyimpan data.");
                }

                if (data.card && data.card.image_url) {
                    this.cardData.image_url = data.card.image_url;
                }
                this.previewEdit = null;
                this.openEditModalId = null;
                await this.refreshCardTable();
                await this.showSuccess(
                    data.message || "Data kartu berhasil diperbarui!"
                );
            } catch (error) {
                this.showError(error.message);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Mengirimkan form hapus kartu dengan AJAX
         * @returns {Promise<void>}
         */
        async submitDeleteForm() {
            this.loading = true;
            this.errors = {};

            try {
                const response = await fetch(
                    `/admin/cards/${this.openDeleteModalId}`,
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

                if (response.status === 422) {
                    this.errors = data.errors || {};
                    this.openDeleteModalId = null;
                    await this.showError(data.message || "Validasi gagal.");
                    return;
                }
                if (!response.ok) {
                    this.openDeleteModalId = null;
                    await this.showError(
                        data.message || "Gagal menghapus data."
                    );
                    return;
                }

                this.openDeleteModalId = null;
                await this.showSuccess(
                    data.message || "Kartu berhasil dihapus!"
                );
                await this.refreshCardTable();
            } catch (error) {
                this.openDeleteModalId = null;
                await this.showError(error.message);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Memperbarui tabel kartu tanpa reload halaman
         * @returns {Promise<void>}
         */
        async refreshCardTable() {
            try {
                const url = new URL(window.location.href);
                url.searchParams.set("page", this.currentPage);
                url.searchParams.set("per_page", this.perPage);
                if (this.searchQuery) {
                    url.searchParams.set(
                        "search",
                        encodeURIComponent(this.searchQuery)
                    );
                } else {
                    url.searchParams.delete("search");
                }

                const response = await fetch(url.toString(), {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    cache: "no-store",
                });

                if (!response.ok) throw new Error("Gagal memuat data kartu");

                const data = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, "text/html");

                // Update tabel
                const newTable = doc.querySelector("tbody");
                if (newTable) {
                    document.querySelector("tbody").innerHTML =
                        newTable.innerHTML;
                }

                // Update summary cards
                const newTotal = doc.querySelector("#totalCount");
                const newActiveCount = doc.querySelector("#activeCount");
                const newInactiveCount = doc.querySelector("#inactiveCount");

                if (newTotal) {
                    document.querySelector("#totalCount").textContent =
                        newTotal.textContent;
                }
                if (newActiveCount) {
                    document.querySelector("#activeCount").textContent =
                        newActiveCount.textContent;
                }
                if (newInactiveCount) {
                    document.querySelector("#inactiveCount").textContent =
                        newInactiveCount.textContent;
                }

                // Update pagination
                const newPagination = doc.querySelector(".pagination");
                if (newPagination) {
                    document.querySelector(".pagination").innerHTML =
                        newPagination.innerHTML;
                    this.addPaginationListeners();
                }

                // Update URL tanpa reload halaman
                window.history.pushState({}, "", url.toString());
            } catch (error) {
                console.error("Error refreshing table:", error);
                this.showError("Gagal memuat data terbaru");
            }
        },

        /**
         * Menambahkan event listener untuk tautan pagination
         */
        addPaginationListeners() {
            const links = document.querySelectorAll(".pagination a");
            links.forEach((link) => {
                link.addEventListener("click", (event) => {
                    event.preventDefault();
                    const url = new URL(link.href);
                    const page = url.searchParams.get("page") || 1;
                    this.setPage(parseInt(page));
                });
            });
        },

        /**
         * Mengubah status aktif/nonaktif kartu
         * @param {number} id - ID kartu
         * @param {boolean} isActive - Status aktif saat ini
         * @returns {Promise<void>}
         */
        async toggleCardStatus(id, isActive) {
            this.loading = true;
            try {
                const response = await fetch(
                    `/admin/cards/${id}/toggle-status`,
                    {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ is_active: !isActive }),
                    }
                );
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message || "Gagal mengubah status kartu"
                    );
                }

                // Update summary cards
                const activeCountElement =
                    document.querySelector("#activeCount");
                const inactiveCountElement =
                    document.querySelector("#inactiveCount");

                if (activeCountElement && inactiveCountElement) {
                    const currentActive = parseInt(
                        activeCountElement.textContent
                    );
                    const currentInactive = parseInt(
                        inactiveCountElement.textContent
                    );

                    if (isActive) {
                        activeCountElement.textContent = currentActive - 1;
                        inactiveCountElement.textContent = currentInactive + 1;
                    } else {
                        activeCountElement.textContent = currentActive + 1;
                        inactiveCountElement.textContent = currentInactive - 1;
                    }
                }

                await this.refreshCardTable();
                await this.showSuccess(
                    data.message || "Status kartu berhasil diubah"
                );
            } catch (error) {
                this.showError(error.message);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Membersihkan preview gambar edit
         */
        clearPreviewEdit() {
            this.previewEdit = null;
            if (this.$refs.editImageInput) {
                this.$refs.editImageInput.value = null;
            }
        },
    };
}
