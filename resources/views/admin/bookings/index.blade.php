@extends('layouts.admin')

@section('page_title', 'Manajemen Booking')
@section('page_pretitle', 'Daftar Pemesanan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pemesanan</h3>
        <div class="card-actions">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <div class="input-group input-group-sm" style="width: 220px;">
                    <input type="text" name="q" class="form-control" placeholder="Cari kode/nama..." value="{{ request('q') }}">
                    <button type="submit" class="btn btn-icon btn-sm"><i class="ti ti-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Tanggal Buat</th>
                    <th>Customer</th>
                    <th>Mobil</th>
                    <th>Tgl Sewa</th>
                    <th>Total Tagihan</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td><code>{{ $booking->booking_code }}</code></td>
                    <td class="text-secondary">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $booking->customer->name }}</td>
                    <td>{{ $booking->vehicle->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>
                        @if($booking->payment_status->value === 'paid')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($booking->payment_status->value === 'partial')
                            <span class="badge bg-warning">DP / Parsial</span>
                        @else
                            <span class="badge bg-danger">Belum Bayar</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusColors = ['pending' => 'secondary', 'confirmed' => 'info', 'active' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$booking->status->value] ?? 'secondary' }}">{{ strtoupper($booking->status->value) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye me-1"></i>Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-secondary">Belum ada data pemesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
