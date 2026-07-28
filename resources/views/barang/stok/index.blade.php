<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stok Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            @if (Auth::user()->role === 'ka_tu')
                <a href="{{ route('stok.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Tambah Mutasi</a>
            @endif

            <form method="GET" class="mb-4 flex gap-2">
                <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}" class="border rounded px-3 py-1 text-sm">
                <select name="kategori" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua</option>
                    <option value="bos" @selected(request('kategori') === 'bos')>BOS</option>
                    <option value="komite" @selected(request('kategori') === 'komite')>Komite</option>
                </select>
                <button type="submit" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Filter</button>
            </form>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-2 px-1">Kode</th>
                                <th class="py-2 px-1">Nama Barang</th>
                                <th class="py-2 px-1">Stok Awal</th>
                                <th class="py-2 px-1">Masuk</th>
                                <th class="py-2 px-1">Keluar</th>
                                <th class="py-2 px-1">Stok Akhir</th>
                                <th class="py-2 px-1">Ruangan</th>
                                <th class="py-2 px-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangs as $b)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-1 font-mono">{{ $b->kode_barang }}</td>
                                    <td class="py-2 px-1">{{ $b->nama_barang }}</td>
                                    <td class="py-2 px-1">{{ $b->kuantitas }}</td>
                                    <td class="py-2 px-1 text-green-600">{{ $b->stok_masuk }}</td>
                                    <td class="py-2 px-1 text-red-600">{{ $b->stok_keluar }}</td>
                                    <td class="py-2 px-1 font-semibold">{{ $b->stok_akhir }}</td>
                                    <td class="py-2 px-1">{{ $b->ruangan?->nama_ruangan ?? '-' }}</td>
                                    <td class="py-2 px-1"><a href="{{ route('stok.show', $b) }}" class="text-blue-600 hover:underline">Mutasi</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="py-4 text-center text-gray-500">Belum ada data barang.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $barangs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
