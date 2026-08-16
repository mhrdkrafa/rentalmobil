@extends('layouts.admin')

@section('page_title', 'Tambah Kategori')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <form role="form" action="{{ route('admin.vehicle-categories.store') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Contoh: City Car">
                        @error('name') <span class="help-block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.vehicle-categories.index') }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection