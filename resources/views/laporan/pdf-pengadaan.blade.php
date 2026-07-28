<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Laporan Status Pengadaan</title><style>body{font-family:sans-serif;font-size:11px;}table{width:100%;border-collapse:collapse;}th,td{border:1px solid #333;padding:4px;text-align:left;}th{background:#eee;}</style></head>
<body><h2>Laporan Status Pengadaan Barang</h2>
<table><thead><tr><th>Kode</th><th>Kategori</th><th>Pengaju</th><th>Status</th><th>Item</th><th>Tanggal</th></tr></thead>
<tbody>@foreach ($pengajuans as $p)<tr><td>{{ $p->kode_pengajuan }}</td><td>{{ strtoupper($p->kategori) }}</td><td>{{ $p->diajukanOleh?->name }}</td><td>{{ str_replace('_',' ',$p->status) }}</td><td>{{ $p->items->count() }}</td><td>{{ $p->created_at?->format('Y-m-d') }}</td></tr>@endforeach</tbody></table>
</body></html>
