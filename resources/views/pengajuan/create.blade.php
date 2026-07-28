<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajukan Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                    <button type="button" id="add-item" class="mb-4 px-3 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">+ Tambah Barang</button>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ajukan</button>
                        <a href="{{ route('pengajuan.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let idx = 1;
        document.getElementById('add-item').addEventListener('click', function () {
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
    </script>
    @endpush
</x-app-layout>
