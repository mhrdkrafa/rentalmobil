@extends('layouts.public')

@section('title', 'Syarat dan Ketentuan - AutoRent')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Syarat & Ketentuan Sewa</h1>
                    <p class="text-gray-500">Pembaruan Terakhir: 17 Agustus 2026</p>
                </div>

                <div class="prose prose-blue max-w-none text-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">1. Persyaratan Umum (Sewa Lepas Kunci)</h3>
                    <ul class="list-disc pl-5 mb-6 space-y-2">
                        <li>Penyewa diwajibkan memiliki <strong>KTP asli</strong> dan <strong>SIM A</strong> yang masih berlaku.</li>
                        <li>Dokumen asli (KTP) akan ditahan oleh pihak AutoRent selama masa penyewaan berlangsung dan akan dikembalikan saat kendaraan dikembalikan dalam keadaan baik.</li>
                        <li>Penyewa bertanggung jawab penuh atas kendaraan selama masa sewa.</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">2. Penggunaan Kendaraan</h3>
                    <ul class="list-disc pl-5 mb-6 space-y-2">
                        <li>Kendaraan dilarang keras digunakan untuk tindak kejahatan, balapan liar, maupun kegiatan yang melanggar hukum perundang-undangan di Indonesia.</li>
                        <li>Wilayah penggunaan kendaraan terbatas pada area JABODETABEK (atau area yang disepakati). Penggunaan ke luar kota tanpa izin tertulis akan dikenakan denda pelanggaran rute.</li>
                        <li>Dilarang mengalih-sewakan kendaraan kepada pihak ketiga.</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">3. Keterlambatan & Denda (Overtime)</h3>
                    <ul class="list-disc pl-5 mb-6 space-y-2">
                        <li>Batas toleransi keterlambatan pengembalian kendaraan adalah <strong>1 jam</strong> dari waktu yang tertera pada faktur sewa.</li>
                        <li>Keterlambatan lebih dari 1 jam akan dikenakan denda sebesar <strong>10% dari harga sewa per hari</strong> untuk setiap jam keterlambatannya.</li>
                        <li>Jika keterlambatan melebihi 6 jam, penyewa wajib membayar biaya sewa penuh untuk 1 hari (24 jam) tambahan.</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">4. Kerusakan & Kehilangan</h3>
                    <ul class="list-disc pl-5 mb-6 space-y-2">
                        <li>Segala bentuk kerusakan kecil (lecet, penyok) maupun kerusakan berat (tabrakan) menjadi tanggung jawab penuh penyewa, dan biaya perbaikan akan dibebankan kepada penyewa sesuai estimasi bengkel resmi.</li>
                        <li>Kehilangan komponen mobil (seperti ban serep, dongkrak, tape/audio, STNK) wajib diganti rugi dengan nominal barang yang setara/asli.</li>
                        <li>Apabila terjadi kehilangan unit mobil, penyewa wajib bertanggung jawab secara hukum dan mengganti sesuai harga pasaran mobil tersebut.</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-900 mt-8 mb-4">5. Pembatalan (Refund Policy)</h3>
                    <ul class="list-disc pl-5 mb-6 space-y-2">
                        <li>Pembatalan maksimal H-2 sebelum hari H (tanggal sewa) akan mendapatkan pengembalian dana (refund) sebesar 100%.</li>
                        <li>Pembatalan pada H-1 akan dikenakan potongan biaya administrasi sebesar 50% dari total DP atau biaya sewa.</li>
                        <li>Pembatalan pada hari H (Hari Pengambilan) mengakibatkan seluruh dana yang telah masuk hangus (Non-Refundable).</li>
                    </ul>
                </div>

                <div class="mt-12 p-6 bg-blue-50 rounded-xl">
                    <p class="text-sm text-blue-800 text-center">
                        Dengan melakukan pemesanan (booking) melalui platform AutoRent, Anda dianggap telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di atas yang mengikat secara hukum.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
