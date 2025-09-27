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
    <nav class="w-full py-5 px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
        <a href="#" class="text-3xl font-bold text-black italic">
            Mesta<span class="text-primary">Kara</span>.
        </a>
        
        <a href="{{ route('home') }}" class="bg-primary text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors duration-300 font-medium">
            Back to Dashboard
        </a>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 px-7 pb-12">
        <h1 class="text-4xl font-bold text-center mb-8">Wahana Kami</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($facilities as $facility)
            <a href="{{ route('wahana.show', $facility->id) }}" class="group block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer border-2 border-transparent hover:border-primary">
                <!-- Gambar Wahana Full Card -->
                <div class="relative h-80 overflow-hidden">
                    <img src="{{ asset('storage/' . $facility->image) }}" 
                         alt="{{ $facility->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <!-- Overlay dengan nama wahana -->
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end p-6">
                        <div class="text-white">
                            <h3 class="text-2xl font-bold mb-2 drop-shadow-lg">{{ $facility->name }}</h3>

                        </div>
                    </div>
                </div>
            </a>
            @endforeach
            
            @if($facilities->count() == 0)
            <div class="col-span-full text-center py-12">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada wahana yang tersedia.</p>
                </div>
            </div>
            @endif
        </div>
    </main>
</body>
</html>