<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Kartu Inventaris: {{ $ruangan->nama_ruangan }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            <div class="glass-card p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-muted-foreground">Prodi: {{ $ruangan->prodi?->nama_prodi ?? 'Umum' }}</p>
                        <p class="text-sm text-muted-foreground">Total item: {{ $barangGroup->count() }} jenis barang</p>
                    </div>
                    <a href="{{ route('kartu.pdf', $ruangan) }}" class="px-3 py-1 bg-destructive text-destructive-foreground rounded text-sm hover:bg-destructive/90">PDF</a>
                </div>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2 px-1">Nama Barang</th>
                            <th class="py-2 px-1 text-right">Total</th>
                            <th class="py-2 px-1 text-right text-green-600">Baik</th>
                            <th class="py-2 px-1 text-right text-warning">R. Ringan</th>
                            <th class="py-2 px-1 text-right text-orange-600">R. Sedang</th>
                            <th class="py-2 px-1 text-right text-red-600">R. Berat</th>
                            <th class="py-2 px-1">Keterangan</th>
                            <th class="py-2 px-1">Kebutuhan</th>
                            @if (in_array(Auth::user()->role, ['ka_prodi', 'waka_sarpras', 'ka_tu']) && (Auth::user()->role !== 'ka_prodi' || $ruangan->prodi_id === Auth::user()->prodi_id))
                                <th class="py-2 px-1"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangGroup as $b)
                            @php $kb = $kebutuhans->get($b->nama_barang); @endphp
                            <tr class="border-b hover:bg-muted">
                                <td class="py-2 px-1 font-medium">{{ $b->nama_barang }}</td>
                                <td class="py-2 px-1 text-right">{{ $b->total }}</td>
                                <td class="py-2 px-1 text-right">{{ $b->kondisi_baik }}</td>
                                <td class="py-2 px-1 text-right">{{ $b->rusak_ringan }}</td>
                                <td class="py-2 px-1 text-right">{{ $b->rusak_sedang }}</td>
                                <td class="py-2 px-1 text-right">{{ $b->rusak_berat }}</td>
                                <td class="py-2 px-1 text-xs">{{ $kb?->keterangan ?? '-' }}</td>
                                <td class="py-2 px-1 text-xs">{{ $kb?->kebutuhan ?? '-' }}</td>
                                @if (in_array(Auth::user()->role, ['ka_prodi', 'waka_sarpras', 'ka_tu']) && (Auth::user()->role !== 'ka_prodi' || $ruangan->prodi_id === Auth::user()->prodi_id))
                                    <td class="py-2 px-1">
                                        <button onclick="toggleForm('{{ $loop->index }}')" class="text-primary hover:underline text-xs">Edit</button>
                                        <form method="POST" action="{{ route('kartu.update-kebutuhan', $ruangan) }}" id="form-{{ $loop->index }}" class="hidden mt-1">
                                            @csrf
                                            <input type="hidden" name="nama_barang" value="{{ $b->nama_barang }}">
                                            <textarea name="keterangan" placeholder="Keterangan" class="border rounded px-1 py-1 text-xs w-full mb-1" rows="2">{{ $kb?->keterangan }}</textarea>
                                            <textarea name="kebutuhan" placeholder="Kebutuhan" class="border rounded px-1 py-1 text-xs w-full mb-1" rows="2">{{ $kb?->kebutuhan }}</textarea>
                                            <button type="submit" class="px-2 py-1 bg-primary text-primary-foreground rounded text-xs">Simpan</button>
                                            <button type="button" onclick="toggleForm('{{ $loop->index }}')" class="px-2 py-1 bg-secondary text-foreground rounded text-xs hover:bg-accent">Batal</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($barangGroup->isEmpty())
                    <p class="text-muted-foreground text-sm py-4">Tidak ada barang di ruangan ini.</p>
                @endif

                <a href="{{ route('kartu.index') }}" class="mt-4 inline-block px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent text-sm">Kembali</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleForm(i) {
            const f = document.getElementById('form-' + i);
            f.classList.toggle('hidden');
        }
    </script>
    @endpush
</x-app-layout>
