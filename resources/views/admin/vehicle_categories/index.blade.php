@extends('layouts.admin')

@section('page_title', 'Kategori Armada')
@section('page_pretitle', 'Manajemen Kendaraan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kategori</h3>
        <div class="card-actions">
            <a href="{{ route('admin.vehicle-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i>Tambah Kategori
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td class="text-secondary">{{ $category->id }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>
                        <a href="{{ route('admin.vehicle-categories.edit', $category) }}" class="btn btn-sm btn-warning">
                            <i class="ti ti-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.vehicle-categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kategori ini?');">
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
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection