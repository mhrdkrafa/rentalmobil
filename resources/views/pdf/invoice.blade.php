<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->booking_code }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: table; width: 100%; border-bottom: 2px solid #2563eb; padding-bottom: 20px; margin-bottom: 20px; }
        .header .logo { display: table-cell; vertical-align: top; font-size: 24px; font-weight: bold; color: #2563eb; }
        .header .info { display: table-cell; text-align: right; vertical-align: top; }
        .title { font-size: 28px; margin-bottom: 5px; font-weight: bold; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { width: 50%; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border-bottom: 1px solid #ddd; padding: 12px; text-align: left; }
        .items-table th { background-color: #f8fafc; font-weight: bold; }
        .items-table .right { text-align: right; }
        .total-row { font-weight: bold; }
        .footer { text-align: center; color: #777; margin-top: 50px; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; background-color: #10b981; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="logo">
                AutoRent
            </div>
            <div class="info">
                <div class="title">INVOICE</div>
                <div>Kode: <strong>{{ $booking->booking_code }}</strong></div>
                <div>Tanggal: {{ now()->format('d/m/Y') }}</div>
            </div>
        </div>

        <table class="details-table">
            <tr>
                <td>
                    <strong>Ditagihkan Kepada:</strong><br>
                    {{ $booking->customer->name }}<br>
                    No. WA: {{ $booking->customer->phone }}<br>
                    No. KTP: {{ $booking->customer->id_card_number }}
                </td>
                <td>
                    <strong>Detail Sewa:</strong><br>
                    Mulai: {{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}<br>
                    Selesai: {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}<br>
                    Layanan: {{ $booking->with_driver ? 'Dengan Supir' : 'Lepas Kunci' }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="right">Durasi</th>
                    <th class="right">Harga/Hari</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Kendaraan: {{ $booking->vehicle->name }}</td>
                    <td class="right">{{ $booking->total_days }} Hari</td>
                    <td class="right">Rp {{ number_format($booking->price_per_day, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="right">Total Tagihan</td>
                    <td class="right">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <strong>Riwayat Pembayaran:</strong>
            <table class="items-table" style="margin-top: 10px; font-size: 12px;">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Waktu</th>
                        <th class="right">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPaid = 0; @endphp
                    @foreach($booking->payments->where('status.value', 'verified') as $payment)
                        @php $totalPaid += $payment->amount; @endphp
                        <tr>
                            <td>{{ strtoupper($payment->payment_type->value) }} ({{ ucfirst($payment->method->value) }})</td>
                            <td>{{ $payment->verified_at ? $payment->verified_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2" class="right">Total Dibayar</td>
                        <td class="right">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2" class="right">Sisa Tagihan</td>
                        <td class="right">Rp {{ number_format($booking->total_price - $totalPaid, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            Terima kasih telah mempercayakan perjalanan Anda kepada AutoRent.<br>
            Dokumen ini sah secara elektronik dan tidak memerlukan tanda tangan basah.
        </div>
    </div>
</body>
</html>
