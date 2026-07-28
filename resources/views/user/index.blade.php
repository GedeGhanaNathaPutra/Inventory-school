<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen User</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <a href="{{ route('user.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ Tambah User</a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead><tr class="border-b text-left"><th class="py-2 px-1">Nama</th><th class="py-2 px-1">Email</th><th class="py-2 px-1">Role</th><th class="py-2 px-1">Prodi</th><th class="py-2 px-1">Aktif</th><th class="py-2 px-1"></th></tr></thead>
                        <tbody>
                            @forelse ($users as $u)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2 px-1">{{ $u->name }}</td>
                                    <td class="py-2 px-1">{{ $u->email }}</td>
                                    <td class="py-2 px-1">{{ str_replace('_', ' ', $u->role) }}</td>
                                    <td class="py-2 px-1">{{ $u->prodi?->nama_prodi ?? '-' }}</td>
                                    <td class="py-2 px-1">{{ $u->is_active ? 'Ya' : 'Tidak' }}</td>
                                    <td class="py-2 px-1 flex gap-1">
                                        <a href="{{ route('user.edit', $u) }}" class="text-yellow-600 hover:underline text-xs">Edit</a>
                                        <form method="POST" action="{{ route('user.toggle-active', $u) }}">
                                            @csrf
                                            <button type="submit" class="text-{{ $u->is_active ? 'red' : 'green' }}-600 hover:underline text-xs">
                                                {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-center text-gray-500">Tidak ada user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
