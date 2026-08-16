@extends('layouts.admin')

@section('page_title', 'Edit Mobil')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-warning">
            <form role="form" action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @error('name') has-error @enderror">
                                <label>Nama Mobil</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $vehicle->name) }}" required>
                                @error('name') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('plate_number') has-error @enderror">
                                <label>Nomor Polisi</label>
                                <input type="text" class="form-control" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                                @error('plate_number') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('category_id') has-error @enderror">
                                <label>Kategori</label>
                                <select class="form-control" name="category_id" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $vehicle->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('transmission') has-error @enderror">
                                <label>Transmisi</label>
                                <select class="form-control" name="transmission" required>
                                    <option value="manual" {{ old('transmission', $vehicle->transmission->value) == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="automatic" {{ old('transmission', $vehicle->transmission->value) == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                </select>
                                @error('transmission') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('fuel_type') has-error @enderror">
                                <label>Bahan Bakar</label>
                                <select class="form-control" name="fuel_type" required>
                                    <option value="bensin" {{ old('fuel_type', $vehicle->fuel_type->value) == 'bensin' ? 'selected' : '' }}>Bensin</option>
                                    <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type->value) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="listrik" {{ old('fuel_type', $vehicle->fuel_type->value) == 'listrik' ? 'selected' : '' }}>Listrik</option>
                                    <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type->value) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                                @error('fuel_type') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('capacity') has-error @enderror">
                                <label>Kapasitas (Orang)</label>
                                <input type="number" class="form-control" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" required>
                                @error('capacity') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @error('year') has-error @enderror">
                                <label>Tahun</label>
                                <input type="number" class="form-control" name="year" value="{{ old('year', $vehicle->year) }}" required>
                                @error('year') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('price_per_day') has-error @enderror">
                                <label>Harga / Hari (Lepas Kunci)</label>
                                <input type="number" class="form-control" name="price_per_day" value="{{ old('price_per_day', $vehicle->price_per_day) }}" required>
                                @error('price_per_day') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('price_per_day_with_driver') has-error @enderror">
                                <label>Harga / Hari (Dengan Supir)</label>
                                <input type="number" class="form-control" name="price_per_day_with_driver" value="{{ old('price_per_day_with_driver', $vehicle->price_per_day_with_driver) }}">
                                @error('price_per_day_with_driver') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('deposit_amount') has-error @enderror">
                                <label>Deposit Amount</label>
                                <input type="number" class="form-control" name="deposit_amount" value="{{ old('deposit_amount', $vehicle->deposit_amount) }}">
                                @error('deposit_amount') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('min_dp_percentage') has-error @enderror">
                                <label>Min DP (%)</label>
                                <input type="number" class="form-control" name="min_dp_percentage" value="{{ old('min_dp_percentage', $vehicle->min_dp_percentage) }}" required>
                                @error('min_dp_percentage') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group @error('status') has-error @enderror">
                                <label>Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="available" {{ old('status', $vehicle->status->value) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="rented" {{ old('status', $vehicle->status->value) == 'rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="maintenance" {{ old('status', $vehicle->status->value) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactive" {{ old('status', $vehicle->status->value) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $vehicle->description) }}</textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection