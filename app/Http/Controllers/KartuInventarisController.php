<?php

namespace App\Http\Controllers;

use App\Models\KebutuhanRuangan;
use App\Models\Prodi;
use App\Models\Ruangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuInventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::with('prodi');

        if ($request->user()->role === 'ka_prodi') {
            $query->where('prodi_id', $request->user()->prodi_id);
        }
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        $ruangans = $query->orderBy('nama_ruangan')->get();
        $prodis = Prodi::orderBy('nama_prodi')->get();

        return view('barang.kartu.index', compact('ruangans', 'prodis'));
    }

    public function show(Request $request, Ruangan $ruangan)
    {
        if ($request->user()->role === 'ka_prodi' && $ruangan->prodi_id !== $request->user()->prodi_id) {
            abort(403);
        }

        $barangGroup = DB::table('barang')
            ->selectRaw("
                nama_barang,
                SUM(kuantitas) as total,
                SUM(CASE WHEN kondisi = 'baik' THEN kuantitas ELSE 0 END) as kondisi_baik,
                SUM(CASE WHEN kondisi = 'rusak_ringan' THEN kuantitas ELSE 0 END) as rusak_ringan,
                SUM(CASE WHEN kondisi = 'rusak_sedang' THEN kuantitas ELSE 0 END) as rusak_sedang,
                SUM(CASE WHEN kondisi = 'rusak_berat' THEN kuantitas ELSE 0 END) as rusak_berat
            ")
            ->where('ruangan_id', $ruangan->id)
            ->where('status', 'aktif')
            ->groupBy('nama_barang')
            ->orderBy('nama_barang')
            ->get();

        $kebutuhans = $ruangan->kebutuhanRuangans->keyBy('nama_barang');

        return view('barang.kartu.show', compact('ruangan', 'barangGroup', 'kebutuhans'));
    }

    public function updateKebutuhan(Request $request, Ruangan $ruangan)
    {
        if ($request->user()->role === 'ka_prodi' && $ruangan->prodi_id !== $request->user()->prodi_id) {
            abort(403);
        }

        $data = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'kebutuhan' => 'nullable|string',
        ]);

        KebutuhanRuangan::updateOrCreate(
            ['ruangan_id' => $ruangan->id, 'nama_barang' => $data['nama_barang']],
            [
                'keterangan' => $data['keterangan'],
                'kebutuhan' => $data['kebutuhan'],
                'dicatat_oleh' => $request->user()->id,
                'tanggal' => now()->format('Y-m-d'),
            ]
        );

        return back()->with('success', 'Keterangan & kebutuhan berhasil disimpan.');
    }

    public function pdf(Ruangan $ruangan)
    {
        $barangGroup = DB::table('barang')
            ->selectRaw("
                nama_barang,
                SUM(kuantitas) as total,
                SUM(CASE WHEN kondisi = 'baik' THEN kuantitas ELSE 0 END) as kondisi_baik,
                SUM(CASE WHEN kondisi = 'rusak_ringan' THEN kuantitas ELSE 0 END) as rusak_ringan,
                SUM(CASE WHEN kondisi = 'rusak_sedang' THEN kuantitas ELSE 0 END) as rusak_sedang,
                SUM(CASE WHEN kondisi = 'rusak_berat' THEN kuantitas ELSE 0 END) as rusak_berat
            ")
            ->where('ruangan_id', $ruangan->id)
            ->where('status', 'aktif')
            ->groupBy('nama_barang')
            ->orderBy('nama_barang')
            ->get();

        $kebutuhans = $ruangan->kebutuhanRuangans->keyBy('nama_barang');

        $pdf = Pdf::loadView('barang.kartu.pdf', compact('ruangan', 'barangGroup', 'kebutuhans'));
        return $pdf->download("kartu-inventaris-{$ruangan->id}.pdf");
    }
}
