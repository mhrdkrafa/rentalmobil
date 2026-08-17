@extends('layouts.admin')

@section('page_title', 'Dashboard')
@section('page_pretitle', 'Ringkasan')

@section('content')
<div class="row row-deck row-cards">
    {{-- Stat Cards --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-secondary">Total Booking</div>
                </div>
                <div class="h1 mb-0 mt-2">{{ $totalBookings }}</div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-link p-0 text-secondary">
                    Lihat semua <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-secondary">Total Pendapatan</div>
                </div>
                <div class="h1 mb-0 mt-2 text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-link p-0 text-secondary">
                    Lihat laporan <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-secondary">Total Customer</div>
                </div>
                <div class="h1 mb-0 mt-2">{{ $totalCustomers }}</div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.customers.index') }}" class="btn btn-link p-0 text-secondary">
                    Lihat semua <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader text-secondary">Total Kendaraan</div>
                </div>
                <div class="h1 mb-0 mt-2">{{ $totalVehicles }}</div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-link p-0 text-secondary">
                    Lihat semua <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pendapatan 6 Bulan Terakhir</h3>
            </div>
            <div class="card-body">
                <div style="height: 260px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status Booking</h3>
            </div>
            <div class="card-body">
                <div style="height: 260px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Booking Terbaru</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Tanggal Mulai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                        <tr>
                            <td><code>{{ $booking->booking_code }}</code></td>
                            <td>{{ $booking->customer->name }}</td>
                            <td>{{ $booking->vehicle->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $colors = ['pending' => 'warning', 'confirmed' => 'info', 'active' => 'primary', 'completed' => 'success', 'cancelled' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $colors[$booking->status->value] ?? 'secondary' }}">{{ strtoupper($booking->status->value) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary">Belum ada booking terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($revenueData) !!},
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'jt'
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Active', 'Completed', 'Cancelled'],
            datasets: [{
                data: [{{ $statusData['pending'] }}, {{ $statusData['confirmed'] }}, {{ $statusData['active'] }}, {{ $statusData['completed'] }}, {{ $statusData['cancelled'] }}],
                backgroundColor: ['#f59e0b', '#0ea5e9', '#0d9488', '#10b981', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
            }
        }
    });
});
</script>
@endpush
