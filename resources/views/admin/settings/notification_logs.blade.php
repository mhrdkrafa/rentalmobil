@extends('layouts.admin')

@section('page_title', 'Histori Notifikasi')
@section('page_pretitle', 'WhatsApp & Email')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Log Notifikasi</h3>
        <div class="card-actions">
            <a href="{{ route('admin.settings.notifications') }}" class="btn btn-sm">
                <i class="ti ti-settings me-1"></i>Pengaturan Template
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe</th>
                    <th>Penerima</th>
                    <th style="width:30%">Pesan</th>
                    <th>Status</th>
                    <th>Response</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="text-secondary">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td><span class="badge bg-info">{{ strtoupper($log->type) }}</span></td>
                    <td>{{ $log->recipient }}</td>
                    <td style="white-space: pre-wrap; font-size: 12px;">{{ $log->message }}</td>
                    <td>
                        @if($log->status === 'success')
                            <span class="badge bg-success">Terkirim</span>
                        @else
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                    </td>
                    <td style="font-size: 11px;">
                        <a class="btn btn-sm" data-bs-toggle="collapse" href="#response-{{ $log->id }}">Lihat</a>
                        <div id="response-{{ $log->id }}" class="collapse mt-2 text-secondary" style="max-height:100px; overflow-y:auto;">
                            {{ $log->response }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary">Belum ada catatan pengiriman notifikasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
