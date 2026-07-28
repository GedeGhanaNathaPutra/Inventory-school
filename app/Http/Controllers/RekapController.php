<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\SerahTerima;
use App\Models\KondisiHistory;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Master (Ka. TU) — all barang grouped by kategori
        $dataMaster = Barang::selectRaw("kategori, jenis_barang, count(*) as total, sum(kuantitas) as qty")
            ->where('status', 'aktif')
            ->groupBy('kategori', 'jenis_barang')
            ->get();

        $totalBarang = Barang::where('status', 'aktif')->count();

        // 2. Data Distribusi (Waka Sarpras) — serah terima stats
        $distribusiPerProdi = SerahTerima::selectRaw("ke_user_id, status, count(*) as total")
            ->groupBy('ke_user_id', 'status')
            ->with('keUser.prodi')
            ->get()
            ->groupBy(fn ($item) => $item->keUser?->prodi?->nama_prodi ?? 'Umum');

        $totalDistribusi = SerahTerima::count();
        $distribusiSelesai = SerahTerima::where('status', 'selesai')->count();

        // 3. Data Pemakaian (Ka. Prodi) — items with condition reports per prodi
        $pemakaianPerProdi = KondisiHistory::selectRaw("dilaporkan_oleh, count(*) as total")
            ->groupBy('dilaporkan_oleh')
            ->with('dilaporkanOleh.prodi')
            ->get()
            ->groupBy(fn ($item) => $item->dilaporkanOleh?->prodi?->nama_prodi ?? 'Tanpa Prodi');

        $totalLaporanKondisi = KondisiHistory::count();

        // Items not yet distributed (in master but no completed handover)
        $barangTerdistribusi = SerahTerima::where('status', 'selesai')
            ->join('serah_terima_item', 'serah_terima.id', '=', 'serah_terima_item.serah_terima_id')
            ->distinct('serah_terima_item.barang_id')
            ->count('serah_terima_item.barang_id');

        $belumTerdistribusi = $totalBarang - $barangTerdistribusi;

        return view('laporan.rekap-3-pihak', compact(
            'dataMaster', 'totalBarang',
            'distribusiPerProdi', 'totalDistribusi', 'distribusiSelesai',
            'pemakaianPerProdi', 'totalLaporanKondisi',
            'barangTerdistribusi', 'belumTerdistribusi',
        ));
    }
}
