<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penyewaan Mobil</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2, h3 {
            text-align: center;
            margin: 5px 0;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            padding: 8px;
            text-align: left;
        }
        td {
            padding: 6px 8px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            width: 300px;
            float: right;
        }
        .summary table {
            border: none;
        }
        .summary table td {
            border: none;
            padding: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>AutoRent - Laporan Penyewaan Mobil</h2>
        <h3>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Kode</th>
                <th width="15%">Tanggal Sewa</th>
                <th width="20%">Pelanggan</th>
                <th width="20%">Kendaraan</th>
                <th width="8%" class="text-center">Durasi</th>
                <th width="12%" class="text-right">Total (Rp)</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRow = 0; @endphp
            @forelse($bookings as $index => $booking)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $booking->booking_code }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</td>
                <td>{{ $booking->customer->name }}</td>
                <td>{{ $booking->vehicle->name }}</td>
                <td class="text-center">{{ $booking->total_days }} Hari</td>
                <td class="text-right">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td class="text-center">{{ strtoupper($booking->status->value) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Transaksi:</strong></td>
                <td class="text-right">{{ $bookings->count() }}</td>
            </tr>
            <tr>
                <td><strong>Transaksi Selesai:</strong></td>
                <td class="text-right">{{ $bookings->filter(function($b) { return $b->status->value === 'completed'; })->count() }}</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div style="clear: both; margin-top: 50px;">
        <p class="text-right" style="padding-right: 50px;">
            Dicetak pada: {{ date('d F Y H:i:s') }}
        </p>
    </div>

</body>
</html>
