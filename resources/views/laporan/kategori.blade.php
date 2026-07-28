<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Per Kategori</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-4 flex gap-2 items-end">
                <select name="kategori" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua</option>
                    <option value="bos" @selected(request('kategori') === 'bos')>BOS</option>
                    <option value="komite" @selected(request('kategori') === 'komite')>Komite</option>
                </select>
                <button type="submit" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Tampilkan</button>
                <button type="submit" name="export" value="pdf" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">PDF</button>
                <button type="submit" name="export" value="excel" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Excel</button>
            </form>

            @foreach ($data as $kat => $items)
                <div class="bg-white rounded shadow-sm p-4 mb-4">
                    <h3 class="font-semibold mb-2 uppercase">{{ $kat }} ({{ $items->count() }} item)</h3>
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left"><th class="py-1">Kode</th><th class="py-1">Nama</th><th class="py-1">Jenis</th><th class="py-1">Qty</th><th class="py-1">Kondisi</th><th class="py-1">Ruangan</th></tr></thead>
                        <tbody>
                            @foreach ($items as $b)
                                <tr class="border-b"><td class="py-1 font-mono">{{ $b->kode_barang }}</td><td class="py-1">{{ $b->nama_barang }}</td><td class="py-1">{{ str_replace('_', ' ', $b->jenis_barang) }}</td><td class="py-1">{{ $b->kuantitas }}</td><td class="py-1">{{ str_replace('_', ' ', $b->kondisi) }}</td><td class="py-1">{{ $b->ruangan?->nama_ruangan ?? '-' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
