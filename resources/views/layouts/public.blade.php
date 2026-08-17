<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Rental Mobil')</title>

    <!-- Meta Tags for SEO -->
    <meta name="description" content="@yield('meta_description', 'Sewa mobil terbaik dengan harga terjangkau dan pelayanan premium.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Extra Styles -->
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased overflow-x-hidden" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navbar -->
    <nav :class="{ 'glass shadow-md py-3': scrolled, 'bg-transparent py-5': !scrolled }" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('public.home') }}" class="text-2xl font-bold tracking-tight text-primary-600">
                        <span class="text-gray-900">Auto</span>Rent
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('public.home') }}" class="text-gray-700 hover:text-primary-600 font-medium transition duration-150 ease-in-out">Beranda</a>
                    <a href="{{ route('public.catalog.index') }}" class="text-gray-700 hover:text-primary-600 font-medium transition duration-150 ease-in-out">Armada</a>
                    <a href="{{ route('public.booking.check') }}" class="text-gray-700 hover:text-primary-600 font-medium transition duration-150 ease-in-out">Cek Pesanan</a>
                    <a href="{{ route('public.pages.contact') }}" class="text-gray-700 hover:text-primary-600 font-medium transition duration-150 ease-in-out">Kontak</a>
                    
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-full font-semibold transition duration-150 ease-in-out shadow-lg hover:shadow-primary-500/50">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-full font-semibold transition duration-150 ease-in-out shadow-lg hover:shadow-primary-500/50">Login</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-700 hover:text-primary-600 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden glass absolute top-full left-0 w-full shadow-lg" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('public.home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:text-primary-600 hover:bg-gray-50">Beranda</a>
                <a href="{{ route('public.catalog.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:text-primary-600 hover:bg-gray-50">Armada</a>
                <a href="{{ route('public.booking.check') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:text-primary-600 hover:bg-gray-50">Cek Pesanan</a>
                <a href="{{ route('public.pages.contact') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 hover:text-primary-600 hover:bg-gray-50">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ route('public.home') }}" class="text-3xl font-bold tracking-tight text-white mb-4 block">
                        Auto<span class="text-primary-500">Rent</span>
                    </a>
                    <p class="text-gray-400 mb-6 max-w-sm">
                        Kami menyediakan layanan sewa mobil terbaik dengan armada terbaru, harga kompetitif, dan pelayanan pelanggan 24/7.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.999 7.377a4.623 4.623 0 1 0 0 9.248 4.623 4.623 0 0 0 0-9.248zm0 7.627a3.004 3.004 0 1 1 0-6.008 3.004 3.004 0 0 1 0 6.008z"/><circle cx="16.806" cy="7.207" r="1.078"/><path d="M20.533 6.111A4.605 4.605 0 0 0 17.9 3.479a6.606 6.606 0 0 0-2.186-.42c-.963-.042-1.268-.054-3.71-.054s-2.755 0-3.71.054a6.554 6.554 0 0 0-2.184.42 4.6 4.6 0 0 0-2.633 2.632 6.585 6.585 0 0 0-.419 2.186c-.043.962-.056 1.267-.056 3.71 0 2.442 0 2.753.056 3.71.015.348.072.686.168 1.005A4.611 4.611 0 0 0 5.86 19.33a6.627 6.627 0 0 0 2.186.419c.963.042 1.268.055 3.71.055s2.755 0 3.71-.055a6.615 6.615 0 0 0 2.186-.419 4.613 4.613 0 0 0 2.633-2.633c.266-.83.39-1.724.419-2.186.043-.962.056-1.267.056-3.71s0-2.753-.056-3.71a6.581 6.581 0 0 0-.421-2.189zm-1.218 7.32a5.543 5.543 0 0 1-.341 1.815 2.992 2.992 0 0 1-1.712 1.712 5.566 5.566 0 0 1-1.815.341c-.958.043-1.233.052-3.648.052s-2.69 0-3.648-.052a5.542 5.542 0 0 1-1.814-.341 2.99 2.99 0 0 1-1.712-1.712 5.546 5.546 0 0 1-.341-1.815c-.043-.958-.052-1.233-.052-3.648s0-2.69.052-3.648a5.56 5.56 0 0 1 .341-1.814 2.99 2.99 0 0 1 1.712-1.712 5.546 5.546 0 0 1 1.814-.341c.958-.043 1.234-.052 3.648-.052s2.69 0 3.648.052a5.558 5.558 0 0 1 1.815.341 2.99 2.99 0 0 1 1.712 1.712 5.561 5.561 0 0 1 .341 1.814c.043.958.052 1.234.052 3.648s0 2.69-.052 3.648z"/></svg></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-primary-400 transition">Sewa Harian</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-400 transition">Sewa Bulanan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-400 transition">Sewa dengan Supir</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-400 transition">Antar Jemput Bandara</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start"><svg class="h-5 w-5 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Jl. Merdeka No. 123, Jakarta</li>
                        <li class="flex items-center"><svg class="h-5 w-5 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> +62 812-3456-7890</li>
                        <li class="flex items-center"><svg class="h-5 w-5 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> info@autorent.com</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} AutoRent. All rights reserved.
                </p>
                <div class="space-x-4 mt-4 md:mt-0 text-sm">
                    <a href="{{ route('public.pages.terms') }}" class="text-gray-500 hover:text-white transition">Kebijakan Privasi</a>
                    <a href="{{ route('public.pages.terms') }}" class="text-gray-500 hover:text-white transition">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
