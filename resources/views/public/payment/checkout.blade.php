@extends('layouts.public')

@section('title', 'Pembayaran | AutoRent')

@push('scripts')
    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="pt-24 pb-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Selesaikan Pembayaran Anda</h1>
            <p class="text-gray-600 mt-2">Pilih metode pembayaran instan atau transfer manual.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sticky top-28">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-4">Ringkasan</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex justify-between">
                            <span>Kode Booking</span>
                            <span class="font-bold text-gray-900">{{ $booking->booking_code }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Kendaraan</span>
                            <span class="font-bold text-gray-900">{{ $booking->vehicle->name }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Jenis Pembayaran</span>
                            <span class="font-bold text-gray-900">{{ strtoupper($paymentType) }}</span>
                        </li>
                    </ul>
                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-bold">Total Tagihan</span>
                            <span class="text-2xl font-extrabold text-primary-600">Rp {{ number_format($amountToPay, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Instant Payment (Midtrans) -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-primary-600 p-6 flex justify-between items-center text-white">
                        <div>
                            <h3 class="text-xl font-bold">Pembayaran Instan (Otomatis)</h3>
                            <p class="text-blue-100 text-sm mt-1">Virtual Account, Kartu Kredit, GoPay, ShopeePay, dll.</p>
                        </div>
                        <svg class="w-10 h-10 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-gray-600 mb-6">Pembayaran Anda akan diverifikasi secara instan dalam hitungan detik. Tidak perlu konfirmasi manual.</p>
                        @if($snapToken)
                            <button id="pay-button" class="px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg transition-transform transform hover:-translate-y-1 w-full sm:w-auto">
                                Bayar Sekarang
                            </button>
                        @else
                            <div class="text-red-500 font-semibold bg-red-50 p-4 rounded-xl border border-red-200">
                                Gagal memuat token pembayaran. Silakan hubungi admin.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Manual Transfer -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6" x-data="{ showManual: false }">
                    <div class="flex justify-between items-center cursor-pointer" @click="showManual = !showManual">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Transfer Manual</h3>
                            <p class="text-gray-500 text-sm">Transfer ke rekening bank & upload bukti bayar</p>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 transform transition-transform" :class="{'rotate-180': showManual}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    
                    <div x-show="showManual" x-collapse class="mt-6 border-t pt-6" style="display: none;">
                        <div class="bg-gray-50 p-4 rounded-xl mb-6">
                            <p class="text-sm text-gray-600 mb-2">Silakan transfer sesuai nominal ke salah satu rekening berikut:</p>
                            <ul class="space-y-2">
                                <li class="flex items-center space-x-3">
                                    <div class="w-12 h-8 bg-blue-900 text-white font-bold text-xs flex items-center justify-center rounded">BCA</div>
                                    <div>
                                        <p class="font-bold text-gray-900">1234567890</p>
                                        <p class="text-xs text-gray-500">a.n. PT AutoRent Indonesia</p>
                                    </div>
                                </li>
                                <li class="flex items-center space-x-3">
                                    <div class="w-12 h-8 bg-orange-600 text-white font-bold text-xs flex items-center justify-center rounded">BNI</div>
                                    <div>
                                        <p class="font-bold text-gray-900">0987654321</p>
                                        <p class="text-xs text-gray-500">a.n. PT AutoRent Indonesia</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <form action="{{ route('public.payment.manual', $booking->booking_code) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="payment_type" value="{{ $paymentType }}">
                            <input type="hidden" name="amount" value="{{ $amountToPay }}">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                                <input type="file" name="proof_file" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" required>
                                @error('proof_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl shadow transition">
                                Kirim Bukti Transfer
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        var payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){
                        // Redirect to success page
                        window.location.href = "{{ route('public.booking.show', $booking->booking_code) }}";
                    },
                    onPending: function(result){
                        // Redirect to show page so they can check status later
                        window.location.href = "{{ route('public.booking.show', $booking->booking_code) }}";
                    },
                    onError: function(result){
                        alert("Pembayaran gagal!");
                    },
                    onClose: function(){
                        // User closed the popup
                    }
                });
            });
        }
    });
</script>
@endpush
