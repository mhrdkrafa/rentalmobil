@extends('layouts.admin')

@section('page_title', 'Edit Kategori')
@section('page_pretitle', 'Armada')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <form action="{{ route('admin.vehicle-categories.update', $vehicleCategory) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Kategori</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $vehicleCategory->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('admin.vehicle-categories.index') }}" class="btn me-2">Batal</a>
                    <button type="submit" class="btn btn-warning"><i class="ti ti-device-floppy me-1"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection