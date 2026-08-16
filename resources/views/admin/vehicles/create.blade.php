@extends('layouts.admin')

@section('page_title', 'Tambah Mobil')
@section('page_pretitle', 'Armada')

@section('content')
<div class="card">
    <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Nama Mobil</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Nomor Polisi</label>
                        <input type="text" class="form-control @error('plate_number') is-invalid @enderror" name="plate_number" value="{{ old('plate_number') }}" required>
                        @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                            <option value="">-- Pilih --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Transmisi</label>
                        <select class="form-select @error('transmission') is-invalid @enderror" name="transmission" required>
                            <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        </select>
                        @error('transmission') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Bahan Bakar</label>
                        <select class="form-select @error('fuel_type') is-invalid @enderror" name="fuel_type" required>
                            <option value="bensin" {{ old('fuel_type') == 'bensin' ? 'selected' : '' }}>Bensin</option>
                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="listrik" {{ old('fuel_type') == 'listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                        @error('fuel_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kapasitas (Orang)</label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity', 4) }}" required>
                        @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Tahun</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" name="year" value="{{ old('year', date('Y')) }}" required>
                        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga / Hari (Lepas Kunci)</label>
                        <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" name="price_per_day" value="{{ old('price_per_day') }}" required>
                        @error('price_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga / Hari (Dengan Supir)</label>
                        <input type="number" class="form-control @error('price_per_day_with_driver') is-invalid @enderror" name="price_per_day_with_driver" value="{{ old('price_per_day_with_driver') }}">
                        @error('price_per_day_with_driver') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deposit Amount</label>
                        <input type="number" class="form-control @error('deposit_amount') is-invalid @enderror" name="deposit_amount" value="{{ old('deposit_amount') }}">
                        @error('deposit_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Min DP (%)</label>
                        <input type="number" class="form-control @error('min_dp_percentage') is-invalid @enderror" name="min_dp_percentage" value="{{ old('min_dp_percentage', 30) }}" required>
                        @error('min_dp_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Foto Mobil (Multi)</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" name="images[]" multiple accept="image/*">
                        <small class="form-hint">Bisa pilih lebih dari satu foto. Foto pertama akan jadi foto utama.</small>
                        @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('admin.vehicles.index') }}" class="btn me-2">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
        </div>
    </form>
</div>
@endsection