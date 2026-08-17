@extends('layouts.admin')

@section('page_title', 'Detail Booking ' . $booking->booking_code)
@section('page_pretitle', 'Manajemen Booking')

@section('content')
<div class="row row-cards">
    <!-- Left Column: Customer & Vehicle Info -->
    <div class="col-md-7">

        <!-- Status Panel -->
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Status Penyewaan</h3></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="subheader mb-1">Pembayaran</div>
                        @if($booking->payment_status->value === 'paid_full')
                            <span class="badge bg-success">LUNAS</span>
                        @elseif($booking->payment_status->value === 'dp_paid')
                            <span class="badge bg-warning">DP DIBAYAR</span>
                        @else
                            <span class="badge bg-danger">BELUM DIBAYAR</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="subheader mb-1">Status Booking</div>
                        @php
                            $sc = ['pending' => 'secondary', 'confirmed' => 'info', 'active' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'];
                        @endphp
                        <span class="badge bg-{{ $sc[$booking->status->value] ?? 'secondary' }}">{{ strtoupper($booking->status->value) }}</span>
                    </div>
                    <div class="col-md-4">
                        <div class="subheader mb-1">Ubah Status</div>
                        <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm">
                                <option value="pending" {{ $booking->status->value === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status->value === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="active" {{ $booking->status->value === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $booking->status->value === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Data Penyewa</h3></div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <tr>
                        <th style="width: 30%">Nama</th>
                        <td>{{ $booking->customer->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $booking->customer->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. WhatsApp</th>
                        <td>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->customer->phone) }}" target="_blank" class="btn btn-sm btn-success">
                                <i class="ti ti-brand-whatsapp me-1"></i>{{ $booking->customer->phone }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $booking->customer->address ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Documents -->
        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Dokumen Penyewa (KTP/SIM)</h3></div>
            <div class="card-body">
                <div class="row">
                    @forelse($booking->documents as $doc)
                        <div class="col-md-6 text-center mb-3">
                            <div class="subheader mb-2">{{ strtoupper($doc->type->value) }}</div>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal-doc-{{ $doc->id }}">
                                <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->type->value }}" class="img-thumbnail" style="max-height: 150px;">
                            </a>

                            <!-- Modal -->
                            <div class="modal fade" id="modal-doc-{{ $doc->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Dokumen {{ strtoupper($doc->type->value) }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->type->value }}" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-secondary">Belum ada dokumen yang diunggah.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Booking Details & Payment -->
    <div class="col-md-5">

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Detail Sewa & Biaya</h3></div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="mb-0">{{ $booking->vehicle->name }}</h3>
                    <span class="badge bg-primary">{{ $booking->vehicle->license_plate }}</span>
                </div>
                <p class="text-secondary">{{ $booking->vehicle->category->name }} &bull; {{ $booking->with_driver ? 'Dengan Supir' : 'Lepas Kunci' }}</p>

                <table class="table table-striped mt-3">
                    <tr><th>Tgl Mulai</th><td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y H:i') }}</td></tr>
                    <tr><th>Tgl Selesai</th><td>{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y H:i') }}</td></tr>
                    <tr><th>Durasi</th><td>{{ $booking->total_days }} Hari</td></tr>
                    <tr><th>Harga per Hari</th><td>Rp {{ number_format($booking->price_per_day, 0, ',', '.') }}</td></tr>
                    <tr class="table-primary fw-bold"><th>TOTAL HARGA</th><td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
                    @if($booking->dp_amount > 0)
                    <tr><th>Min. DP (30%)</th><td>Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td></tr>
                    @endif
                </table>

                @if($booking->with_driver)
                    <hr>
                    <h4>Supir</h4>
                    @if($booking->driver)
                        <p><strong>Nama:</strong> {{ $booking->driver->name }}</p>
                        <p><strong>No. HP:</strong> {{ $booking->driver->phone }}</p>
                    @else
                        <div class="alert alert-warning"><i class="ti ti-alert-triangle me-2"></i>Supir belum ditugaskan!</div>
                        <form action="{{ route('admin.bookings.assign-driver', $booking->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Supir Tersedia:</label>
                                <select name="driver_id" class="form-select" required>
                                    <option value="">-- Pilih Supir --</option>
                                    @foreach($availableDrivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Assign Supir</button>
                        </form>
                    @endif
                @endif

                @if($booking->status->value === 'active' || $booking->status->value === 'confirmed')
                    <hr>
                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modal-complete">
                        <i class="ti ti-check me-1"></i>Selesaikan Pesanan & Hitung Denda
                    </button>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Riwayat Pembayaran</h3></div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Metode</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($booking->payments as $payment)
                        <tr>
                            <td>{{ strtoupper($payment->payment_type->value) }}</td>
                            <td>{{ ucfirst($payment->method->value) }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($payment->status->value === 'verified')
                                    <span class="badge bg-success">Sukses</span>
                                @elseif($payment->status->value === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary">Belum ada riwayat pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Complete Booking -->
<div class="modal fade" id="modal-complete" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Selesaikan Pesanan (Pengembalian Mobil)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Waktu pengembalian yang disepakati: <strong>{{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y H:i') }}</strong></p>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Waktu Pengembalian Aktual (Sekarang)</label>
                        <input type="datetime-local" name="actual_end_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        <small class="form-hint">Jika pengembalian melebihi waktu yang disepakati (+ toleransi 1 jam), denda akan dihitung proporsional dan ditambahkan ke tagihan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Proses Pengembalian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
