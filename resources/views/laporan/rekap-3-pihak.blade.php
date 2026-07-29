<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Rekap Data 3 Pihak</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Ringkasan --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="glass-card p-4 border-l-4 border-primary">
                    <p class="text-sm text-muted-foreground">Data Master (Ka. TU)</p>
                    <p class="text-2xl font-bold">{{ $totalBarang }}</p>
                    <p class="text-xs text-gray-400">total barang aktif</p>
                </div>
                <div class="glass-card p-4 border-l-4 border-success">
                    <p class="text-sm text-muted-foreground">Data Distribusi (Waka Sarpras)</p>
                    <p class="text-2xl font-bold">{{ $totalDistribusi }}</p>
                    <p class="text-xs text-gray-400">{{ $distribusiSelesai }} selesai / {{ $totalDistribusi - $distribusiSelesai }} draft</p>
                </div>
                <div class="glass-card p-4 border-l-4 border-warning">
                    <p class="text-sm text-muted-foreground">Data Pemakaian (Ka. Prodi)</p>
                    <p class="text-2xl font-bold">{{ $totalLaporanKondisi }}</p>
                    <p class="text-xs text-gray-400">total laporan kondisi</p>
                </div>
            </div>

            {{-- Sinkronisasi --}}
            <div class="glass-card p-4 mb-6">
                <h3 class="font-semibold mb-2">Sinkronisasi Data</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></span>
                        Barang sudah terdistribusi: <strong>{{ $barangTerdistribusi }}</strong>
                    </div>
                    <div>
                        @if ($belumTerdistribusi > 0)
                            <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-1"></span>
                            Barang belum terdistribusi: <strong class="text-red-600">{{ $belumTerdistribusi }}</strong>
                        @else
                            <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-1"></span>
                            Barang belum terdistribusi: <strong>0</strong>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Data Master --}}
                <div class="glass-card p-4">
                    <h3 class="font-semibold mb-3 text-blue-700"><svg class="inline-block w-5 h-5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg> Data Master (Ka. TU)</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-1">Kategori</th>
                                <th class="py-1">Jenis</th>
                                <th class="py-1 text-right">Item</th>
                                <th class="py-1 text-right">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataMaster as $d)
                                <tr class="border-b">
                                    <td class="py-1 uppercase">{{ $d->kategori }}</td>
                                    <td class="py-1">{{ str_replace('_', ' ', $d->jenis_barang) }}</td>
                                    <td class="py-1 text-right">{{ $d->total }}</td>
                                    <td class="py-1 text-right">{{ $d->qty }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-2 text-muted-foreground">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Data Distribusi --}}
                <div class="glass-card p-4">
                    <h3 class="font-semibold mb-3 text-green-700"><svg class="inline-block w-5 h-5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg> Data Distribusi (Waka Sarpras)</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-1">Prodi</th>
                                <th class="py-1">Draft</th>
                                <th class="py-1">Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($distribusiPerProdi as $prodi => $items)
                                <tr class="border-b">
                                    <td class="py-1">{{ $prodi }}</td>
                                    <td class="py-1">{{ $items->where('status', 'draft')->sum('total') }}</td>
                                    <td class="py-1">{{ $items->where('status', 'selesai')->sum('total') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-2 text-muted-foreground">Belum ada distribusi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Data Pemakaian --}}
                <div class="glass-card p-4">
                    <h3 class="font-semibold mb-3 text-yellow-700"><svg class="inline-block w-5 h-5 mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Data Pemakaian (Ka. Prodi)</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b text-left">
                                <th class="py-1">Prodi</th>
                                <th class="py-1 text-right">Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pemakaianPerProdi as $prodi => $items)
                                <tr class="border-b">
                                    <td class="py-1">{{ $prodi }}</td>
                                    <td class="py-1 text-right">{{ $items->sum('total') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-2 text-muted-foreground">Belum ada laporan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
