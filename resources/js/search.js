// Enhanced Search Functionality for home.blade.php
// Optimized untuk pencarian fokus di kolom title
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const searchBtn = document.getElementById('search-btn');
    const cardsContainer = document.getElementById('cards-container');
    const searchLoading = document.getElementById('search-loading');

    let searchTimeout;
    let currentQuery = '';
    let abortController = null;

    // Function to perform search dengan abort controller untuk performa
    async function performSearch(query) {
        if (query.length < 1) {
            showInitialCards();
            return;
        }

        // Abort request sebelumnya untuk performa
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        currentQuery = query;
        searchLoading.classList.remove('hidden');
        cardsContainer.classList.add('opacity-50');

        try {
            const response = await fetch(`/search?query=${encodeURIComponent(query)}`, {
                signal: abortController.signal
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data.success) {
                displaySearchResults(data.cards, data.message, data.total);
            } else {
                displayError(data.message || 'Terjadi kesalahan saat mencari');
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Error searching:', error);
                displayError('Terjadi kesalahan saat mencari. Silakan coba lagi.');
            }
        } finally {
            searchLoading.classList.add('hidden');
            cardsContainer.classList.remove('opacity-50');
        }
    }

    // Function to display search results dengan highlight
    function displaySearchResults(cards, message, total = 0) {
        if (cards.length === 0) {
            cardsContainer.innerHTML = `
                <div class="col-span-full text-center py-16" data-aos="zoom-in">
                    <div class="relative inline-block mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 float-animation" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">Tidak ada hasil ditemukan</h3>
                    <p class="text-gray-400 max-w-md mx-auto">${message || 'Coba kata kunci lain atau gunakan kata yang lebih umum.'}</p>
                    <div class="mt-6">
                        <button onclick="clearSearch()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg hover:from-emerald-800 hover:to-emerald-700 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Tampilkan Semua
                        </button>
                    </div>
                </div>
            `;
            return;
        }

        // Display results dengan highlight
        cardsContainer.innerHTML = `
            <div class="col-span-full mb-4">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">${total}</span> hasil untuk "<span class="font-semibold text-emerald-600">${currentQuery}</span>"
                </p>
            </div>
        ` + cards.map(card => `
            <article tabindex="0" data-aos="fade-up" data-aos-delay="${Math.random() * 100}"
                class="bg-white rounded-xl shadow-lg hover:shadow-2xl hover:shadow-emerald-500/20 hover:-translate-y-2 hover:scale-[1.02] transition-all duration-300 ease-out flex flex-col overflow-hidden cursor-pointer group">
                <div class="w-full overflow-hidden">
                    <img src="${card.image_url || '/images/placeholder.jpg'}" 
                         alt="${card.title}"
                         class="w-full max-h-40 object-cover bg-gray-100 transition-transform duration-300 group-hover:scale-110" 
                         onerror="this.src='/images/placeholder.jpg'"/>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h2 class="text-sm font-semibold text-emerald-900 mb-2 group-hover:text-emerald-700 transition-colors">
                        ${highlightText(card.title, currentQuery)}
                    </h2>
                    <p class="text-emerald-700 text-xs mb-3 line-clamp-3">${card.description}</p>
                    <a href="${card.external_link}" target="_blank" rel="noopener noreferrer"
                        class="mt-auto inline-flex items-center gap-1 text-white font-medium px-3 py-1.5 rounded bg-gradient-to-r from-emerald-900 to-emerald-700 text-xs hover:from-emerald-700 hover:to-emerald-600 transition-all">
                        Kunjungi
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 1 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                        </svg>
                    </a>
                </div>
            </article>
        `).join('');
    }

    // Function untuk highlight text yang match
    function highlightText(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    }

    // Function to display error
    function displayError(message) {
        cardsContainer.innerHTML = `
            <div class="col-span-full text-center py-16">
                <div class="text-red-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-600 mb-2">Terjadi Kesalahan</h3>
                <p class="text-gray-500">${message}</p>
            </div>
        `;
    }

    // Function to clear search
    function clearSearch() {
        searchInput.value = '';
        currentQuery = '';
        window.location.reload();
    }

    // Function to show initial cards
    function showInitialCards() {
        window.location.reload();
    }

    // Enhanced event listeners dengan debounce yang lebih baik
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();

        if (query.length === 0) {
            showInitialCards();
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300); // Debounce 300ms untuk respons lebih cepat
    });

    searchBtn.addEventListener('click', () => {
        const query = searchInput.value.trim();
        if (query.length >= 1) {
            performSearch(query);
        }
    });

    // Handle Enter key
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (query.length >= 1) {
                performSearch(query);
            }
        }
    });

    // Clear search on escape key
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            clearSearch();
        }
    });

    // Focus states
    searchInput.addEventListener('focus', () => {
        searchInput.parentElement.classList.add('ring-2', 'ring-emerald-500');
    });

    searchInput.addEventListener('blur', () => {
        searchInput.parentElement.classList.remove('ring-2', 'ring-emerald-500');
    });
});
