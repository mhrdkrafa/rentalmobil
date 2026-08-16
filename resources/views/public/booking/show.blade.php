@extends('layouts.public')

@section('title', 'Ringkasan Pemesanan | AutoRent')

@section('content')
<div class="pt-24 pb-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                        <p class="text-sm text-green-700 mt-1">Silakan selesaikan pembayaran DP agar pesanan Anda tidak dibatalkan otomatis.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-primary-600 px-8 py-6 text-white flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold">Detail Pesanan</h1>
                    <p class="text-primary-100 mt-1">Kode: <span class="font-mono bg-primary-700 px-2 py-1 rounded">{{ $booking->booking_code }}</span></p>
                </div>
                <div>
                    @if($booking->status->value === 'pending')
                        <span class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-full font-bold text-sm shadow-sm">Menunggu Pembayaran</span>
                    @elseif($booking->status->value === 'confirmed')
                        <span class="px-4 py-2 bg-green-400 text-green-900 rounded-full font-bold text-sm shadow-sm">Terkonfirmasi</span>
                    @else
                        <span class="px-4 py-2 bg-gray-400 text-gray-900 rounded-full font-bold text-sm shadow-sm">{{ ucfirst($booking->status->value) }}</span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <!-- Vehicle Info -->
                <div class="flex items-center space-x-6 border-b border-gray-100 pb-8 mb-8">
                    @if($booking->vehicle->images->count() > 0)
                        <img src="{{ asset('storage/' . $booking->vehicle->images->first()->image_path) }}" alt="{{ $booking->vehicle->name }}" class="w-32 h-24 object-cover rounded-xl shadow-sm">
                    @else
                        <div class="w-32 h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $booking->vehicle->name }}</h2>
                        <p class="text-gray-500">{{ $booking->vehicle->category->name }} &bull; {{ $booking->with_driver ? 'Dengan Supir' : 'Lepas Kunci' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Customer Data -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-primary-500 pl-3">Data Pemesan</h3>
                        <ul class="space-y-3 text-sm">
                            <li class="flex justify-between"><span class="text-gray-500">Nama</span> <span class="font-medium text-gray-900">{{ $booking->customer->name }}</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">No. WhatsApp</span> <span class="font-medium text-gray-900">{{ $booking->customer->phone }}</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">No. KTP</span> <span class="font-medium text-gray-900">{{ $booking->customer->id_card_number }}</span></li>
                        </ul>
                    </div>

                    <!-- Booking Data -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-primary-500 pl-3">Waktu Sewa</h3>
                        <ul class="space-y-3 text-sm">
                            <li class="flex justify-between"><span class="text-gray-500">Mulai</span> <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->start_date)->translatedFormat('d F Y') }}</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">Selesai</span> <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->end_date)->translatedFormat('d F Y') }}</span></li>
                            <li class="flex justify-between"><span class="text-gray-500">Durasi</span> <span class="font-medium text-gray-900">{{ $booking->total_days }} Hari</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Price Details -->
                <div class="mt-8 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian Biaya</h3>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between"><span class="text-gray-600">Harga per Hari</span> <span class="font-medium">Rp {{ number_format($booking->price_per_day, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Total Durasi</span> <span class="font-medium">{{ $booking->total_days }} Hari</span></div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex justify-between items-center mb-4">
                        <span class="text-gray-900 font-bold">Total Harga</span>
                        <span class="text-xl font-bold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>

                    @if($booking->payment_status->value === 'unpaid')
                        <div class="bg-blue-100 border border-blue-200 rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <span class="text-blue-900 font-bold block">DP Minimum yang Harus Dibayar</span>
                                <span class="text-blue-700 text-xs">Batas waktu: 24 Jam sejak pemesanan</span>
                            </div>
                            <span class="text-2xl font-extrabold text-blue-800">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                @if($booking->payment_status->value === 'unpaid')
                    <div class="mt-8 text-center">
                        <button onclick="alert('Integrasi Payment Gateway akan dilakukan pada Fase 7.')" class="w-full md:w-auto px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                            Bayar Sekarang (Simulasi Gateway)
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
