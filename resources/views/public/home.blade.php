@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gray-900 h-screen flex items-center justify-center">
        <!-- Abstract Animated Background -->
        <div class="absolute inset-0 w-full h-full overflow-hidden z-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-primary-500 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fade-in-up">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight mb-6">
                Jelajahi Kota Tanpa Batas<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-blue-200">Dengan Kenyamanan Premium</span>
            </h1>
            <p class="mt-4 text-xl md:text-2xl text-gray-300 max-w-3xl mx-auto mb-10">
                Pesan mobil impian Anda sekarang. Pilihan lengkap, harga transparan, dan pelayanan 24 jam.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="{{ route('public.catalog.index') }}" class="px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-full text-lg shadow-[0_0_20px_rgba(37,99,235,0.4)] hover:shadow-[0_0_30px_rgba(37,99,235,0.6)] transition-all duration-300 transform hover:-translate-y-1">
                    Cari Mobil Sekarang
                </a>
                <a href="#features" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white font-bold rounded-full text-lg border border-white/20 transition-all duration-300">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Mengapa Memilih AutoRent?</h2>
                <p class="mt-4 text-lg text-gray-500">Kami menawarkan lebih dari sekadar kendaraan, kami menawarkan pengalaman.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="p-8 rounded-3xl bg-gray-50 hover:bg-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600 leading-relaxed">Seluruh armada kami dirawat secara berkala dan dilindungi asuransi komprehensif untuk ketenangan perjalanan Anda.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-8 rounded-3xl bg-gray-50 hover:bg-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pemesanan Instan</h3>
                    <p class="text-gray-600 leading-relaxed">Sistem booking kami dirancang cepat dan responsif. Pilih mobil, atur tanggal, dan bayar dalam hitungan menit.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-3xl bg-gray-50 hover:bg-white hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-transparent hover:border-gray-100 group">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Dukungan 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">Tim customer service kami selalu siap membantu Anda kapan pun diperlukan, siang maupun malam.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Fleet -->
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Koleksi Terpopuler</h2>
                    <p class="mt-2 text-gray-500">Mobil favorit pelanggan kami minggu ini.</p>
                </div>
                <a href="{{ route('public.catalog.index') }}" class="hidden sm:inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 transition">
                    Lihat Semua <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredVehicles as $vehicle)
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col">
                        <div class="relative h-60 overflow-hidden">
                            @if($vehicle->images->count() > 0)
                                <img src="{{ asset('storage/' . $vehicle->images->first()->image_path) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-900">
                                {{ $vehicle->category->name }}
                            </div>
                        </div>
                        <div class="p-6 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $vehicle->name }}</h3>
                                <p class="text-gray-500 text-sm mb-4">{{ ucfirst($vehicle->transmission->value) }} &bull; {{ $vehicle->capacity }} Kursi</p>
                            </div>
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <span class="text-sm text-gray-500">Mulai dari</span>
                                    <div class="text-lg font-bold text-primary-600">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span class="text-sm text-gray-400 font-normal">/hari</span></div>
                                </div>
                                <a href="{{ route('public.catalog.show', $vehicle->id) }}" class="bg-gray-900 hover:bg-primary-600 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        Belum ada armada yang tersedia.
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('public.catalog.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 w-full transition">
                    Lihat Semua Armada
                </a>
            </div>
        </div>
    </div>
@endsection
