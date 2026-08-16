<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | AutoRent</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-900">
    <!-- Background Image with Overlay -->
    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1503376760367-13eea3d452fc?q=80&w=2070&auto=format&fit=crop" alt="Background" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 to-gray-900/90"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <!-- Logo -->
        <div class="mb-8 transform transition hover:scale-105 duration-300">
            <a href="/" class="text-4xl font-bold tracking-tight text-white flex items-center gap-2">
                <svg class="w-10 h-10 text-primary-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21.707 11.293l-8-8A1 1 0 0 0 12 3v18a1 1 0 0 0 1.707.707l8-8a1 1 0 0 0 0-1.414zM10 21V3a1 1 0 0 0-1.707-.707l-8 8a1 1 0 0 0 0 1.414l8 8A1 1 0 0 0 10 21z"/></svg>
                Auto<span class="text-primary-500">Rent</span>
            </a>
        </div>

        <!-- Glassmorphism Card -->
        <div class="w-full sm:max-w-md p-8 glass-dark rounded-2xl border border-gray-700/50 shadow-2xl backdrop-blur-xl">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Selamat Datang Kembali</h2>
            
            <x-auth-session-status class="mb-4 text-green-400 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="pl-10 block w-full bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:border-primary-500 focus:ring-primary-500 transition-colors sm:text-sm py-3" placeholder="admin@rentalmobil.test">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="pl-10 block w-full bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:border-primary-500 focus:ring-primary-500 transition-colors sm:text-sm py-3" placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-600 rounded bg-gray-800">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-400">
                            Ingat saya
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-primary-500 hover:text-primary-400 transition-colors">
                                Lupa password?
                            </a>
                        </div>
                    @endif
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-gray-900 transition-all duration-200 hover:shadow-primary-500/30 hover:-translate-y-0.5">
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} AutoRent. Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
