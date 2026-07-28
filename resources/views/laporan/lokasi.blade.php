<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Per Lokasi / Prodi</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex gap-2">
                <a href="{{ route('laporan.lokasi', ['export' => 'pdf']) }}" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">PDF</a>
                <a href="{{ route('laporan.lokasi', ['export' => 'excel']) }}" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Excel</a>
            </div>

            @foreach ($prodis as $prodi)
                <div class="bg-white rounded shadow-sm p-4 mb-4">
                    <h3 class="font-semibold mb-2">{{ $prodi->nama_prodi }}</h3>
                    @foreach ($prodi->ruangans as $ruangan)
                        <div class="ml-4 mb-2">
                            <h4 class="text-sm font-medium text-gray-600">{{ $ruangan->nama_ruangan }} ({{ $ruangan->barangs->count() }} item)</h4>
                            @if ($ruangan->barangs->isNotEmpty())
                                <table class="w-full text-sm mt-1">
                                    <thead><tr class="border-b text-left"><th class="py-1">Kode</th><th class="py-1">Nama</th><th class="py-1">Kategori</th><th class="py-1">Qty</th><th class="py-1">Kondisi</th></tr></thead>
                                    <tbody>
                                        @foreach ($ruangan->barangs as $b)
                                            <tr class="border-b"><td class="py-1 font-mono">{{ $b->kode_barang }}</td><td class="py-1">{{ $b->nama_barang }}</td><td class="py-1 uppercase">{{ $b->kategori }}</td><td class="py-1">{{ $b->kuantitas }}</td><td class="py-1">{{ str_replace('_', ' ', $b->kondisi) }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-xs text-gray-400 ml-2">Tidak ada barang.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
