<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('laporan.kategori') }}" class="bg-white rounded shadow-sm p-6 hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Per Kategori</h3>
                    <p class="text-sm text-gray-500">BOS / Komite</p>
                </a>
                <a href="{{ route('laporan.kondisi') }}" class="bg-white rounded shadow-sm p-6 hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Per Kondisi</h3>
                    <p class="text-sm text-gray-500">Baik / Rusak Ringan / Sedang / Berat</p>
                </a>
                <a href="{{ route('laporan.lokasi') }}" class="bg-white rounded shadow-sm p-6 hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Per Lokasi / Prodi</h3>
                    <p class="text-sm text-gray-500">Barang per ruangan & prodi</p>
                </a>
                <a href="{{ route('laporan.pengadaan') }}" class="bg-white rounded shadow-sm p-6 hover:shadow-md transition">
                    <h3 class="font-semibold text-lg">Status Pengadaan</h3>
                    <p class="text-sm text-gray-500">Progress alur pengajuan barang</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
