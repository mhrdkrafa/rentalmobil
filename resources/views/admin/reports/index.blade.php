@extends('layouts.admin')

@section('page_title', 'Laporan Penyewaan')
@section('page_pretitle', 'Laporan Transaksi')

@section('content')
<div class="card mb-3">
    <div class="card-header"><h3 class="card-title">Filter Laporan</h3></div>
    <form action="{{ route('admin.reports.index') }}" method="GET">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-search me-1"></i>Tampilkan</button>
                    <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-danger">
                        <i class="ti ti-file-type-pdf me-1"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="row row-deck row-cards mb-3">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader text-secondary">Total Transaksi</div>
                <div class="h1 mt-2">{{ $totalBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader text-secondary">Total Pendapatan</div>
                <div class="h1 mt-2 text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <div class="subheader text-secondary">Selesai</div>
                <div class="h1 mt-2">{{ $completedBookings }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Tanggal Sewa</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Total Hari</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><code>{{ $booking->booking_code }}</code></td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->vehicle->name }}</td>
                    <td>{{ $booking->total_days }} Hari</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>
                        @php $sc = ['completed' => 'success', 'cancelled' => 'danger', 'pending' => 'warning', 'confirmed' => 'info', 'active' => 'primary']; @endphp
                        <span class="badge bg-{{ $sc[$booking->status->value] ?? 'secondary' }}">{{ strtoupper($booking->status->value) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary">Tidak ada data transaksi pada rentang tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
