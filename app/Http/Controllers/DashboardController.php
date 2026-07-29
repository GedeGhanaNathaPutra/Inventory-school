<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengajuan;
use App\Models\SerahTerima;
use App\Models\KondisiHistory;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        $data = match ($role) {
            'kepsek' => $this->kepsekData(),
            'waka_sarpras' => $this->wakaData(),
            'ka_tu' => $this->kaTuData(),
            'ka_prodi' => $this->kaProdiData($user->prodi_id),
            default => [],
        };

        return view('dashboard', $data);
    }

    private function kepsekData(): array
    {
        return [
            'totalBarang' => Barang::where('status', 'aktif')->count(),
            'totalNilai' => Barang::where('status', 'aktif')->sum('harga'),
            'barangBaik' => Barang::where('status', 'aktif')->where('kondisi', 'baik')->count(),
            'barangRusak' => Barang::where('status', 'aktif')->where('kondisi', '!=', 'baik')->count(),
            'totalDistribusi' => SerahTerima::count(),
            'distribusiSelesai' => SerahTerima::where('status', 'selesai')->count(),
            'pengajuanMenunggu' => Pengajuan::whereIn('status', ['diajukan', 'diteruskan_rapbs'])->count(),
            'barangPerKategori' => Barang::where('status', 'aktif')
                ->selectRaw("kategori, count(*) as total")
                ->groupBy('kategori')->pluck('total', 'kategori'),
            'recentPengajuan' => Pengajuan::with('diajukanOleh')->latest()->take(5)->get(),
            'rekapTigaPihak' => [
                'master' => Barang::where('status', 'aktif')->count(),
                'distribusi' => SerahTerima::where('status', 'selesai')
                    ->join('serah_terima_item', 'serah_terima.id', '=', 'serah_terima_item.serah_terima_id')
                    ->distinct('serah_terima_item.barang_id')->count('serah_terima_item.barang_id'),
                'kondisi' => KondisiHistory::count(),
            ],
        ];
    }

    private function wakaData(): array
    {
        return [
            'pengajuanStats' => Pengajuan::selectRaw("status, count(*) as total")
                ->groupBy('status')->pluck('total', 'status'),
            'totalDistribusi' => SerahTerima::count(),
            'distribusiSelesai' => SerahTerima::where('status', 'selesai')->count(),
            'distribusiDraft' => SerahTerima::where('status', 'draft')->count(),
            'recentSerahTerima' => SerahTerima::with('dariUser', 'keUser')
                ->latest()->take(5)->get(),
            'pengajuanPending' => Pengajuan::whereIn('status', ['diajukan', 'diteruskan_rapbs'])
                ->with('diajukanOleh')->latest()->take(5)->get(),
        ];
    }

    private function kaTuData(): array
    {
        return [
            'totalBarang' => Barang::where('status', 'aktif')->count(),
            'barangByKategori' => Barang::where('status', 'aktif')
                ->selectRaw("kategori, jenis_barang, count(*) as total")
                ->groupBy('kategori', 'jenis_barang')->get(),
            'kondisiBreakdown' => Barang::where('status', 'aktif')
                ->selectRaw("kondisi, count(*) as total")
                ->groupBy('kondisi')->pluck('total', 'kondisi'),
            'totalRuangan' => \App\Models\Ruangan::count(),
            'totalProdi' => \App\Models\Prodi::count(),
            'totalUser' => \App\Models\User::count(),
            'recentBarang' => Barang::with('ruangan')->latest()->take(5)->get(),
            'stokMenipis' => Barang::where('status', 'aktif')->where('kuantitas', '<=', 2)
                ->latest()->take(5)->get(),
        ];
    }

    private function kaProdiData(?int $prodiId): array
    {
        $ruanganIds = \App\Models\Ruangan::where('prodi_id', $prodiId)->pluck('id');

        return [
            'barangDiProdi' => Barang::whereIn('ruangan_id', $ruanganIds)->where('status', 'aktif')->count(),
            'barangBaik' => Barang::whereIn('ruangan_id', $ruanganIds)->where('status', 'aktif')->where('kondisi', 'baik')->count(),
            'barangRusak' => Barang::whereIn('ruangan_id', $ruanganIds)->where('status', 'aktif')->where('kondisi', '!=', 'baik')->count(),
            'totalLaporan' => KondisiHistory::whereHas('barang', fn($q) => $q->whereIn('ruangan_id', $ruanganIds))->count(),
            'recentKondisi' => KondisiHistory::whereHas('barang', fn($q) => $q->whereIn('ruangan_id', $ruanganIds))
                ->with('barang', 'dilaporkanOleh')->latest()->take(5)->get(),
        ];
    }
}
