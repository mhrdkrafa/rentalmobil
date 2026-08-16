@extends('layouts.admin')

@section('page_title', 'Kategori Armada')

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
                <h3 class="box-title">Daftar Kategori</h3>
                <div class="box-tools">
                    <a href="{{ route('admin.vehicle-categories.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Kategori</a>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <a href="{{ route('admin.vehicle-categories.edit', $category) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.vehicle-categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus kategori ini?');">
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
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection