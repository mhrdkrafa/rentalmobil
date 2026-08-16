@extends('layouts.admin')

@section('page_title', 'Manajemen Customer')
@section('page_pretitle', 'Daftar Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Semua Customer</h3>
        <div class="card-actions">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="q" class="form-control" placeholder="Cari Nama/No.HP/KTP" value="{{ request('q') }}">
                    <button type="submit" class="btn btn-icon btn-sm"><i class="ti ti-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>No. WhatsApp</th>
                    <th>No. KTP</th>
                    <th>Total Sewa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="text-secondary">{{ $customer->id }}</td>
                    <td><strong>{{ $customer->name }}</strong></td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->id_card_number ?? '-' }}</td>
                    <td>{{ $customer->completed_bookings_count }}x Selesai ({{ $customer->bookings_count }} Total)</td>
                    <td>
                        @if($customer->is_blacklisted)
                            <span class="badge bg-danger"><i class="ti ti-ban me-1"></i>Blacklisted</span>
                        @else
                            <span class="badge bg-success">Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-eye me-1"></i>Detail
                        </a>
                        <form action="{{ route('admin.customers.blacklist', $customer->id) }}" method="POST" class="d-inline">
                            @csrf
                            @if($customer->is_blacklisted)
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Pulihkan customer ini?')">Pulihkan</button>
                            @else
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Blacklist customer ini?')">
                                    <i class="ti ti-ban me-1"></i>Blacklist
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary">Belum ada data customer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
