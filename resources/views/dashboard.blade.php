<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php $role = Auth::user()->role; @endphp

            @if ($role === 'kepsek')
                {{-- Kepsek: High-level overview --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-dashboard-card label="Total Barang Aktif" :value="$totalBarang" color="primary" />
                    <x-dashboard-card label="Total Nilai" :value="'Rp ' . number_format($totalNilai, 0, ',', '.')" color="primary" />
                    <x-dashboard-card label="Distribusi Selesai" :value="$distribusiSelesai . ' / ' . $totalDistribusi" color="success" />
                    <x-dashboard-card label="Pengajuan Menunggu" :value="$pengajuanMenunggu" color="warning" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <x-dashboard-panel title="Kondisi Barang">
                        <x-dashboard-stats-bar label="Baik" :value="$barangBaik" color="success" :total="$totalBarang" />
                        <x-dashboard-stats-bar label="Rusak" :value="$barangRusak" color="destructive" :total="$totalBarang" />
                    </x-dashboard-panel>

                    <x-dashboard-panel title="Kategori">
                        @foreach ($barangPerKategori as $kat => $total)
                            <div class="flex justify-between py-1 text-sm">
                                <span class="capitalize text-foreground">{{ $kat }}</span>
                                <span class="font-semibold text-foreground">{{ $total }}</span>
                            </div>
                        @endforeach
                    </x-dashboard-panel>
                </div>

                <x-dashboard-panel title="Pengajuan Terbaru">
                    <x-dashboard-table :headers="['Kode','Pengaju','Kategori','Status']">
                        @forelse ($recentPengajuan as $p)
                            <tr class="border-b border-border hover:bg-muted">
                                <td class="py-2 px-1 font-mono text-foreground">{{ $p->kode_pengajuan }}</td>
                                <td class="py-2 px-1 text-foreground">{{ $p->diajukanOleh?->name }}</td>
                                <td class="py-2 px-1 uppercase text-foreground">{{ $p->kategori }}</td>
                                <td class="py-2 px-1"><x-dashboard-badge :label="str_replace('_', ' ', $p->status)" :type="$p->status === 'ditolak' ? 'destructive' : ($p->status === 'disetujui' || $p->status === 'selesai' ? 'success' : 'warning')" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-muted-foreground">Belum ada pengajuan.</td></tr>
                        @endforelse
                    </x-dashboard-table>
                </x-dashboard-panel>

            @elseif ($role === 'waka_sarpras')
                {{-- Waka: Procurement & handover --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-dashboard-card label="Total Distribusi" :value="$totalDistribusi" color="primary" />
                    <x-dashboard-card label="Selesai" :value="$distribusiSelesai" color="success" />
                    <x-dashboard-card label="Draft" :value="$distribusiDraft" color="warning" />
                    <x-dashboard-card label="Pengajuan Diajukan" :value="$pengajuanStats['diajukan'] ?? 0" color="destructive" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <x-dashboard-panel title="Status Pengajuan">
                        @foreach (['diajukan','diteruskan_rapbs','disetujui','ditolak','dibelanjakan','diserahkan_waka','diserahkan_pengguna','selesai'] as $s)
                            @if (($pengajuanStats[$s] ?? 0) > 0)
                                <div class="flex justify-between py-1 text-sm">
                                    <span class="text-foreground">{{ str_replace('_', ' ', $s) }}</span>
                                    <x-dashboard-badge :label="$pengajuanStats[$s] ?? 0" :type="$s === 'ditolak' ? 'destructive' : ($s === 'selesai' ? 'success' : 'warning')" />
                                </div>
                            @endif
                        @endforeach
                    </x-dashboard-panel>

                    <x-dashboard-panel title="Serah Terima Terbaru">
                        <x-dashboard-table :headers="['BA','Dari','Ke','Status']">
                            @forelse ($recentSerahTerima as $st)
                                <tr class="border-b border-border hover:bg-muted">
                                    <td class="py-2 px-1 font-mono text-foreground">{{ $st->nomor_berita_acara }}</td>
                                    <td class="py-2 px-1 text-foreground">{{ $st->dariUser?->name }}</td>
                                    <td class="py-2 px-1 text-foreground">{{ $st->keUser?->name }}</td>
                                    <td class="py-2 px-1"><x-dashboard-badge :label="$st->status" :type="$st->status === 'selesai' ? 'success' : 'warning'" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-muted-foreground">Belum ada serah terima.</td></tr>
                            @endforelse
                        </x-dashboard-table>
                    </x-dashboard-panel>
                </div>

            @elseif ($role === 'ka_tu')
                {{-- Ka.TU: Master data stats --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-dashboard-card label="Total Barang" :value="$totalBarang" color="primary" />
                    <x-dashboard-card label="Ruangan" :value="$totalRuangan" color="primary" />
                    <x-dashboard-card label="Program Studi" :value="$totalProdi" color="primary" />
                    <x-dashboard-card label="User" :value="$totalUser" color="primary" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <x-dashboard-panel title="Kondisi Barang">
                        @foreach ($kondisiBreakdown as $kond => $total)
                            <div class="flex justify-between py-1 text-sm">
                                <span class="text-foreground">{{ str_replace('_', ' ', $kond) }}</span>
                                <span class="font-semibold text-foreground">{{ $total }}</span>
                            </div>
                        @endforeach
                    </x-dashboard-panel>

                    <x-dashboard-panel title="Kategori & Jenis Barang">
                        <x-dashboard-table :headers="['Kategori','Jenis','Jumlah']">
                            @foreach ($barangByKategori as $bk)
                                <tr class="border-b border-border">
                                    <td class="py-1 px-1 uppercase text-foreground">{{ $bk->kategori }}</td>
                                    <td class="py-1 px-1 text-foreground">{{ str_replace('_', ' ', $bk->jenis_barang) }}</td>
                                    <td class="py-1 px-1 font-semibold text-foreground">{{ $bk->total }}</td>
                                </tr>
                            @endforeach
                        </x-dashboard-table>
                    </x-dashboard-panel>
                </div>

                @if ($stokMenipis->count())
                    <x-dashboard-panel title="Stok Menipis (≤ 2)">
                        <x-dashboard-table :headers="['Barang','Kode','Qty']">
                            @foreach ($stokMenipis as $b)
                                <tr class="border-b border-border hover:bg-muted">
                                    <td class="py-2 px-1 text-foreground">{{ $b->nama_barang }}</td>
                                    <td class="py-2 px-1 font-mono text-muted-foreground">{{ $b->kode_barang }}</td>
                                    <td class="py-2 px-1 font-semibold text-destructive">{{ $b->kuantitas }} {{ $b->nama_satuan }}</td>
                                </tr>
                            @endforeach
                        </x-dashboard-table>
                    </x-dashboard-panel>
                @endif

            @elseif ($role === 'ka_prodi')
                {{-- Ka.Prodi: Department-scoped --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <x-dashboard-card label="Barang di Prodi" :value="$barangDiProdi" color="primary" />
                    <x-dashboard-card label="Baik" :value="$barangBaik" color="success" />
                    <x-dashboard-card label="Rusak" :value="$barangRusak" color="destructive" />
                    <x-dashboard-card label="Laporan Kondisi" :value="$totalLaporan" color="warning" />
                </div>

                <x-dashboard-panel title="Laporan Kondisi Terbaru">
                    <x-dashboard-table :headers="['Barang','Kondisi','Pelapor','Tanggal']">
                        @forelse ($recentKondisi as $k)
                            <tr class="border-b border-border hover:bg-muted">
                                <td class="py-2 px-1 text-foreground">{{ $k->barang?->nama_barang }}</td>
                                <td class="py-2 px-1">{{ str_replace('_', ' ', $k->kondisi_setelah) }}</td>
                                <td class="py-2 px-1 text-foreground">{{ $k->dilaporkanOleh?->name }}</td>
                                <td class="py-2 px-1 text-muted-foreground">{{ $k->created_at?->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-center text-muted-foreground">Belum ada laporan kondisi.</td></tr>
                        @endforelse
                    </x-dashboard-table>
                </x-dashboard-panel>

            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-muted-foreground">
                <div class="bg-background border border-border rounded-lg p-4 text-center">
                    <a href="{{ route('barang.index') }}" class="text-primary hover:underline font-medium">Data Barang</a>
                </div>
                @if ($role !== 'ka_prodi')
                    <div class="bg-background border border-border rounded-lg p-4 text-center">
                        <a href="{{ route('laporan.index') }}" class="text-primary hover:underline font-medium">Laporan</a>
                    </div>
                @endif
                <div class="bg-background border border-border rounded-lg p-4 text-center">
                    <a href="{{ route('pengajuan.index') }}" class="text-primary hover:underline font-medium">Pengajuan</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
