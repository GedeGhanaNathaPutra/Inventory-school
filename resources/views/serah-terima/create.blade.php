<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Buat Serah Terima</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card p-6">
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
                    <div id="items-wrapper">
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
                    <button type="button" id="add-item" class="mb-4 px-3 py-1 bg-secondary text-foreground rounded text-sm hover:bg-accent">+ Tambah Barang</button>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Simpan Draft</button>
                        <a href="{{ route('serah-terima.index') }}" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function () {
            const wrapper = document.getElementById('items-wrapper');
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
        document.getElementById('items-wrapper').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
            }
        });
    </script>
    @endpush
</x-app-layout>
