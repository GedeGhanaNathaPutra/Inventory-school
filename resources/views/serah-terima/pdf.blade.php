<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara {{ $serahTerima->nomor_berita_acara }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 4px; }
        h2 { text-align: center; font-size: 14px; margin-top: 0; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #eee; }
        .info { margin-top: 24px; }
        .info td { border: none; padding: 2px 0; }
        .ttd { margin-top: 40px; display: flex; justify-content: space-between; }
        .ttd div { text-align: center; width: 45%; }
        .ttd .line { margin-top: 48px; border-top: 1px solid #333; padding-top: 4px; }
    </style>
</head>
<body>
    <h1>BERITA ACARA SERAH TERIMA BARANG</h1>
    <h2>No. {{ $serahTerima->nomor_berita_acara }}</h2>

    <table class="info">
        <tr><td style="width:120px"><strong>Tanggal</strong></td><td>: {{ $serahTerima->tanggal_serah_terima }}</td></tr>
        <tr><td><strong>Dari</strong></td><td>: {{ $serahTerima->dariUser?->name }}</td></tr>
        <tr><td><strong>Kepada</strong></td><td>: {{ $serahTerima->keUser?->name }}</td></tr>
        <tr><td><strong>Ruangan</strong></td><td>: {{ $serahTerima->ruanganTujuan?->nama_ruangan ?? '-' }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($serahTerima->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->barang?->kode_barang }}</td>
                    <td>{{ $item->barang?->nama_barang }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ str_replace('_', ' ', $item->kondisi_saat_serah_terima) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($serahTerima->catatan)
        <p><strong>Catatan:</strong> {{ $serahTerima->catatan }}</p>
    @endif

    <div class="ttd">
        <div>
            <p><strong>Yang Menyerahkan,</strong></p>
            <div class="line">{{ $serahTerima->dariUser?->name }}</div>
        </div>
        <div>
            <p><strong>Yang Menerima,</strong></p>
            <div class="line">{{ $serahTerima->keUser?->name }}</div>
        </div>
    </div>
</body>
</html>
