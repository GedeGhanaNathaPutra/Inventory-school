<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Kartu Inventaris {{ $ruangan->nama_ruangan }}</title><style>body{font-family:sans-serif;font-size:11px;}table{width:100%;border-collapse:collapse;margin-top:8px;}th,td{border:1px solid #333;padding:4px;text-align:left;}th{background:#eee;}h2,h3{text-align:center;}</style></head>
<body>
<h2>KARTU INVENTARIS RUANGAN</h2>
<h3>{{ $ruangan->nama_ruangan }} ({{ $ruangan->prodi?->nama_prodi ?? 'Umum' }})</h3>
<table>
<thead><tr><th>Nama Barang</th><th>Total</th><th>Baik</th><th>R. Ringan</th><th>R. Sedang</th><th>R. Berat</th><th>Keterangan</th><th>Kebutuhan</th></tr></thead>
<tbody>
@foreach ($barangGroup as $b)
@php $kb = $kebutuhans->get($b->nama_barang); @endphp
<tr>
    <td>{{ $b->nama_barang }}</td>
    <td>{{ $b->total }}</td>
    <td>{{ $b->kondisi_baik }}</td>
    <td>{{ $b->rusak_ringan }}</td>
    <td>{{ $b->rusak_sedang }}</td>
    <td>{{ $b->rusak_berat }}</td>
    <td>{{ $kb?->keterangan ?? '-' }}</td>
    <td>{{ $kb?->kebutuhan ?? '-' }}</td>
</tr>
@endforeach
</tbody>
</table>
</body></html>
