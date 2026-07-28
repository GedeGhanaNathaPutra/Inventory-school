<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm">
                    <tr class="border-b"><td class="py-2 font-semibold w-48">Kode Barang</td><td class="py-2 font-mono">{{ $barang->kode_barang }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Tanggal Pembukuan</td><td class="py-2">{{ $barang->tanggal_pembukuan }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Nama Barang</td><td class="py-2">{{ $barang->nama_barang }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Keterangan Nomor/Ukuran</td><td class="py-2">{{ $barang->keterangan_nomor_ukuran ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Merek/Type</td><td class="py-2">{{ $barang->merek_type ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kuantitas</td><td class="py-2">{{ $barang->kuantitas }} {{ $barang->nama_satuan }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kategori</td><td class="py-2 uppercase">{{ $barang->kategori }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Jenis Barang</td><td class="py-2">{{ str_replace('_', ' ', $barang->jenis_barang) }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kelengkapan Dokumen</td><td class="py-2">{{ $barang->kelengkapan_dokumen ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kondisi</td><td class="py-2">{{ str_replace('_', ' ', $barang->kondisi) }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Harga</td><td class="py-2">{{ $barang->harga ? 'Rp ' . number_format($barang->harga, 2) : '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Ruangan</td><td class="py-2">{{ $barang->ruangan?->nama_ruangan ?? '-' }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Status</td><td class="py-2">{{ $barang->status }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Dicatat Oleh</td><td class="py-2">{{ $barang->dicatatOleh?->name ?? '-' }}</td></tr>
                    <tr><td class="py-2 font-semibold">Keterangan</td><td class="py-2">{{ $barang->keterangan ?? '-' }}</td></tr>
                </table>

                <div class="mt-6 flex flex-wrap gap-2">
                    @if (in_array(Auth::user()->role, ['ka_tu', 'waka_sarpras', 'ka_prodi']) && $barang->status === 'aktif')
                        <a href="{{ route('kondisi.create', $barang) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lapor Kondisi</a>
                    @endif
                    <a href="{{ route('kondisi.history', $barang) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Riwayat Kondisi</a>
                    @if (Auth::user()->role === 'ka_tu' && $barang->status === 'aktif')
                        <a href="{{ route('barang.edit', $barang) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                    @endif
                    <a href="{{ route('barang.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
