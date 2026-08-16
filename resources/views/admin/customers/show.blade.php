@extends('layouts.admin')

@section('page_title', 'Detail Customer')
@section('page_pretitle', 'Profil & Riwayat')

@section('content')
<div class="row row-cards">
    <!-- Profil -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body text-center">
                <span class="avatar avatar-xl rounded-circle bg-primary text-white mb-3" style="font-size: 2rem;">{{ substr($customer->name, 0, 1) }}</span>
                <h3 class="mb-1">{{ $customer->name }}</h3>
                <p class="text-secondary">Bergabung sejak {{ $customer->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between">
                    <strong>WhatsApp</strong>
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $customer->phone) }}" target="_blank">{{ $customer->phone }}</a>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <strong>Email</strong>
                    <span>{{ $customer->email ?? '-' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <strong>No. KTP</strong>
                    <span>{{ $customer->id_card_number ?? '-' }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <strong>Total Booking</strong>
                    <span>{{ $customer->bookings->count() }}</span>
                </div>
                <div class="list-group-item d-flex justify-content-between">
                    <strong>Status</strong>
                    @if($customer->is_blacklisted)
                        <span class="badge bg-danger">Blacklisted</span>
                    @else
                        <span class="badge bg-success">Aktif</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.customers.blacklist', $customer->id) }}" method="POST">
                    @csrf
                    @if($customer->is_blacklisted)
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Pulihkan customer ini?')">Pulihkan Status</button>
                    @else
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Blacklist customer ini?')">Blacklist Customer</button>
                    @endif
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Alamat</h3></div>
            <div class="card-body">
                <p class="text-secondary">{{ $customer->address ?? 'Belum ada data alamat' }}</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Booking -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Riwayat Penyewaan</h3></div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kendaraan</th>
                            <th>Tgl Sewa</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->bookings as $booking)
                        <tr>
                            <td><code>{{ $booking->booking_code }}</code></td>
                            <td>{{ $booking->vehicle->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td>
                                @php $sc = ['completed' => 'success', 'cancelled' => 'danger', 'pending' => 'warning', 'confirmed' => 'info', 'active' => 'primary']; @endphp
                                <span class="badge bg-{{ $sc[$booking->status] ?? 'secondary' }}">{{ strtoupper($booking->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm"><i class="ti ti-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary">Belum ada riwayat booking.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
