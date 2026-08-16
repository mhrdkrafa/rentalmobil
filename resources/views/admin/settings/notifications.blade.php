@extends('layouts.admin')

@section('page_title', 'Template Notifikasi WA')
@section('page_pretitle', 'Pengaturan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Template Pesan</h3>
                <div class="card-actions">
                    <a href="{{ route('admin.notification-logs.index') }}" class="btn btn-sm">
                        <i class="ti ti-history me-1"></i>Histori Pesan
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.settings.notifications.update') }}" method="POST">
                @csrf
                <div class="card-body">

                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div><i class="ti ti-info-circle icon alert-icon"></i></div>
                            <div>
                                <strong>Variabel Tersedia:</strong><br>
                                <code>{name}</code> : Nama Customer<br>
                                <code>{booking_code}</code> : Kode Booking<br>
                                <code>{vehicle}</code> : Nama Kendaraan<br>
                                <code>{total}</code> : Total Harga (Khusus Booking Baru)
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Template Booking Baru (Ke Customer)</label>
                        <textarea name="wa_template_new_booking" class="form-control" rows="4" required>{{ $templates['wa_template_new_booking'] ?? "Halo {name}, pesanan rental mobil Anda dengan kode {booking_code} telah kami terima. Silakan lakukan pembayaran agar pesanan dapat diproses." }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Template Pembayaran Terverifikasi</label>
                        <textarea name="wa_template_payment_verified" class="form-control" rows="4" required>{{ $templates['wa_template_payment_verified'] ?? "Halo {name}, pembayaran untuk pesanan {booking_code} telah diverifikasi. Terima kasih." }}</textarea>
                    </div>

                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
