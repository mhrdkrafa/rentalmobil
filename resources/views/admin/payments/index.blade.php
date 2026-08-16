@extends('layouts.admin')

@section('page_title', 'Verifikasi Pembayaran')
@section('page_pretitle', 'Pembayaran Manual')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Daftar Pembayaran Manual</h3></div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Kode Booking</th>
                    <th>Customer</th>
                    <th>Tipe</th>
                    <th>Nominal</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td class="text-secondary">{{ $payment->id }}</td>
                    <td class="text-secondary">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td><a href="{{ route('admin.bookings.show', $payment->booking->id) }}"><code>{{ $payment->booking->booking_code }}</code></a></td>
                    <td>{{ $payment->booking->customer->name }}</td>
                    <td><span class="badge bg-info">{{ strtoupper($payment->payment_type->value) }}</span></td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->proof_file_path)
                            <a href="{{ asset('storage/' . $payment->proof_file_path) }}" target="_blank" class="btn btn-sm">
                                <i class="ti ti-photo me-1"></i>Lihat
                            </a>
                        @else
                            <span class="text-secondary">-</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->status->value === 'pending')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif($payment->status->value === 'verified')
                            <span class="badge bg-success">Diverifikasi</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->status->value === 'pending')
                        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?');">
                                <i class="ti ti-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pembayaran ini?');">
                                <i class="ti ti-x"></i>
                            </button>
                        </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-secondary">Belum ada data pembayaran manual.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $payments->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
