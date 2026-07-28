<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Serah Terima Barang</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            @if (in_array(Auth::user()->role, ['waka_sarpras', 'ka_tu']))
                <a href="{{ route('serah-terima.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Buat Serah Terima</a>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-1 font-mono">{{ $st->nomor_berita_acara }}</td>
                                    <td class="py-2 px-1">{{ $st->dariUser?->name }}</td>
                                    <td class="py-2 px-1">{{ $st->keUser?->name }}</td>
                                    <td class="py-2 px-1">{{ $st->tanggal_serah_terima }}</td>
                                    <td class="py-2 px-1">{{ $st->status }}</td>
                                    <td class="py-2 px-1">
                                        <a href="{{ route('serah-terima.show', $st) }}" class="text-blue-600 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-center text-gray-500">Belum ada serah terima.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $serahTerimas->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
