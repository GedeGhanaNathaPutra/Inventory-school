<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Detail Pengajuan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            <div class="glass-card p-6 mb-4">
                <table class="w-full text-sm">
                    <tr class="border-b"><td class="py-2 font-semibold w-48">Kode Pengajuan</td><td class="py-2 font-mono">{{ $pengajuan->kode_pengajuan }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Kategori</td><td class="py-2 uppercase">{{ $pengajuan->kategori }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Diajukan Oleh</td><td class="py-2">{{ $pengajuan->diajukanOleh?->name }}</td></tr>
                    <tr class="border-b"><td class="py-2 font-semibold">Status</td><td class="py-2">{{ str_replace('_', ' ', $pengajuan->status) }}</td></tr>
                    <tr><td class="py-2 font-semibold">Catatan</td><td class="py-2">{{ $pengajuan->catatan ?? '-' }}</td></tr>
                </table>

                <h3 class="font-semibold mt-6 mb-2">Daftar Barang</h3>
                <table class="w-full text-sm">
                    <thead><tr class="border-b text-left"><th class="py-1">Nama</th><th class="py-1">Jumlah</th><th class="py-1">Satuan</th><th class="py-1">Estimasi</th><th class="py-1">Barang ID</th></tr></thead>
                    <tbody>
                        @foreach ($pengajuan->items as $item)
                            <tr class="border-b">
                                <td class="py-1">{{ $item->nama_barang }}</td>
                                <td class="py-1">{{ $item->jumlah }}</td>
                                <td class="py-1">{{ $item->satuan }}</td>
                                <td class="py-1">{{ $item->estimasi_harga ? 'Rp ' . number_format($item->estimasi_harga) : '-' }}</td>
                                <td class="py-1">{{ $item->barang_id ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Action buttons based on current status + role --}}
                <div class="mt-6 flex flex-wrap gap-2">
                    @php $role = Auth::user()->role; $s = $pengajuan->status; @endphp

                    @if ($s === 'diajukan' && $role === 'waka_sarpras')
                        <form method="POST" action="{{ route('pengajuan.forward-to-rapbs', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Teruskan ke RAPBS</button>
                        </form>
                    @endif

                    @if ($s === 'diteruskan_rapbs' && $role === 'kepsek')
                        <form method="POST" action="{{ route('pengajuan.approve', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-success text-success-foreground rounded hover:bg-success/90" onclick="return confirm('Setujui anggaran ini?')">Setujui</button>
                        </form>
                        <form method="POST" action="{{ route('pengajuan.reject', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-destructive text-destructive-foreground rounded hover:bg-destructive/90" onclick="return confirm('Tolak anggaran ini?')">Tolak</button>
                        </form>
                    @endif

                    @if ($s === 'disetujui' && in_array($role, ['waka_sarpras', 'ka_tu']))
                        <form method="POST" action="{{ route('pengajuan.mark-dibelanjakan', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Sudah Dibelanjakan</button>
                        </form>
                    @endif

                    @if ($s === 'dibelanjakan' && $role === 'waka_sarpras')
                        <form method="POST" action="{{ route('pengajuan.mark-diserahkan-waka', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">Terima ke Waka</button>
                        </form>
                    @endif

                    @if ($s === 'diserahkan_waka' && $role === 'waka_sarpras')
                        <form method="POST" action="{{ route('pengajuan.mark-diserahkan-pengguna', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Serahkan ke Pengguna</button>
                        </form>
                    @endif

                    @if ($s === 'diserahkan_pengguna' && $role === 'ka_tu')
                        <form method="POST" action="{{ route('pengajuan.mark-didata', $pengajuan) }}">@csrf
                            <button class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700" onclick="return confirm('Catat barang ke data master? Barang baru akan dibuat otomatis.')">Catat ke Data Barang</button>
                        </form>
                    @endif

                    <a href="{{ route('pengajuan.index') }}" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Kembali</a>
                </div>
            </div>

            {{-- Logs --}}
            <div class="glass-card p-4">
                <h3 class="font-semibold mb-2">Riwayat Status</h3>
                @forelse ($pengajuan->logs as $log)
                    <div class="flex justify-between text-sm border-b py-1">
                        <span>{{ str_replace('_', ' ', $log->status) }}</span>
                        <span class="text-muted-foreground">{{ $log->updatedBy?->name }} — {{ $log->keterangan ?? '' }}</span>
                    </div>
                @empty
                    <p class="text-sm text-muted-foreground">Belum ada riwayat.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
