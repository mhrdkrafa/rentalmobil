@extends('layouts.public')

@section('title', 'Hubungi Kami - AutoRent')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 400px; z-index: 10; }
</style>
@endpush

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Hubungi Kami</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">
                Punya pertanyaan mengenai sewa mobil atau butuh bantuan darurat? Tim layanan pelanggan kami siap membantu Anda kapan saja.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informasi Kontak -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card Alamat -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start space-x-4">
                    <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Kantor Pusat</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Jl. Kemang Raya No. 123, Bangka, Kec. Mampang Prpt., Kota Jakarta Selatan,<br>
                            Daerah Khusus Ibukota Jakarta 12730
                        </p>
                    </div>
                </div>

                <!-- Card WhatsApp -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start space-x-4">
                    <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">WhatsApp (Fast Response)</h3>
                        <p class="text-gray-600 text-sm mb-3">Layanan CS beroperasi 24 Jam.</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center text-sm font-semibold text-green-600 hover:text-green-700">
                            Chat Sekarang &rarr;
                        </a>
                    </div>
                </div>

                <!-- Card Jam Operasional -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start space-x-4">
                    <div class="flex-shrink-0 bg-orange-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Jam Pengambilan Mobil</h3>
                        <p class="text-gray-600 text-sm">
                            <strong>Senin - Jumat:</strong> 07.00 - 21.00 WIB<br>
                            <strong>Sabtu - Minggu:</strong> 06.00 - 22.00 WIB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Area Peta (OpenStreetMap) -->
            <div class="lg:col-span-2">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 h-full">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 px-2">Lokasi Garasi Kami</h3>
                    <div id="map" class="w-full rounded-xl border border-gray-200"></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Koordinat Dummy (Kemang, Jakarta Selatan)
        var latitude = -6.2615;
        var longitude = 106.8106;

        // Inisialisasi peta
        var map = L.map('map').setView([latitude, longitude], 15);

        // Menambahkan tile layer dari OpenStreetMap (Gratis & Open Source)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Menambahkan Marker (Pin Lokasi)
        var marker = L.marker([latitude, longitude]).addTo(map);
        
        // Popup saat marker di-klik
        marker.bindPopup("<b>AutoRent Pusat</b><br>Jl. Kemang Raya No. 123.").openPopup();
    });
</script>
@endpush
