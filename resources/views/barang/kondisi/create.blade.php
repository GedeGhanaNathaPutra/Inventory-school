<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lapor Kondisi Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4 text-sm">
                    Barang: <strong>{{ $barang->kode_barang }} — {{ $barang->nama_barang }}</strong><br>
                    Kondisi saat ini: <strong>{{ str_replace('_', ' ', $barang->kondisi) }}</strong>
                </p>

                <form method="POST" action="{{ route('kondisi.store', $barang) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Kondisi Baru</label>
                        <select name="kondisi" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="baik" @selected(old('kondisi') === 'baik')>Baik</option>
                            <option value="rusak_ringan" @selected(old('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                            <option value="rusak_sedang" @selected(old('kondisi') === 'rusak_sedang')>Rusak Sedang</option>
                            <option value="rusak_berat" @selected(old('kondisi') === 'rusak_berat')>Rusak Berat</option>
                        </select>
                        @error('kondisi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Keterangan</label>
                        <textarea name="keterangan" class="w-full border rounded px-3 py-2 mt-1 text-sm" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Tanggal Lapor</label>
                        <input type="date" name="tanggal_lapor" value="{{ old('tanggal_lapor', now()->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('tanggal_lapor') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <hr class="my-4">

                    <p class="text-sm text-gray-600 mb-3">
                        <strong>Upload Foto</strong> — Foto 3 arah <span class="text-red-500">wajib</span> jika kondisi bukan "Baik".
                    </p>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium">Foto Arah 1</label>
                            <input type="file" name="foto_1" accept="image/*" class="w-full mt-1 text-sm">
                            @error('foto_1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Foto Arah 2</label>
                            <input type="file" name="foto_2" accept="image/*" class="w-full mt-1 text-sm">
                            @error('foto_2') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Foto Arah 3</label>
                            <input type="file" name="foto_3" accept="image/*" class="w-full mt-1 text-sm">
                            @error('foto_3') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                        <a href="{{ route('barang.show', $barang) }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
