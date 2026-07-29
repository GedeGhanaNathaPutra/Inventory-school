<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Serah Terima Barang</h2>
    </x-slot>

    <div x-data="{ showCreate: false }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 px-4 py-2 bg-destructive/10 border border-destructive/30 text-destructive rounded">{{ session('error') }}</div>
            @endif

            @if (in_array(Auth::user()->role, ['waka_sarpras', 'ka_tu']))
                <button @@click="showCreate = true" class="inline-block mb-4 px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">+ Buat Serah Terima</button>
            @endif

            <div class="glass-card p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-2 px-1">No. BA</th>
                                <th class="py-2 px-1">Dari</th>
                                <th class="py-2 px-1">Ke</th>
                                <th class="py-2 px-1">Tanggal</th>
                                <th class="py-2 px-1">Status</th>
                                <th class="py-2 px-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($serahTerimas as $st)
                                <tr class="border-b hover:bg-muted">
                                    <td class="py-2 px-1 font-mono">{{ $st->nomor_berita_acara }}</td>
                                    <td class="py-2 px-1">{{ $st->dariUser?->name }}</td>
                                    <td class="py-2 px-1">{{ $st->keUser?->name }}</td>
                                    <td class="py-2 px-1">{{ $st->tanggal_serah_terima }}</td>
                                    <td class="py-2 px-1">{{ $st->status }}</td>
                                    <td class="py-2 px-1">
                                        <a href="{{ route('serah-terima.show', $st) }}" class="text-primary hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-center text-muted-foreground">Belum ada serah terima.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $serahTerimas->links() }}</div>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreate" class="fixed inset-0 z-50 flex items-start justify-center px-4 py-10 sm:px-0" x-cloak>
            <div class="fixed inset-0 bg-black/50" @@click="showCreate = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">Buat Serah Terima</h3>
                <form method="POST" action="{{ route('serah-terima.store') }}">
                    @csrf
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium">Penerima</label>
                            <select name="ke_user_id" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" @selected(old('ke_user_id') == $u->id)>{{ $u->name }} ({{ $u->role }})</option>
                                @endforeach
                            </select>
                            @error('ke_user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Ruangan Tujuan</label>
                            <select name="ruangan_tujuan_id" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($ruangans as $r)
                                    <option value="{{ $r->id }}" @selected(old('ruangan_tujuan_id') == $r->id)>{{ $r->nama_ruangan }}</option>
                                @endforeach
                            </select>
                            @error('ruangan_tujuan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Tanggal Serah Terima</label>
                            <input type="date" name="tanggal_serah_terima" value="{{ old('tanggal_serah_terima', now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            @error('tanggal_serah_terima') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Catatan</label>
                        <textarea name="catatan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="2">{{ old('catatan') }}</textarea>
                    </div>
                    <hr class="my-4">
                    <h3 class="font-semibold mb-2">Daftar Barang</h3>
                    <div id="serah-items-wrapper">
                        <div class="item-row grid grid-cols-4 gap-2 mb-2">
                            <select name="items[0][barang_id]" class="border rounded px-2 py-1 text-sm" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barangs as $b)
                                    <option value="{{ $b->id }}">{{ $b->kode_barang }} — {{ $b->nama_barang }} ({{ $b->kuantitas }} {{ $b->nama_satuan }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[0][jumlah]" placeholder="Jumlah" class="border rounded px-2 py-1 text-sm" min="1" required>
                            <select name="items[0][kondisi_saat_serah_terima]" class="border rounded px-2 py-1 text-sm" required>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_sedang">Rusak Sedang</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                            <button type="button" class="remove-item px-2 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">Hapus</button>
                        </div>
                    </div>
                    <button type="button" id="add-item-serah" class="mb-4 px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">+ Tambah Barang</button>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Simpan Draft</button>
                        <button type="button" @@click="showCreate = false" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            let itemIndex = 1;
            const addBtn = document.getElementById('add-item-serah');
            if (!addBtn) return;
            addBtn.addEventListener('click', function () {
                const wrapper = document.getElementById('serah-items-wrapper');
                const row = document.createElement('div');
                row.className = 'item-row grid grid-cols-4 gap-2 mb-2';
                row.innerHTML = `
                    <select name="items[${itemIndex}][barang_id]" class="border rounded px-2 py-1 text-sm" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $b)
                        <option value="{{ $b->id }}">{{ $b->kode_barang }} — {{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[${itemIndex}][jumlah]" placeholder="Jumlah" class="border rounded px-2 py-1 text-sm" min="1" required>
                    <select name="items[${itemIndex}][kondisi_saat_serah_terima]" class="border rounded px-2 py-1 text-sm" required>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_sedang">Rusak Sedang</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                    <button type="button" class="remove-item px-2 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">Hapus</button>
                `;
                wrapper.appendChild(row);
                itemIndex++;
            });
            document.getElementById('serah-items-wrapper').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.item-row').remove();
                }
            });
        })();
    </script>
    @endpush
</x-app-layout>
