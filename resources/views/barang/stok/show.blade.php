<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Mutasi Stok</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4 text-sm">
                    Barang: <strong>{{ $barang->kode_barang }} — {{ $barang->nama_barang }}</strong><br>
                    Stok Awal: <strong>{{ $barang->kuantitas }}</strong> |
                    Masuk: <strong class="text-green-600">{{ $stokMasuk }}</strong> |
                    Keluar: <strong class="text-red-600">{{ $stokKeluar }}</strong> |
                    Stok Akhir: <strong>{{ $stokAkhir }}</strong>
                </p>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 px-1">Tanggal</th>
                            <th class="py-2 px-1">Jenis</th>
                            <th class="py-2 px-1 text-right">Jumlah</th>
                            <th class="py-2 px-1">Referensi</th>
                            <th class="py-2 px-1">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mutasis as $m)
                            <tr class="border-b">
                                <td class="py-2 px-1">{{ $m->tanggal }}</td>
                                <td class="py-2 px-1">
                                    @if ($m->jenis === 'masuk')
                                        <span class="text-green-600">Masuk</span>
                                    @else
                                        <span class="text-red-600">Keluar</span>
                                    @endif
                                </td>
                                <td class="py-2 px-1 text-right">{{ $m->jumlah }}</td>
                                <td class="py-2 px-1">{{ $m->referensi_tipe ? $m->referensi_tipe . '#' . $m->referensi_id : '-' }}</td>
                                <td class="py-2 px-1">{{ $m->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada mutasi stok.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <a href="{{ route('stok.index') }}" class="mt-4 inline-block px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 text-sm">Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
