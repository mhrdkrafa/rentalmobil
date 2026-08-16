@extends('layouts.admin')

@section('page_title', 'Moderasi Review')
@section('page_pretitle', 'Ulasan Customer')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Daftar Review</h3></div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>Customer</th>
                    <th>Kendaraan</th>
                    <th>Rating</th>
                    <th style="width:30%">Komentar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td class="text-secondary">{{ $review->created_at->format('d/m/Y') }}</td>
                    <td>{{ $review->customer->name }}</td>
                    <td>{{ $review->vehicle->name }}</td>
                    <td>
                        <span class="text-warning">
                            @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                            @for($i = $review->rating; $i < 5; $i++) ☆ @endfor
                        </span>
                    </td>
                    <td>{{ $review->comment ?? '-' }}</td>
                    <td>
                        @if($review->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-danger">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
                            @csrf
                            @if($review->is_published)
                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Sembunyikan review ini?')">
                                    <i class="ti ti-eye-off me-1"></i>Sembunyikan
                                </button>
                            @else
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Tampilkan review ini?')">
                                    <i class="ti ti-eye me-1"></i>Tampilkan
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary">Belum ada review.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
