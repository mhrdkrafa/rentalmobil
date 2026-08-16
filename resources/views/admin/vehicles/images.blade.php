@extends('layouts.admin')

@section('page_title', 'Kelola Foto: ' . $vehicle->name)
@section('page_pretitle', 'Armada')

@section('content')
<div class="card mb-3">
    <div class="card-header"><h3 class="card-title">Upload Foto Baru</h3></div>
    <form action="{{ route('admin.vehicles.images.store', $vehicle) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="mb-3">
                <input type="file" class="form-control" name="images[]" multiple accept="image/*" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="ti ti-upload me-1"></i>Upload</button>
            <a href="{{ route('admin.vehicles.index') }}" class="btn ms-2">Kembali ke Daftar Mobil</a>
        </div>
    </form>
</div>

<div class="row row-cards">
    @foreach($images as $image)
        <div class="col-md-3">
            <div class="card">
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto Mobil" class="card-img-top" style="height:180px; object-fit:cover;">
                <div class="card-body text-center p-2">
                    @if($image->is_primary)
                        <span class="badge bg-success mb-2">Primary</span>
                    @else
                        <form action="{{ route('admin.vehicles.images.primary', $image) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm mb-2">Set Primary</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.vehicles.images.destroy', $image) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus foto ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger mb-2"><i class="ti ti-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection