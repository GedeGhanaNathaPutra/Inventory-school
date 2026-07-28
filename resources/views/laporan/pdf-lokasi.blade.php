<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Laporan Per Lokasi</title><style>body{font-family:sans-serif;font-size:11px;}table{width:100%;border-collapse:collapse;margin-bottom:12px;}th,td{border:1px solid #333;padding:4px;text-align:left;}th{background:#eee;}h3{margin-top:16px;}h4{margin:8px 0 4px;}</style></head>
<body><h2>Laporan Barang Per Lokasi / Prodi</h2>
@foreach ($prodis as $prodi)
    <h3>{{ $prodi->nama_prodi }}</h3>
    @foreach ($prodi->ruangans as $ruangan)
        <h4>{{ $ruangan->nama_ruangan }} ({{ $ruangan->barangs->count() }} item)</h4>
        @if ($ruangan->barangs->isNotEmpty())
            <table><thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Qty</th><th>Kondisi</th></tr></thead>
            <tbody>@foreach ($ruangan->barangs as $b)<tr><td>{{ $b->kode_barang }}</td><td>{{ $b->nama_barang }}</td><td>{{ strtoupper($b->kategori) }}</td><td>{{ $b->kuantitas }}</td><td>{{ str_replace('_',' ',$b->kondisi) }}</td></tr>@endforeach</tbody></table>
        @endif
    @endforeach
@endforeach
</body></html>
