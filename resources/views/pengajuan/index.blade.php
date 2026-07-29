<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Pengajuan Barang</h2>
    </x-slot>

    <div x-data="{ showCreate: false }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            @if (in_array(Auth::user()->role, ['ka_prodi', 'waka_sarpras']))
                <button @@click="showCreate = true" class="inline-block mb-4 px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">+ Ajukan Barang</button>
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
                <button type="submit" class="px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">Filter</button>
            </form>

            <div class="glass-card p-6">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left"><th class="py-2 px-1">Kode</th><th class="py-2 px-1">Kategori</th><th class="py-2 px-1">Pengaju</th><th class="py-2 px-1">Status</th><th class="py-2 px-1">Item</th><th class="py-2 px-1">Tgl</th><th class="py-2 px-1"></th></tr></thead>
                        <tbody>
                            @forelse ($pengajuans as $p)
                                <tr class="border-b hover:bg-muted">
                                    <td class="py-2 px-1 font-mono">{{ $p->kode_pengajuan }}</td>
                                    <td class="py-2 px-1 uppercase">{{ $p->kategori }}</td>
                                    <td class="py-2 px-1">{{ $p->diajukanOleh?->name }}</td>
                                    <td class="py-2 px-1">{{ str_replace('_', ' ', $p->status) }}</td>
                                    <td class="py-2 px-1">{{ $p->items->count() }}</td>
                                    <td class="py-2 px-1">{{ $p->created_at?->format('Y-m-d') }}</td>
                                    <td class="py-2 px-1"><a href="{{ route('pengajuan.show', $p) }}" class="text-primary hover:underline">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-4 text-center text-muted-foreground">Belum ada pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $pengajuans->links() }}</div>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreate" class="fixed inset-0 z-50 flex items-start justify-center px-4 py-10 sm:px-0" x-cloak>
            <div class="fixed inset-0 bg-black/50" @@click="showCreate = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">Ajukan Barang</h3>
                <form method="POST" action="{{ route('pengajuan.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Kategori</label>
                        <select name="kategori" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="bos" @selected(old('kategori') === 'bos')>BOS</option>
                            <option value="komite" @selected(old('kategori') === 'komite')>Komite</option>
                        </select>
                        @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Catatan / Alasan</label>
                        <textarea name="catatan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="2">{{ old('catatan') }}</textarea>
                    </div>
                    <hr class="my-4">
                    <h3 class="font-semibold mb-2">Daftar Barang</h3>
                    <div id="items-wrapper">
                        <div class="item-row grid grid-cols-5 gap-2 mb-2">
                            <input type="text" name="items[0][nama_barang]" placeholder="Nama barang" class="border rounded px-2 py-1 text-sm col-span-2" required>
                            <input type="number" name="items[0][jumlah]" placeholder="Jumlah" class="border rounded px-2 py-1 text-sm" min="1" required>
                            <input type="text" name="items[0][satuan]" placeholder="Satuan" class="border rounded px-2 py-1 text-sm" required>
                            <input type="number" step="0.01" name="items[0][estimasi_harga]" placeholder="Estimasi harga" class="border rounded px-2 py-1 text-sm">
                            <button type="button" class="remove-item px-2 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">Hapus</button>
                        </div>
                    </div>
                    <button type="button" id="add-item-pengajuan" class="mb-4 px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">+ Tambah Barang</button>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Ajukan</button>
                        <button type="button" @@click="showCreate = false" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            let idx = 1;
            const addBtn = document.getElementById('add-item-pengajuan');
            if (!addBtn) return;
            addBtn.addEventListener('click', function () {
                const w = document.getElementById('items-wrapper');
                const r = document.createElement('div'); r.className = 'item-row grid grid-cols-5 gap-2 mb-2';
                r.innerHTML = `
                    <input type="text" name="items[${idx}][nama_barang]" placeholder="Nama barang" class="border rounded px-2 py-1 text-sm col-span-2" required>
                    <input type="number" name="items[${idx}][jumlah]" placeholder="Jumlah" class="border rounded px-2 py-1 text-sm" min="1" required>
                    <input type="text" name="items[${idx}][satuan]" placeholder="Satuan" class="border rounded px-2 py-1 text-sm" required>
                    <input type="number" step="0.01" name="items[${idx}][estimasi_harga]" placeholder="Harga" class="border rounded px-2 py-1 text-sm">
                    <button type="button" class="remove-item px-2 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">Hapus</button>
                `;
                w.appendChild(r); idx++;
            });
            document.getElementById('items-wrapper').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-item')) e.target.closest('.item-row').remove();
            });
        })();
    </script>
    @endpush
</x-app-layout>
