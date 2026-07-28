<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Laporan Per Kategori</title><style>body{font-family:sans-serif;font-size:11px;}table{width:100%;border-collapse:collapse;margin-bottom:16px;}th,td{border:1px solid #333;padding:4px;text-align:left;}th{background:#eee;}h3{margin-top:16px;}</style></head>
<body><h2>Laporan Barang Per Kategori</h2>
@foreach ($data as $kat => $items)
    <h3>{{ strtoupper($kat) }} ({{ $items->count() }} item)</h3>
    <table><thead><tr><th>Kode</th><th>Nama</th><th>Jenis</th><th>Qty</th><th>Kondisi</th><th>Ruangan</th></tr></thead>
    <tbody>@foreach ($items as $b)<tr><td>{{ $b->kode_barang }}</td><td>{{ $b->nama_barang }}</td><td>{{ str_replace('_',' ',$b->jenis_barang) }}</td><td>{{ $b->kuantitas }}</td><td>{{ str_replace('_',' ',$b->kondisi) }}</td><td>{{ $b->ruangan?->nama_ruangan ?? '-' }}</td></tr>@endforeach</tbody></table>
@endforeach
</body></html>
