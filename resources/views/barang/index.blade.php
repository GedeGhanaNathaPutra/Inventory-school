<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (Auth::user()->role === 'ka_tu')
                        <a href="{{ route('barang.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Tambah Barang</a>
                    @endif

                    <form method="GET" class="mb-4 flex flex-wrap gap-2">
                        <input type="text" name="search" placeholder="Cari nama/kode/merek..." value="{{ request('search') }}" class="border rounded px-3 py-1 text-sm">
                        <select name="kategori" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Kategori</option>
                            <option value="bos" @selected(request('kategori') === 'bos')>BOS</option>
                            <option value="komite" @selected(request('kategori') === 'komite')>Komite</option>
                        </select>
                        <select name="jenis_barang" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Jenis</option>
                            <option value="inventaris" @selected(request('jenis_barang') === 'inventaris')>Inventaris</option>
                            <option value="non_inventaris" @selected(request('jenis_barang') === 'non_inventaris')>Non Inventaris</option>
                        </select>
                        <select name="kondisi" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Kondisi</option>
                            <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                            <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                            <option value="rusak_sedang" @selected(request('kondisi') === 'rusak_sedang')>Rusak Sedang</option>
                            <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
                        </select>
                        <select name="ruangan_id" class="border rounded px-3 py-1 text-sm">
                            <option value="">Semua Ruangan</option>
                            @foreach ($ruangans as $r)
                                <option value="{{ $r->id }}" @selected(request('ruangan_id') == $r->id)>{{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="tahun" placeholder="Tahun" value="{{ request('tahun') }}" class="border rounded px-3 py-1 text-sm w-20">
                        <button type="submit" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Filter</button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="py-2 px-1">Kode</th>
                                    <th class="py-2 px-1">Nama Barang</th>
                                    <th class="py-2 px-1">Kategori</th>
                                    <th class="py-2 px-1">Jenis</th>
                                    <th class="py-2 px-1">Qty</th>
                                    <th class="py-2 px-1">Kondisi</th>
                                    <th class="py-2 px-1">Ruangan</th>
                                    <th class="py-2 px-1">Status</th>
                                    <th class="py-2 px-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang as $b)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-1 font-mono">{{ $b->kode_barang }}</td>
                                        <td class="py-2 px-1">{{ $b->nama_barang }}</td>
                                        <td class="py-2 px-1 uppercase">{{ $b->kategori }}</td>
                                        <td class="py-2 px-1">{{ str_replace('_', ' ', $b->jenis_barang) }}</td>
                                        <td class="py-2 px-1">{{ $b->kuantitas }} {{ $b->nama_satuan }}</td>
                                        <td class="py-2 px-1">{{ str_replace('_', ' ', $b->kondisi) }}</td>
                                        <td class="py-2 px-1">{{ $b->ruangan?->nama_ruangan ?? '-' }}</td>
                                        <td class="py-2 px-1">{{ $b->status }}</td>
                                        <td class="py-2 px-1">
                                            <a href="{{ route('barang.show', $b) }}" class="text-blue-600 hover:underline">Detail</a>
                                            @if (Auth::user()->role === 'ka_tu' && $b->status === 'aktif')
                                                <a href="{{ route('barang.edit', $b) }}" class="ml-2 text-yellow-600 hover:underline">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="py-4 text-center text-gray-500">Belum ada data barang.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $barang->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
