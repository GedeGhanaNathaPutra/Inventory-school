<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Manajemen User</h2>
    </x-slot>

    <div x-data="{ showCreate: false }" class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-success/10 border border-success/30 text-success rounded">{{ session('success') }}</div>
            @endif

            <button @@click="showCreate = true" class="inline-block mb-4 px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">+ Tambah User</button>

            <div class="glass-card p-6">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left"><th class="py-2 px-1">Nama</th><th class="py-2 px-1">Email</th><th class="py-2 px-1">Role</th><th class="py-2 px-1">Prodi</th><th class="py-2 px-1">Aktif</th><th class="py-2 px-1"></th></tr></thead>
                        <tbody>
                            @forelse ($users as $u)
                                <tr class="border-b hover:bg-muted">
                                    <td class="py-2 px-1">{{ $u->name }}</td>
                                    <td class="py-2 px-1">{{ $u->email }}</td>
                                    <td class="py-2 px-1">{{ str_replace('_', ' ', $u->role) }}</td>
                                    <td class="py-2 px-1">{{ $u->prodi?->nama_prodi ?? '-' }}</td>
                                    <td class="py-2 px-1">{{ $u->is_active ? 'Ya' : 'Tidak' }}</td>
                                    <td class="py-2 px-1 flex gap-1">
                                        <a href="{{ route('user.edit', $u) }}" class="text-warning hover:underline text-xs">Edit</a>
                                        <form method="POST" action="{{ route('user.toggle-active', $u) }}">
                                            @csrf
                                            <button type="submit" class="text-{{ $u->is_active ? 'red' : 'green' }}-600 hover:underline text-xs">
                                                {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-center text-muted-foreground">Tidak ada user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreate" class="fixed inset-0 z-50 flex items-start justify-center px-4 py-10 sm:px-0" x-cloak>
            <div class="fixed inset-0 bg-black/50" @@click="showCreate = false"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Tambah User</h3>
                <form method="POST" action="{{ route('user.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Password</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Role</label>
                        <select name="role" id="role-select" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="">-- Pilih --</option>
                            <option value="kepsek" @selected(old('role') === 'kepsek')>Kepala Sekolah</option>
                            <option value="waka_sarpras" @selected(old('role') === 'waka_sarpras')>Waka Sarpras</option>
                            <option value="ka_tu" @selected(old('role') === 'ka_tu')>Kepala TU</option>
                            <option value="ka_prodi" @selected(old('role') === 'ka_prodi')>Kepala Prodi</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4" id="prodi-field" style="display: {{ old('role') === 'ka_prodi' ? 'block' : 'none' }}">
                        <label class="block text-sm font-medium">Prodi <span class="text-red-500">*</span></label>
                        <select name="prodi_id" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach ($prodis as $p)
                                <option value="{{ $p->id }}" @selected(old('prodi_id') == $p->id)>{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                        @error('prodi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded hover:bg-primary/90">Simpan</button>
                        <button type="button" @@click="showCreate = false" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('change', function(e) {
            if (e.target.id === 'role-select') {
                document.getElementById('prodi-field').style.display = e.target.value === 'ka_prodi' ? 'block' : 'none';
            }
        });
    </script>
    @endpush
</x-app-layout>
