<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengajuan Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            @if (in_array(Auth::user()->role, ['ka_prodi', 'waka_sarpras']))
                <a href="{{ route('pengajuan.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Ajukan Barang</a>
            @endif

            <form method="GET" class="mb-4 flex gap-2">
                <select name="status" class="border rounded px-3 py-1 text-sm">
                    <option value="">Semua Status</option>
                    @foreach (['diajukan','diteruskan_rapbs','disetujui','ditolak','dibelanjakan','diserahkan_waka','diserahkan_pengguna','selesai'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ str_replace('_', ' ', $s) }}</option>
                    @endforeach
                </select>
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
                        <thead><tr class="border-b text-left"><th class="py-2 px-1">Kode</th><th class="py-2 px-1">Kategori</th><th class="py-2 px-1">Pengaju</th><th class="py-2 px-1">Status</th><th class="py-2 px-1">Item</th><th class="py-2 px-1">Tgl</th><th class="py-2 px-1"></th></tr></thead>
                        <tbody>
                            @forelse ($pengajuans as $p)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-1 font-mono">{{ $p->kode_pengajuan }}</td>
                                    <td class="py-2 px-1 uppercase">{{ $p->kategori }}</td>
                                    <td class="py-2 px-1">{{ $p->diajukanOleh?->name }}</td>
                                    <td class="py-2 px-1">{{ str_replace('_', ' ', $p->status) }}</td>
                                    <td class="py-2 px-1">{{ $p->items->count() }}</td>
                                    <td class="py-2 px-1">{{ $p->created_at?->format('Y-m-d') }}</td>
                                    <td class="py-2 px-1"><a href="{{ route('pengajuan.show', $p) }}" class="text-blue-600 hover:underline">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-4 text-center text-gray-500">Belum ada pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $pengajuans->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
