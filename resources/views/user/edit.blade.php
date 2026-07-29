<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">Edit User</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card p-6">
                <form method="POST" action="{{ route('user.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Password <span class="text-gray-400">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Role</label>
                        <select name="role" class="w-full border rounded px-3 py-2 mt-1 text-sm" required>
                            <option value="kepsek" @selected(old('role', $user->role) === 'kepsek')>Kepala Sekolah</option>
                            <option value="waka_sarpras" @selected(old('role', $user->role) === 'waka_sarpras')>Waka Sarpras</option>
                            <option value="ka_tu" @selected(old('role', $user->role) === 'ka_tu')>Kepala TU</option>
                            <option value="ka_prodi" @selected(old('role', $user->role) === 'ka_prodi')>Kepala Prodi</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4" id="prodi-field" style="display: {{ old('role', $user->role) === 'ka_prodi' ? 'block' : 'none' }}">
                        <label class="block text-sm font-medium">Prodi <span class="text-red-500">*</span></label>
                        <select name="prodi_id" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach ($prodis as $p)
                                <option value="{{ $p->id }}" @selected(old('prodi_id', $user->prodi_id) == $p->id)>{{ $p->nama_prodi }}</option>
                            @endforeach
                        </select>
                        @error('prodi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2 mt-1 text-sm">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
                        <a href="{{ route('user.index') }}" class="px-4 py-2 bg-secondary text-foreground rounded hover:bg-accent">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelector('select[name="role"]').addEventListener('change', function() {
            document.getElementById('prodi-field').style.display = this.value === 'ka_prodi' ? 'block' : 'none';
        });
    </script>
    @endpush
</x-app-layout>
