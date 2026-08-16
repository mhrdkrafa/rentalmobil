@extends('layouts.public')

@section('title', 'Cek Status Pesanan | AutoRent')

@section('content')
<div class="pt-24 pb-12 min-h-screen bg-cover bg-center relative" style="background-image: url('https://images.unsplash.com/photo-1503371471593-9462215c0e0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

    <div class="relative max-w-lg mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="glass-dark rounded-3xl p-8 shadow-2xl border border-white/10">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-600 mb-4 shadow-[0_0_20px_rgba(37,99,235,0.4)]">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Cek Status Pesanan</h1>
                <p class="text-gray-300 text-sm">Masukkan Kode Booking dan Nomor WhatsApp yang Anda gunakan saat memesan.</p>
            </div>

            @if(session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/50 p-4 rounded-xl backdrop-blur-md">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-red-200">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('public.booking.check.process') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Kode Booking</label>
                        <input type="text" name="booking_code" value="{{ old('booking_code') }}" placeholder="Contoh: RC-20260815-A1B2" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nomor WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition" required>
                    </div>

                    <button type="submit" class="w-full py-4 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(37,99,235,0.5)] transition-all duration-300 transform hover:-translate-y-1 mt-4">
                        Cari Pesanan
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">Belum punya pesanan? <a href="{{ route('public.catalog.index') }}" class="text-primary-400 hover:text-primary-300 font-semibold transition">Pesan Sekarang</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
