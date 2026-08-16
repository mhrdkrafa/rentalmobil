@extends('layouts.admin')

@section('page_title', 'Kelola Foto: ' . $vehicle->name)

@section('content')
<div class="row">
    <div class="col-xs-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Terdapat kesalahan pada file yang diupload.
            </div>
        @endif

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Upload Foto Baru</h3>
            </div>
            <form action="{{ route('admin.vehicles.images.store', $vehicle) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <input type="file" name="images[]" multiple accept="image/*" required>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-default">Kembali ke Daftar Mobil</a>
                </div>
            </form>
        </div>
        
        <div class="row">
            @foreach($images as $image)
                <div class="col-md-3">
                    <div class="thumbnail">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto Mobil" style="width:100%; height:150px; object-fit:cover;">
                        <div class="caption text-center">
                            @if($image->is_primary)
                                <span class="label label-success">Primary</span>
                            @else
                                <form action="{{ route('admin.vehicles.images.primary', $image) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-xs btn-default">Set Primary</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.vehicles.images.destroy', $image) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus foto ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection