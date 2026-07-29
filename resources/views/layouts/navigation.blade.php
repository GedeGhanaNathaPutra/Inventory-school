@php
$links = [
    ['route' => 'dashboard', 'label' => 'Dashboard', 'pattern' => 'dashboard'],
    ['route' => 'barang.index', 'label' => 'Data Barang', 'pattern' => 'barang.*'],
    ['route' => 'serah-terima.index', 'label' => 'Serah Terima', 'pattern' => 'serah-terima.*'],
    ['route' => 'rekap.3-pihak', 'label' => 'Rekap 3 Pihak', 'pattern' => 'rekap.*'],
    ['route' => 'stok.index', 'label' => 'Stok Barang', 'pattern' => 'stok.*'],
    ['route' => 'pengajuan.index', 'label' => 'Pengajuan', 'pattern' => 'pengajuan.*', 'roles' => ['ka_prodi', 'waka_sarpras', 'kepsek', 'ka_tu']],
    ['route' => 'kartu.index', 'label' => 'Kartu Inventaris', 'pattern' => 'kartu.*'],
    ['route' => 'laporan.index', 'label' => 'Laporan', 'pattern' => 'laporan.*', 'exclude' => ['ka_prodi']],
    ['route' => 'user.index', 'label' => 'User', 'pattern' => 'user.*', 'roles' => ['kepsek', 'ka_tu']],
];
@endphp

<nav x-data="{ open: false }" class="fixed inset-y-0 left-0 z-50 w-64 glass-nav flex flex-col">
    {{-- Logo --}}
    <div class="shrink-0 flex items-center h-16 px-4 border-b border-border">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-9 w-auto fill-current text-foreground" />
        </a>
    </div>

    {{-- Navigation Links --}}
    <div class="flex-1 overflow-y-auto py-4 space-y-1 px-2">
        @foreach ($links as $link)
            @if (isset($link['roles']) && !in_array(Auth::user()->role, $link['roles']))
                @continue
            @endif
            @if (isset($link['exclude']) && in_array(Auth::user()->role, $link['exclude']))
                @continue
            @endif
            <x-nav-link :href="route($link['route'])" :active="request()->routeIs($link['pattern'])">
                {{ __($link['label']) }}
            </x-nav-link>
        @endforeach
    </div>

    {{-- User & Logout --}}
    <div class="shrink-0 border-t border-border p-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-foreground truncate">{{ Auth::user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-muted-foreground hover:text-foreground transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
        <a href="{{ route('profile.edit') }}" class="text-xs text-muted-foreground hover:text-foreground mt-1 block">{{ Auth::user()->email }}</a>
    </div>

    {{-- Mobile overlay --}}
    <div x-show="open" x-cloak @@click="open = false" class="fixed inset-0 bg-black/50 sm:hidden" style="display: none;"></div>
</nav>
