<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi - {{ now()->format('d/m/Y') }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .summary-row {
            display: table-row;
        }
        .summary-cell {
            display: table-cell;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
        }
        .summary-label {
            font-weight: bold;
            background-color: #f5f5f5;
            font-size: 10px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #2d5a27;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #2d5a27;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SEBELAS COFFEE</h1>
        <p>Laporan Transaksi</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y') }}</p>
    </div>

    @if($summary['tanggal_mulai'] || $summary['tanggal_selesai'] || $summary['metode_pembayaran'] !== 'all')
    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong><br>
        @if($summary['tanggal_mulai'])
        Tanggal Mulai: {{ \Carbon\Carbon::parse($summary['tanggal_mulai'])->format('d/m/Y') }}<br>
        @endif
        @if($summary['tanggal_selesai'])
        Tanggal Selesai: {{ \Carbon\Carbon::parse($summary['tanggal_selesai'])->format('d/m/Y') }}<br>
        @endif
        @if($summary['metode_pembayaran'] !== 'all')
        Metode Pembayaran: {{ ucfirst($summary['metode_pembayaran']) }}
        @endif
    </div>
    @endif

    <div class="summary">
        <div class="summary-row">
            <div class="summary-cell summary-label">Total Transaksi</div>
            <div class="summary-cell summary-label">Total Pendapatan</div>
            <div class="summary-cell summary-label">Cash</div>
            <div class="summary-cell summary-label">QRIS</div>
            <div class="summary-cell summary-label">Transfer</div>
        </div>
        <div class="summary-row">
            <div class="summary-cell summary-value">{{ $summary['total_transaksi'] }}</div>
            <div class="summary-cell summary-value">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="summary-cell summary-value">{{ $summary['total_cash'] > 0 ? 'Rp ' . number_format($summary['total_cash'], 0, ',', '.') : '-' }}</div>
            <div class="summary-cell summary-value">{{ $summary['total_qris'] > 0 ? 'Rp ' . number_format($summary['total_qris'], 0, ',', '.') : '-' }}</div>
            <div class="summary-cell summary-value">{{ $summary['total_transfer'] > 0 ? 'Rp ' . number_format($summary['total_transfer'], 0, ',', '.') : '-' }}</div>
        </div>
    </div>

    @if($transaksi->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode Transaksi</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 15%;">Pegawai</th>
                <th style="width: 15%;">Metode</th>
                <th style="width: 20%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $item)
            <tr>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                <td>{{ $item->pegawai->nama ?? '-' }}</td>
                <td class="text-center">
                    @if($item->metode_pembayaran === 'cash')
                        Cash
                    @elseif($item->metode_pembayaran === 'qris')
                        QRIS
                    @elseif($item->metode_pembayaran === 'transfer')
                        Transfer
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>Tidak ada data transaksi untuk periode yang dipilih.</p>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem POS & Referral Sebelas Coffee</p>
        <p>© {{ date('Y') }} Sebelas Coffee. All rights reserved.</p>
    </div>
</body>
</html>
