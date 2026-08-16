@extends('layouts.public')

@section('title', 'Booking ' . $vehicle->name . ' | AutoRent')

@section('content')
<div class="pt-24 pb-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Formulir Booking</h1>
        <p class="text-gray-500 mb-8">Anda sedang memesan <strong>{{ $vehicle->name }}</strong></p>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div x-data="bookingWizard()" class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Progress Bar -->
            <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <template x-for="i in 4">
                        <div class="flex flex-col items-center relative z-10">
                            <div :class="{'bg-primary-600 text-white': step >= i, 'bg-gray-200 text-gray-400': step < i}" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300">
                                <span x-text="i"></span>
                            </div>
                            <span class="text-xs font-medium mt-2" :class="{'text-primary-600': step >= i, 'text-gray-400': step < i}" x-text="stepLabels[i-1]"></span>
                        </div>
                    </template>
                </div>
                <div class="relative -top-8 left-10 right-10 h-1 bg-gray-200 -z-10" style="width: calc(100% - 5rem);">
                    <div class="h-full bg-primary-500 transition-all duration-300" :style="'width: ' + ((step - 1) / 3 * 100) + '%'"></div>
                </div>
            </div>

            <!-- Form -->
            <form id="bookingForm" action="{{ route('public.booking.store', $vehicle) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <input type="hidden" name="with_driver" :value="withDriver ? 1 : 0">

                <!-- Step 1: Tanggal Sewa -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Tentukan Tanggal Sewa</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" x-model="startDate" @change="calculateSummary" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                            <input type="date" name="end_date" x-model="endDate" @change="calculateSummary" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200" required>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 text-blue-800 p-4 rounded-xl text-sm mb-6 flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p>Pastikan Anda telah mengecek ketersediaan kendaraan pada kalender di halaman detail sebelumnya. Pemesanan pada tanggal yang sudah terisi akan otomatis ditolak oleh sistem.</p>
                    </div>
                </div>

                <!-- Step 2: Data Diri -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Penyewa</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor KTP</label>
                            <input type="text" name="id_card_number" value="{{ old('id_card_number') }}" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200" required>{{ old('address') }}</textarea>
                        </div>
                        
                        <div class="md:col-span-2 border-t border-gray-100 pt-6 mt-2">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Dokumen Jaminan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload KTP <span class="text-red-500">*</span></label>
                                    <input type="file" name="ktp_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" required>
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF. Max: 2MB.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload SIM (Opsional)</label>
                                    <input type="file" name="sim_file" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Opsi Layanan -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Layanan Tambahan</h2>
                    
                    @if($vehicle->price_per_day_with_driver)
                        <div class="mb-8 p-6 border-2 rounded-2xl cursor-pointer transition-colors"
                             :class="withDriver ? 'border-primary-500 bg-primary-50' : 'border-gray-200 bg-white'"
                             @click="toggleDriver()">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="withDriver ? 'border-primary-500 bg-primary-500' : 'border-gray-300'">
                                        <svg x-show="withDriver" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-grow">
                                    <h4 class="text-lg font-bold text-gray-900">Gunakan Jasa Supir</h4>
                                    <p class="text-gray-500 text-sm mt-1">Perjalanan lebih santai tanpa perlu repot menyetir sendiri.</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-primary-600 font-bold">+ Rp {{ number_format($vehicle->price_per_day_with_driver - $vehicle->price_per_day, 0, ',', '.') }}</span>
                                    <span class="text-gray-500 text-sm block">/ hari</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 text-yellow-800 p-4 rounded-xl text-sm mb-8">
                            <p>Kendaraan ini hanya melayani sewa <strong>Lepas Kunci</strong> (tanpa supir).</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Penjemputan / Ambil (Opsional)</label>
                            <input type="text" name="pickup_location" value="{{ old('pickup_location') }}" placeholder="Misal: Bandara Soekarno Hatta" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Pengembalian (Opsional)</label>
                            <input type="text" name="dropoff_location" value="{{ old('dropoff_location') }}" placeholder="Misal: Stasiun Gambir" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Ringkasan -->
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Ringkasan Pemesanan</h2>
                    
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Detail Biaya</h3>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Harga per Hari</span>
                            <span class="font-semibold text-gray-900" x-text="'Rp ' + formatNumber(currentPricePerDay)"></span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Durasi Sewa</span>
                            <span class="font-semibold text-gray-900"><span x-text="totalDays"></span> Hari</span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-gray-600">Opsi Supir</span>
                            <span class="font-semibold text-gray-900" x-text="withDriver ? 'Ya' : 'Tidak (Lepas Kunci)'"></span>
                        </div>
                        
                        <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between items-center">
                            <span class="text-gray-900 font-bold">Total Harga</span>
                            <span class="text-xl font-extrabold text-primary-600" x-text="'Rp ' + formatNumber(totalPrice)"></span>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mt-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-blue-800 font-bold block">DP Minimum ({{ $vehicle->min_dp_percentage }}%)</span>
                                    <span class="text-blue-600 text-sm">Wajib dibayar untuk konfirmasi</span>
                                </div>
                                <span class="text-xl font-extrabold text-blue-700" x-text="'Rp ' + formatNumber(dpAmount)"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-start mb-6">
                        <div class="flex items-center h-5">
                            <input type="checkbox" required class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label class="font-medium text-gray-700">Saya menyetujui syarat & ketentuan AutoRent</label>
                            <p class="text-gray-500">Pesanan yang sudah dibayar DP tidak dapat dikembalikan jika dibatalkan secara sepihak.</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between">
                    <button type="button" x-show="step > 1" @click="step--" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-50 transition-colors">
                        Kembali
                    </button>
                    <div x-show="step === 1" class="w-full"></div> <!-- Spacer for flex alignment -->
                    
                    <button type="button" x-show="step < 4" @click="nextStep()" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-full hover:bg-primary-700 shadow-md transition-colors">
                        Lanjut
                    </button>
                    
                    <button type="submit" x-show="step === 4" class="px-8 py-3 bg-primary-600 text-white font-bold rounded-full hover:bg-primary-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        Buat Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function bookingWizard() {
        return {
            step: 1,
            stepLabels: ['Tanggal', 'Data Diri', 'Layanan', 'Ringkasan'],
            startDate: '',
            endDate: '',
            withDriver: false,
            
            // Vehicle Constants
            basePrice: {{ $vehicle->price_per_day }},
            driverPrice: {{ $vehicle->price_per_day_with_driver ?? $vehicle->price_per_day }},
            minDpPercent: {{ $vehicle->min_dp_percentage }},
            
            // Computed Summary
            totalDays: 0,
            currentPricePerDay: 0,
            totalPrice: 0,
            dpAmount: 0,
            
            nextStep() {
                if (this.step === 1) {
                    if (!this.startDate || !this.endDate) {
                        alert('Silakan lengkapi tanggal mulai dan tanggal selesai.');
                        return;
                    }
                    if (new Date(this.endDate) < new Date(this.startDate)) {
                        alert('Tanggal selesai tidak boleh sebelum tanggal mulai.');
                        return;
                    }
                    this.calculateSummary();
                }
                
                if (this.step === 2) {
                    // Simple HTML5 validation trigger
                    const form = document.getElementById('bookingForm');
                    if (!form.reportValidity()) {
                        return;
                    }
                }
                
                if (this.step === 3) {
                    this.calculateSummary();
                }
                
                this.step++;
            },
            
            toggleDriver() {
                this.withDriver = !this.withDriver;
                this.calculateSummary();
            },
            
            calculateSummary() {
                if (!this.startDate || !this.endDate) return;
                
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);
                
                // Calculate difference in days (inclusive)
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                this.totalDays = diffDays;
                this.currentPricePerDay = this.withDriver ? this.driverPrice : this.basePrice;
                this.totalPrice = this.totalDays * this.currentPricePerDay;
                this.dpAmount = (this.totalPrice * this.minDpPercent) / 100;
            },
            
            formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        }
    }
</script>
@endsection
