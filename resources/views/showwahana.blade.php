<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wahana - Mestakara</title>
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        .sticky-nav {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .slider-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
        }
        .slider-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slider-item {
            min-width: 100%;
            flex-shrink: 0;
        }
        .thumbnail {
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0.6;
        }
        .thumbnail.active {
            opacity: 1;
            border-color: #CFD916;
        }
        .thumbnail:hover {
            opacity: 1;
        }
        /* Disable image download */
        img {
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
        }
        .slider-item img {
            pointer-events: auto;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        .close-modal {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
        }
        .modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }
        .modal-nav:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }
        .modal-prev {
            left: 20px;
        }
        .modal-next {
            right: 20px;
        }
    </style>
</head>
<body class="bg-gray-50" oncontextmenu="return false;">
    <!-- Enhanced Navbar with Blur Effect -->
    <nav class="w-full py-3 sm:py-4 px-4 sm:px-7 flex items-center justify-between sticky-nav border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
        <a href="#" class="text-xl sm:text-3xl font-bold text-black italic">
            Mesta<span class="text-primary">Kara</span>.
        </a>
        
        <div class="flex items-center space-x-2 sm:space-x-4">
            <a href="{{ route('wahana.index') }}" class="bg-gray-100 text-gray-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg hover:bg-gray-200 transition-all duration-300 font-medium text-xs sm:text-base flex items-center gap-1 sm:gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
            <a href="{{ route('home') }}" class="bg-primary text-black px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg hover:bg-yellow-500 transition-all duration-300 font-medium text-xs sm:text-base shadow-sm">
                Dashboard
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 sm:pt-24 px-4 sm:px-7 pb-8 sm:pb-12">
        <div class="max-w-6xl mx-auto">
            <!-- Enhanced Breadcrumb -->
            <nav class="mb-4 sm:mb-6 fade-in">
                <ol class="flex items-center space-x-2 text-xs sm:text-sm text-gray-500 bg-white px-4 py-2 rounded-lg shadow-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Dashboard</a></li>
                    <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li><a href="{{ route('wahana.index') }}" class="hover:text-primary transition-colors">Wahana</a></li>
                    <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li class="text-gray-900 font-medium">{{ $facility->name }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Image & Gallery -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Main Image Slider -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden fade-in">
                        <div class="slider-container relative">
                            <div class="slider-track" id="sliderTrack">
                                <!-- Main Image -->
                                <div class="slider-item relative">
                                    <img src="{{ asset('storage/' . $facility->image) }}" 
                                         alt="{{ $facility->name }}" 
                                         class="w-full h-64 sm:h-96 object-cover">
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-primary text-black text-xs sm:text-sm font-bold px-3 py-1.5 rounded-full shadow-lg">
                                            {{ ucfirst($facility->category) }} Unggulan
                                        </span>
                                    </div>
                                    <button onclick="openModal('{{ asset('storage/' . $facility->image) }}', 0)" class="absolute bottom-4 right-4 bg-white bg-opacity-90 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium hover:bg-opacity-100 transition-all shadow-lg flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                        </svg>
                                        Perbesar
                                    </button>
                                </div>
                                
                                <!-- Gallery Images -->
                                @if($facility->gallery_images && count($facility->gallery_images) > 0)
                                    @foreach($facility->gallery_images as $index => $galleryImage)
                                    <div class="slider-item relative">
                                        <img src="{{ asset('storage/' . $galleryImage) }}" 
                                             alt="{{ $facility->name }} - View {{ $index + 1 }}" 
                                             class="w-full h-64 sm:h-96 object-cover">
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-primary text-black text-xs sm:text-sm font-bold px-3 py-1.5 rounded-full shadow-lg">
                                                Gallery {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <button onclick="openModal('{{ asset('storage/' . $galleryImage) }}', {{ $index + 1 }})" class="absolute bottom-4 right-4 bg-white bg-opacity-90 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium hover:bg-opacity-100 transition-all shadow-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                            Perbesar
                                        </button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <!-- Navigation Buttons -->
                            <button onclick="previousSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 text-gray-800 p-2 rounded-full hover:bg-opacity-100 transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 text-gray-800 p-2 rounded-full hover:bg-opacity-100 transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            
                            <!-- Slide Counter -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                                <span id="currentSlide">1</span> / <span id="totalSlides">
                                    @if($facility->gallery_images && count($facility->gallery_images) > 0)
                                        {{ count($facility->gallery_images) + 1 }}
                                    @else
                                        1
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Thumbnails -->
                        <div class="p-4 bg-gray-50">
                            <div class="flex space-x-2 overflow-x-auto pb-2">
                                <!-- Main Image Thumbnail -->
                                <div class="thumbnail flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-primary active" onclick="goToSlide(0)">
                                    <img src="{{ asset('storage/' . $facility->image) }}" 
                                         alt="{{ $facility->name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                
                                <!-- Gallery Thumbnails -->
                                @if($facility->gallery_images && count($facility->gallery_images) > 0)
                                    @foreach($facility->gallery_images as $index => $galleryImage)
                                    <div class="thumbnail flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-300" onclick="goToSlide({{ $index + 1 }})">
                                        <img src="{{ asset('storage/' . $galleryImage) }}" 
                                             alt="{{ $facility->name }} - View {{ $index + 1 }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tabbed Content Section -->
                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 fade-in">
                        <!-- Tabs -->
                        <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
                            <button onclick="switchTab('deskripsi')" class="tab-button px-4 py-3 font-medium text-sm sm:text-base border-b-2 border-primary text-primary whitespace-nowrap" data-tab="deskripsi">
                                Deskripsi
                            </button>
                            <button onclick="switchTab('ketentuan')" class="tab-button px-4 py-3 font-medium text-sm sm:text-base border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="ketentuan">
                                Ketentuan
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div id="deskripsi" class="tab-content active">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Tentang {{ $facility->name }}</h2>
                            <div class="text-gray-700 leading-relaxed text-sm sm:text-base">
                                {!! nl2br(e($facility->description)) !!}
                            </div>
                        </div>

                        <div id="ketentuan" class="tab-content">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Ketentuan & Peraturan</h2>
                            <div class="space-y-3">
                                <div class="flex gap-3 p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm sm:text-base text-gray-700">Pengunjung wajib mengikuti instruksi operator</span>
                                </div>
                                <div class="flex gap-3 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                                    <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm sm:text-base text-gray-700">Perhatikan kenyamanan dan keselamatan anda</span>
                                </div>
                                <div class="flex gap-3 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm sm:text-base text-gray-700">Tetap jaga kebersihan lingkungan wisata</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Info Card -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        <!-- Quick Info Card -->
                        <div class="bg-white rounded-xl shadow-lg p-6 fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">Informasi Wahana</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-primary bg-opacity-20 rounded-lg">
                                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium mb-1">Durasi</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $facility->duration }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-primary bg-opacity-20 rounded-lg">
                                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium mb-1">Batasan Usia</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $facility->age_range }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-primary bg-opacity-20 rounded-lg">
                                        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium mb-1">Kategori</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ ucfirst($facility->category) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rating & Review Card -->
                        <div class="bg-gradient-to-br from-primary to-yellow-400 rounded-xl shadow-lg p-6 text-black fade-in">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold">Rating Pengunjung</h3>
                                <div class="flex items-center gap-1">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-2xl font-bold">4.8</span>
                                </div>
                            </div>
                            <div class="flex gap-1 mb-2">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current opacity-30" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <p class="text-sm opacity-90">Berdasarkan 1,234 ulasan</p>
                        </div>

                        <!-- Share Button -->
                        <button onclick="shareWahana()" class="w-full bg-white text-gray-800 px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300 font-medium flex items-center justify-center gap-2 shadow-md fade-in">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Bagikan Wahana
                        </button>

                        <!-- Contact Card -->
                        <div class="bg-white rounded-xl shadow-lg p-6 fade-in">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Butuh Bantuan?</h3>
                            <p class="text-sm text-gray-600 mb-4">Hubungi kami untuk informasi lebih lanjut atau reservasi khusus.</p>
                            <a href="tel:+628112051616" class="w-full bg-primary text-black px-6 py-3 rounded-lg hover:bg-yellow-500 transition-all duration-300 font-medium flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <button class="modal-nav modal-prev" onclick="modalPrev()">&#10094;</button>
        <button class="modal-nav modal-next" onclick="modalNext()">&#10095;</button>
        <img class="modal-content" id="modalImage" src="">
    </div>

    <!-- Floating Action Button for Quick Actions -->
    <div class="fixed bottom-6 right-6 z-40 flex flex-col gap-3" style="opacity: 0; pointer-events: none; transition: opacity 0.3s;">
        <button onclick="scrollToTop()" class="bg-primary text-black p-3 rounded-full shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>

    <script>
        // Slider functionality
        let currentSlide = 0;
        let totalSlides = document.querySelectorAll('.slider-item').length;
        const sliderTrack = document.getElementById('sliderTrack');
        const thumbnails = document.querySelectorAll('.thumbnail');
        let modalCurrentIndex = 0;
        const allImages = [
            '{{ asset('storage/' . $facility->image) }}',
            @if($facility->gallery_images && count($facility->gallery_images) > 0)
                @foreach($facility->gallery_images as $galleryImage)
                    '{{ asset('storage/' . $galleryImage) }}',
                @endforeach
            @endif
        ];

        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable keyboard shortcuts for saving images
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
            }
        });

        function updateSlider() {
            sliderTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
            document.getElementById('currentSlide').textContent = currentSlide + 1;
            
            // Update thumbnails
            thumbnails.forEach((thumb, index) => {
                if (index === currentSlide) {
                    thumb.classList.add('active', 'border-primary');
                    thumb.classList.remove('border-gray-300');
                } else {
                    thumb.classList.remove('active', 'border-primary');
                    thumb.classList.add('border-gray-300');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function previousSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-primary', 'text-primary');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            document.getElementById(tabName).classList.add('active');
            
            const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-primary', 'text-primary');
        }

        function shareWahana() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $facility->name }} - Mestakara',
                    text: 'Lihat wahana menarik ini di Mestakara!',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link berhasil disalin ke clipboard!');
                });
            }
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Modal functionality
        function openModal(imageSrc, index) {
            modalCurrentIndex = index;
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function modalNext() {
            modalCurrentIndex = (modalCurrentIndex + 1) % allImages.length;
            document.getElementById('modalImage').src = allImages[modalCurrentIndex];
        }

        function modalPrev() {
            modalCurrentIndex = (modalCurrentIndex - 1 + allImages.length) % allImages.length;
            document.getElementById('modalImage').src = allImages[modalCurrentIndex];
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Keyboard navigation for modal
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('imageModal').style.display === 'flex') {
                if (e.key === 'Escape') {
                    closeModal();
                } else if (e.key === 'ArrowRight') {
                    modalNext();
                } else if (e.key === 'ArrowLeft') {
                    modalPrev();
                }
            }
        });

        // Auto slide every 5 seconds
        let autoSlideInterval = setInterval(nextSlide, 5000);

        // Pause auto slide on hover
        sliderTrack.addEventListener('mouseenter', () => {
            clearInterval(autoSlideInterval);
        });

        sliderTrack.addEventListener('mouseleave', () => {
            autoSlideInterval = setInterval(nextSlide, 5000);
        });

        // Touch/Swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        sliderTrack.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            clearInterval(autoSlideInterval);
        });

        sliderTrack.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            autoSlideInterval = setInterval(nextSlide, 5000);
        });

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                nextSlide();
            }
            if (touchEndX > touchStartX + 50) {
                previousSlide();
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                previousSlide();
            } else if (e.key === 'ArrowRight') {
                nextSlide();
            }
        });

        // Add fade-in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            observer.observe(el);
        });

        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            const scrollButton = document.querySelector('.fixed.bottom-6');
            if (window.scrollY > 300) {
                scrollButton.style.opacity = '1';
                scrollButton.style.pointerEvents = 'auto';
            } else {
                scrollButton.style.opacity = '0';
                scrollButton.style.pointerEvents = 'none';
            }
        });

        // Initialize total slides
        document.getElementById('totalSlides').textContent = totalSlides;
    </script>
</body>
</html>