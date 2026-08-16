@extends('layouts.public')

@section('title', $vehicle->name . ' | AutoRent')

@section('content')
<div class="pt-24 pb-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('public.home') }}" class="hover:text-primary-600 transition">Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('public.catalog.index') }}" class="ml-1 hover:text-primary-600 md:ml-2 transition">Katalog</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-gray-700 md:ml-2" aria-current="page">{{ $vehicle->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Image Gallery -->
                <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100" x-data="{ mainImage: '{{ $vehicle->images->count() > 0 ? asset('storage/' . $vehicle->images->first()->image_path) : '' }}' }">
                    <div class="h-64 md:h-96 rounded-2xl overflow-hidden bg-gray-100 mb-4 relative">
                        <template x-if="mainImage">
                            <img :src="mainImage" class="w-full h-full object-cover" alt="Main Vehicle Image">
                        </template>
                        <template x-if="!mainImage">
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </template>
                    </div>
                    @if($vehicle->images->count() > 1)
                        <div class="flex space-x-3 overflow-x-auto pb-2">
                            @foreach($vehicle->images as $image)
                                <button @click="mainImage = '{{ asset('storage/' . $image->image_path) }}'" class="h-20 w-24 flex-shrink-0 rounded-xl overflow-hidden border-2 focus:outline-none transition" :class="mainImage === '{{ asset('storage/' . $image->image_path) }}' ? 'border-primary-500 opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Specs & Description -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <div class="mb-6 pb-6 border-b border-gray-100 flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle->name }}</h1>
                            <p class="text-gray-500 mt-1">{{ $vehicle->year }} &bull; {{ $vehicle->category->name }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            Available
                        </span>
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Spesifikasi Kendaraan</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="flex flex-col items-center p-4 bg-gray-50 rounded-2xl">
                            <svg class="w-8 h-8 text-primary-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm text-gray-500">Kapasitas</span>
                            <span class="font-semibold text-gray-900">{{ $vehicle->capacity }} Kursi</span>
                        </div>
                        <div class="flex flex-col items-center p-4 bg-gray-50 rounded-2xl">
                            <svg class="w-8 h-8 text-primary-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm text-gray-500">Transmisi</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($vehicle->transmission->value) }}</span>
                        </div>
                        <div class="flex flex-col items-center p-4 bg-gray-50 rounded-2xl">
                            <svg class="w-8 h-8 text-primary-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span class="text-sm text-gray-500">Bahan Bakar</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($vehicle->fuel_type->value) }}</span>
                        </div>
                        <div class="flex flex-col items-center p-4 bg-gray-50 rounded-2xl">
                            <svg class="w-8 h-8 text-primary-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span class="text-sm text-gray-500">Deposit</span>
                            <span class="font-semibold text-gray-900">{{ $vehicle->deposit_amount > 0 ? 'Wajib' : 'Tidak' }}</span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-4">Deskripsi</h2>
                    <div class="prose prose-blue text-gray-600 max-w-none">
                        {{ $vehicle->description ?? 'Deskripsi kendaraan tidak tersedia.' }}
                    </div>
                </div>

                <!-- Reviews -->
                @php
                    $publishedReviews = \App\Models\Review::with('customer')
                        ->where('vehicle_id', $vehicle->id)
                        ->where('is_published', true)
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                @if($publishedReviews->count() > 0)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 mt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Ulasan Pelanggan</h2>
                    <div class="space-y-6">
                        @foreach($publishedReviews as $review)
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                                    {{ substr($review->customer->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-gray-900">{{ $review->customer->name }}</h4>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex text-yellow-400 text-sm my-1">
                                    @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                    @for($i = $review->rating; $i < 5; $i++) ☆ @endfor
                                </div>
                                @if($review->comment)
                                    <p class="text-gray-600 mt-2 text-sm">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Booking Widget & Calendar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 sticky top-28">
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Harga Sewa Mulai</span>
                        <div class="text-3xl font-extrabold text-primary-600 mt-1">
                            Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}<span class="text-base font-normal text-gray-400">/hari</span>
                        </div>
                        @if($vehicle->price_per_day_with_driver)
                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Tersedia dengan Supir (+Rp {{ number_format($vehicle->price_per_day_with_driver - $vehicle->price_per_day, 0, ',', '.') }})
                            </p>
                        @endif
                    </div>

                    <!-- Alpine Calendar Component -->
                    <div x-data="availabilityCalendar({{ $vehicle->id }})" class="mb-6">
                        <h3 class="text-md font-bold text-gray-900 mb-4 flex justify-between items-center">
                            <span>Cek Ketersediaan</span>
                            <div class="flex items-center space-x-2">
                                <button @click="prevMonth" class="p-1 rounded-full hover:bg-gray-100 text-gray-600 focus:outline-none"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                                <span class="text-sm font-medium w-24 text-center" x-text="monthName + ' ' + year"></span>
                                <button @click="nextMonth" class="p-1 rounded-full hover:bg-gray-100 text-gray-600 focus:outline-none"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                            </div>
                        </h3>

                        <div class="relative min-h-[220px]">
                            <!-- Loader -->
                            <div x-show="loading" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl">
                                <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
                            </div>

                            <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2">
                                <div class="text-gray-400 font-medium">Su</div>
                                <div class="text-gray-400 font-medium">Mo</div>
                                <div class="text-gray-400 font-medium">Tu</div>
                                <div class="text-gray-400 font-medium">We</div>
                                <div class="text-gray-400 font-medium">Th</div>
                                <div class="text-gray-400 font-medium">Fr</div>
                                <div class="text-gray-400 font-medium">Sa</div>
                            </div>
                            
                            <div class="grid grid-cols-7 gap-1">
                                <template x-for="blank in blankDays">
                                    <div class="h-8 w-full"></div>
                                </template>
                                
                                <template x-for="date in daysInMonth" :key="date">
                                    <div class="h-8 w-full flex items-center justify-center rounded-lg text-sm transition-colors cursor-default"
                                         :class="{
                                            'bg-green-100 text-green-800': isAvailable(date),
                                            'bg-red-100 text-red-800 opacity-50 line-through': isBooked(date),
                                            'bg-gray-200 text-gray-500 opacity-50': isPast(date)
                                         }">
                                        <span x-text="date"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4 mt-4 text-xs text-gray-500 justify-center">
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-100 mr-1 block"></span> Tersedia</div>
                            <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-100 mr-1 block"></span> Terisi</div>
                        </div>
                    </div>

                    <a href="{{ route('public.booking.create', $vehicle) }}" class="w-full block text-center py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        Pesan Kendaraan Ini
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('availabilityCalendar', (vehicleId) => ({
            vehicleId: vehicleId,
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            daysInMonth: [],
            blankDays: [],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            bookedData: {}, // Format: {'2026-08-15': 'booked'}
            loading: false,

            init() {
                this.renderCalendar();
            },

            get monthName() {
                return this.monthNames[this.month];
            },

            renderCalendar() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                
                let blankdaysArray = [];
                for (var i = 1; i <= dayOfWeek; i++) {
                    blankdaysArray.push(i);
                }
                
                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }
                
                this.blankDays = blankdaysArray;
                this.daysInMonth = daysArray;

                this.fetchAvailability();
            },

            async fetchAvailability() {
                this.loading = true;
                try {
                    const response = await fetch(`/api/availability/${this.vehicleId}?year=${this.year}&month=${this.month + 1}`);
                    const data = await response.json();
                    this.bookedData = data.booked_dates || {};
                } catch (error) {
                    console.error("Failed to fetch availability", error);
                } finally {
                    this.loading = false;
                }
            },

            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.renderCalendar();
            },

            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.renderCalendar();
            },

            getFormattedDate(date) {
                return `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
            },

            isPast(date) {
                const today = new Date();
                today.setHours(0,0,0,0);
                const checkDate = new Date(this.year, this.month, date);
                return checkDate < today;
            },

            isBooked(date) {
                if (this.isPast(date)) return false;
                const formatted = this.getFormattedDate(date);
                return !!this.bookedData[formatted];
            },

            isAvailable(date) {
                if (this.isPast(date)) return false;
                return !this.isBooked(date);
            }
        }));
    });
</script>
@endsection
