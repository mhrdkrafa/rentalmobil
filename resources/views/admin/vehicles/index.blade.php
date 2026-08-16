@extends('layouts.admin')

@section('page_title', 'Armada Mobil')

@section('content')
<div class="row">
    <div class="col-xs-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Daftar Kendaraan</h3>
                <div class="box-tools">
                    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Mobil</a>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>Nopol</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Harga/Hari</th>
                        <th>Aksi</th>
                    </tr>
                    @foreach($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->plate_number }}</td>
                        <td>{{ $vehicle->name }}</td>
                        <td>{{ $vehicle->category->name }}</td>
                        <td>
                            @if($vehicle->status->value === 'available')
                                <span class="label label-success">Available</span>
                            @elseif($vehicle->status->value === 'rented')
                                <span class="label label-info">Rented</span>
                            @elseif($vehicle->status->value === 'maintenance')
                                <span class="label label-warning">Maintenance</span>
                            @else
                                <span class="label label-default">Inactive</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.vehicles.images.index', $vehicle) }}" class="btn btn-xs btn-info"><i class="fa fa-image"></i> Foto</a>
                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus kendaraan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="box-footer clearfix">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection