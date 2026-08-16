@extends('layouts.admin')

@section('page_title', 'Armada Mobil')
@section('page_pretitle', 'Manajemen Kendaraan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kendaraan</h3>
        <div class="card-actions">
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>Tambah Mobil
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Nopol</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Harga/Hari</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td><code>{{ $vehicle->plate_number }}</code></td>
                    <td><strong>{{ $vehicle->name }}</strong></td>
                    <td>{{ $vehicle->category->name }}</td>
                    <td>
                        @php
                            $vc = ['available' => 'success', 'rented' => 'info', 'maintenance' => 'warning'];
                        @endphp
                        <span class="badge bg-{{ $vc[$vehicle->status->value] ?? 'secondary' }}">{{ ucfirst($vehicle->status->value) }}</span>
                    </td>
                    <td>Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('admin.vehicles.images.index', $vehicle) }}" class="btn btn-sm">
                            <i class="ti ti-photo me-1"></i>Foto
                        </a>
                        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">
                            <i class="ti ti-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kendaraan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-trash me-1"></i>Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $vehicles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection