<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahana Kami - Mestakara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CFD916',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .slide-down {
            animation: slideDown 0.4s ease-out forwards;
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .image-zoom {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover .image-zoom {
            transform: scale(1.15);
        }

        .filter-btn {
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background-color: #CFD916;
            color: black;
            font-weight: 600;
        }

        .search-container {
            position: relative;
        }

        .search-results {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .search-results.show {
            max-height: 400px;
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .sticky-header {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.95);
        }

        .view-toggle button {
            transition: all 0.2s ease;
        }

        .view-toggle button.active {
            background-color: #CFD916;
            color: black;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Enhanced Navbar -->
    <nav class="w-full py-3 sm:py-4 px-4 sm:px-7 flex items-center justify-between sticky-header border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
        <a href="#" class="text-xl sm:text-3xl font-bold text-black italic">
            Mesta<span class="text-primary">Kara</span>.
        </a>
        
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- View Toggle (Desktop) -->
            <div class="hidden sm:flex items-center gap-2 view-toggle">
                <button onclick="setView('grid')" class="view-btn active p-2 rounded-lg hover:bg-gray-100 transition-all" data-view="grid">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button onclick="setView('list')" class="view-btn p-2 rounded-lg hover:bg-gray-100 transition-all" data-view="list">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <a href="{{ route('home') }}" class="bg-primary text-black px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg hover:bg-yellow-500 transition-all duration-300 font-medium text-sm sm:text-base shadow-sm">
                Dashboard
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 sm:pt-24 px-4 sm:px-7 pb-8 sm:pb-12">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto mb-8 sm:mb-12 slide-down">
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-5xl font-bold mb-3 sm:mb-4 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                    Jelajahi Wahana Kami
                </h1>
                <p class="text-gray-600 text-sm sm:text-lg max-w-2xl mx-auto">
                    Temukan pengalaman seru dan tak terlupakan di berbagai wahana pilihan kami
                </p>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1 search-container">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Cari wahana favorit Anda..." 
                                class="w-full px-4 py-3 pl-12 pr-4 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors"
                                oninput="searchWahana()"
                            >
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <button onclick="clearSearch()" id="clearBtn" class="hidden absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="sm:w-48">
                        <select 
                            id="sortSelect"
                            onchange="sortWahana()"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors cursor-pointer"
                        >
                            <option value="default">Urutkan</option>
                            <option value="name-asc">Nama (A-Z)</option>
                            <option value="name-desc">Nama (Z-A)</option>
                            <option value="newest">Terbaru</option>
                        </select>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="flex flex-wrap gap-2 mt-4" id="categoryFilter">
                    <button onclick="filterCategory('all')" class="filter-btn active px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-gray-200 transition-all" data-category="all">
                        Semua
                    </button>
                    <button onclick="filterCategory('wahana')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-gray-200 transition-all" data-category="wahana">
                        Wahana
                    </button>
                    <button onclick="filterCategory('fasilitas')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-gray-200 transition-all" data-category="fasilitas">
                        Fasilitas
                    </button>
                </div>

                <!-- Results Info -->
                <div class="mt-4 flex items-center justify-between text-sm">
                    <span id="resultsCount" class="text-gray-600">Menampilkan semua wahana</span>
                    <button onclick="resetFilters()" class="text-primary hover:text-yellow-600 font-medium transition-colors">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Wahana Grid -->
        <div class="max-w-7xl mx-auto">
            <div id="wahanaGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($facilities as $index => $facility)
                <a 
                    href="{{ route('wahana.show', $facility->id) }}" 
                    class="wahana-card group block bg-white rounded-2xl shadow-md overflow-hidden card-hover border-2 border-transparent hover:border-primary hover:shadow-2xl"
                    data-name="{{ strtolower($facility->name) }}"
                    data-category="{{ strtolower($facility->category) }}"
                    data-index="{{ $index }}"
                    style="animation-delay: {{ $index * 0.1 }}s"
                >
                    <!-- Image Container -->
                    <div class="relative h-64 sm:h-72 overflow-hidden bg-gray-200">
                        <img 
                            src="{{ asset('storage/' . $facility->image) }}" 
                            alt="{{ $facility->name }}" 
                            class="w-full h-full object-cover image-zoom"
                        >
                        
                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-primary text-black text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                {{ ucfirst($facility->category) }}
                            </span>
                        </div>

                        <!-- Favorite Button -->
                        <button 
                            onclick="toggleFavorite(event, {{ $facility->id }})"
                            class="favorite-btn absolute top-4 right-4 p-2 bg-white bg-opacity-90 rounded-full hover:bg-opacity-100 transition-all shadow-lg"
                            data-id="{{ $facility->id }}"
                        >
                            <svg class="w-5 h-5 text-gray-400 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>

                        <!-- Overlay Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
                        
                        <!-- Content Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 text-white">
                            <h3 class="text-xl sm:text-2xl font-bold mb-2 drop-shadow-lg group-hover:text-primary transition-colors">
                                {{ $facility->name }}
                            </h3>
                            <p class="text-sm opacity-90 line-clamp-2">
                                {{ $facility->description ?? 'Nikmati pengalaman seru di wahana ini' }}
                            </p>
                        </div>

                        <!-- Quick View Button -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="bg-white text-black px-6 py-3 rounded-full font-semibold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 flex items-center gap-2">
                                <span>Lihat Detail</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden col-span-full text-center py-12 sm:py-16">
                <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 max-w-md mx-auto">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak ada wahana ditemukan</h3>
                    <p class="text-gray-500 mb-4">Coba kata kunci atau filter lain</p>
                    <button onclick="resetFilters()" class="bg-primary text-black px-6 py-2 rounded-lg font-medium hover:bg-yellow-500 transition-all">
                        Reset Pencarian
                    </button>
                </div>
            </div>

            @if($facilities->count() == 0)
            <div class="col-span-full text-center py-12 sm:py-16">
                <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12 max-w-md mx-auto">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada wahana tersedia</h3>
                    <p class="text-gray-500">Wahana menarik akan segera hadir!</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Floating Stats -->
        <div class="fixed bottom-6 left-6 bg-white rounded-xl shadow-lg p-4 hidden lg:block">
            <div class="text-sm text-gray-600 mb-1">Total Wahana</div>
            <div class="text-2xl font-bold text-primary" id="totalCount">{{ $facilities->count() }}</div>
        </div>
    </main>

    <script>
        let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
        let currentView = 'grid';

        // Initialize favorites on load
        document.addEventListener('DOMContentLoaded', function() {
            updateFavoriteButtons();
            initializeAnimations();
        });

        function initializeAnimations() {
            const cards = document.querySelectorAll('.wahana-card');
            cards.forEach(card => {
                card.classList.add('fade-in-up');
            });
        }

        function searchWahana() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.wahana-card');
            const clearBtn = document.getElementById('clearBtn');
            let visibleCount = 0;

            clearBtn.classList.toggle('hidden', searchTerm === '');

            cards.forEach(card => {
                const name = card.dataset.name;
                const matches = name.includes(searchTerm);
                
                if (matches) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            updateResultsCount(visibleCount);
            toggleEmptyState(visibleCount === 0);
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            searchWahana();
        }

        function filterCategory(category) {
            const buttons = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.wahana-card');
            let visibleCount = 0;

            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.category === category) {
                    btn.classList.add('active');
                }
            });

            cards.forEach(card => {
                const cardCategory = card.dataset.category;
                const matches = category === 'all' || cardCategory === category;
                
                if (matches) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            updateResultsCount(visibleCount, category);
            toggleEmptyState(visibleCount === 0);
        }

        function sortWahana() {
            const sortValue = document.getElementById('sortSelect').value;
            const grid = document.getElementById('wahanaGrid');
            const cards = Array.from(document.querySelectorAll('.wahana-card'));

            cards.sort((a, b) => {
                const nameA = a.dataset.name;
                const nameB = b.dataset.name;

                switch(sortValue) {
                    case 'name-asc':
                        return nameA.localeCompare(nameB);
                    case 'name-desc':
                        return nameB.localeCompare(nameA);
                    case 'newest':
                        return b.dataset.index - a.dataset.index;
                    default:
                        return a.dataset.index - b.dataset.index;
                }
            });

            cards.forEach(card => grid.appendChild(card));
        }

        function toggleFavorite(event, id) {
            event.preventDefault();
            event.stopPropagation();
            
            const index = favorites.indexOf(id);
            const btn = event.currentTarget;
            const svg = btn.querySelector('svg');

            if (index > -1) {
                favorites.splice(index, 1);
                svg.setAttribute('fill', 'none');
                svg.classList.remove('text-red-500');
                svg.classList.add('text-gray-400');
            } else {
                favorites.push(id);
                svg.setAttribute('fill', 'currentColor');
                svg.classList.remove('text-gray-400');
                svg.classList.add('text-red-500');
            }

            localStorage.setItem('favorites', JSON.stringify(favorites));
        }

        function updateFavoriteButtons() {
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                const id = parseInt(btn.dataset.id);
                const svg = btn.querySelector('svg');
                
                if (favorites.includes(id)) {
                    svg.setAttribute('fill', 'currentColor');
                    svg.classList.remove('text-gray-400');
                    svg.classList.add('text-red-500');
                }
            });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('sortSelect').value = 'default';
            filterCategory('all');
            searchWahana();
        }

        function updateResultsCount(count, category = 'all') {
            const resultsCount = document.getElementById('resultsCount');
            const totalCount = document.getElementById('totalCount');
            
            if (category === 'all') {
                resultsCount.textContent = `Menampilkan ${count} wahana`;
            } else {
                resultsCount.textContent = `Menampilkan ${count} ${category}`;
            }
            
            if (totalCount) {
                totalCount.textContent = count;
            }
        }

        function toggleEmptyState(show) {
            const emptyState = document.getElementById('emptyState');
            const grid = document.getElementById('wahanaGrid');
            
            if (show) {
                emptyState.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                emptyState.classList.add('hidden');
                grid.classList.remove('hidden');
            }
        }

        function setView(view) {
            currentView = view;
            const grid = document.getElementById('wahanaGrid');
            const buttons = document.querySelectorAll('.view-btn');

            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.view === view) {
                    btn.classList.add('active');
                }
            });

            if (view === 'list') {
                grid.classList.remove('lg:grid-cols-3', 'sm:grid-cols-2');
                grid.classList.add('grid-cols-1');
            } else {
                grid.classList.remove('grid-cols-1');
                grid.classList.add('sm:grid-cols-2', 'lg:grid-cols-3');
            }
        }

        // Smooth scroll to top
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Show scroll indicator
        window.addEventListener('scroll', function() {
            const scrolled = window.scrollY > 300;
            // You can add a scroll-to-top button here if needed
        });
    </script>
</body>
</html>