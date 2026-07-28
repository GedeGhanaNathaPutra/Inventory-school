<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use App\Exports\PengajuanExport;
use App\Models\Barang;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\Ruangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function kategori(Request $request)
    {
        $query = Barang::where('status', 'aktif');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $barang = $query->orderBy('kategori')->orderBy('nama_barang')->get();
        $data = $barang->groupBy('kategori');

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf-kategori', compact('data'));
            return $pdf->download('laporan-kategori.pdf');
        }
        if ($request->export === 'excel') {
            return Excel::download(new BarangExport($request->only(['kategori'])), 'laporan-kategori.xlsx');
        }

        return view('laporan.kategori', compact('data'));
    }

    public function kondisi(Request $request)
    {
        $query = Barang::where('status', 'aktif');

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barang = $query->orderBy('kondisi')->orderBy('nama_barang')->get();
        $data = $barang->groupBy('kondisi');

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf-kondisi', compact('data'));
            return $pdf->download('laporan-kondisi.pdf');
        }
        if ($request->export === 'excel') {
            return Excel::download(new BarangExport($request->only(['kondisi'])), 'laporan-kondisi.xlsx');
        }

        return view('laporan.kondisi', compact('data'));
    }

    public function lokasi(Request $request)
    {
        $prodis = Prodi::with(['ruangans' => fn ($q) => $q->with(['barangs' => fn ($b) => $b->where('status', 'aktif')])])->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf-lokasi', compact('prodis'));
            return $pdf->download('laporan-lokasi.pdf');
        }
        if ($request->export === 'excel') {
            return Excel::download(new BarangExport($request->only(['ruangan_id'])), 'laporan-lokasi.xlsx');
        }

        return view('laporan.lokasi', compact('prodis'));
    }

    public function pengadaan(Request $request)
    {
        $query = Pengajuan::with('diajukanOleh', 'items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengajuans = $query->latest()->get();

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('laporan.pdf-pengadaan', compact('pengajuans'));
            return $pdf->download('laporan-pengadaan.pdf');
        }
        if ($request->export === 'excel') {
            return Excel::download(new PengajuanExport($request->only(['status', 'kategori'])), 'laporan-pengadaan.xlsx');
        }

        return view('laporan.pengadaan', compact('pengajuans'));
    }
}
