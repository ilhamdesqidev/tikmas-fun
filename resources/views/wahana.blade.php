<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mestakara</title>
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
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="w-full py-3 sm:py-5 px-4 sm:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
        <a href="#" class="text-xl sm:text-3xl font-bold text-black italic">
            Mesta<span class="text-primary">Kara</span>.
        </a>
        
        <a href="{{ route('home') }}" class="bg-primary text-black px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg hover:bg-yellow-500 transition-colors duration-300 font-medium text-sm sm:text-base">
            Back to Dashboard
        </a>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 sm:pt-24 px-4 sm:px-7 pb-8 sm:pb-12">
        <h1 class="text-2xl sm:text-4xl font-bold text-center mb-6 sm:mb-8">Wahana Kami</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($facilities as $facility)
            <a href="{{ route('wahana.show', $facility->id) }}" class="group block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-primary">
                <!-- Gambar Wahana Full Card -->
                <div class="relative h-64 sm:h-80 overflow-hidden">
                    <img src="{{ asset('storage/' . $facility->image) }}" 
                         alt="{{ $facility->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <!-- Overlay dengan nama wahana -->
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end p-4 sm:p-6">
                        <div class="text-white">
                            <h3 class="text-xl sm:text-2xl font-bold mb-2 drop-shadow-lg">{{ $facility->name }}</h3>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
            
            @if($facilities->count() == 0)
            <div class="col-span-full text-center py-8 sm:py-12">
                <div class="bg-white rounded-xl shadow-md p-6 sm:p-8">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-base sm:text-lg">Belum ada wahana yang tersedia.</p>
                </div>
            </div>
            @endif
        </div>
    </main>
</body>
</html>