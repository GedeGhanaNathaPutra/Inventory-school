<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Status Pengadaan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-4 flex gap-2 items-end">
                <select name="status" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua Status</option>
                    <option value="diajukan" @selected(request('status') === 'diajukan')>Diajukan</option>
                    <option value="diteruskan_rapbs" @selected(request('status') === 'diteruskan_rapbs')>Diteruskan RAPBS</option>
                    <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                    <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                    <option value="dibelanjakan" @selected(request('status') === 'dibelanjakan')>Dibelanjakan</option>
                    <option value="diserahkan_waka" @selected(request('status') === 'diserahkan_waka')>Diserahkan Waka</option>
                    <option value="diserahkan_pengguna" @selected(request('status') === 'diserahkan_pengguna')>Diserahkan Pengguna</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                </select>
                <select name="kategori" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua Kategori</option>
                    <option value="bos" @selected(request('kategori') === 'bos')>BOS</option>
                    <option value="komite" @selected(request('kategori') === 'komite')>Komite</option>
                </select>
                <button type="submit" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Tampilkan</button>
                <button type="submit" name="export" value="pdf" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">PDF</button>
                <button type="submit" name="export" value="excel" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Excel</button>
            </form>

            <div class="bg-white rounded shadow-sm p-4">
                <table class="w-full text-sm">
                    <thead><tr class="border-b text-left"><th class="py-1">Kode</th><th class="py-1">Kategori</th><th class="py-1">Pengaju</th><th class="py-1">Status</th><th class="py-1">Item</th><th class="py-1">Tanggal</th></tr></thead>
                    <tbody>
                        @forelse ($pengajuans as $p)
                            <tr class="border-b"><td class="py-1 font-mono">{{ $p->kode_pengajuan }}</td><td class="py-1 uppercase">{{ $p->kategori }}</td><td class="py-1">{{ $p->diajukanOleh?->name }}</td><td class="py-1">{{ str_replace('_', ' ', $p->status) }}</td><td class="py-1">{{ $p->items->count() }}</td><td class="py-1">{{ $p->created_at?->format('Y-m-d') }}</td></tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-center text-gray-500">Belum ada pengajuan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
