@extends('layouts.admin')

@section('page_title', 'Edit Kategori')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="box box-warning">
            <form role="form" action="{{ route('admin.vehicle-categories.update', $vehicleCategory) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $vehicleCategory->name) }}">
                        @error('name') <span class="help-block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="{{ route('admin.vehicle-categories.index') }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection