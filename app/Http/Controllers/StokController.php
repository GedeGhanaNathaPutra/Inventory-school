<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokMutasi;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::where('status', 'aktif')->with('ruangan');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('nama_barang', 'like', "%{$s}%")->orWhere('kode_barang', 'like', "%{$s}%"));
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->user()->role === 'ka_prodi') {
            $query->whereHas('ruangan', fn ($q) => $q->where('prodi_id', $request->user()->prodi_id));
        }

        $barangs = $query->orderBy('nama_barang')->paginate(20);

        // ponytail: compute current stock inline instead of a separate aggregates query
        $barangs->getCollection()->transform(function ($b) {
            $b->stok_masuk = StokMutasi::where('barang_id', $b->id)->where('jenis', 'masuk')->sum('jumlah');
            $b->stok_keluar = StokMutasi::where('barang_id', $b->id)->where('jenis', 'keluar')->sum('jumlah');
            $b->stok_akhir = $b->kuantitas + $b->stok_masuk - $b->stok_keluar;
            return $b;
        });

        return view('barang.stok.index', compact('barangs'));
    }

    public function show(Barang $barang)
    {
        if ($barang->status !== 'aktif') abort(404);

        $mutasis = StokMutasi::where('barang_id', $barang->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $stokMasuk = $mutasis->where('jenis', 'masuk')->sum('jumlah');
        $stokKeluar = $mutasis->where('jenis', 'keluar')->sum('jumlah');
        $stokAkhir = $barang->kuantitas + $stokMasuk - $stokKeluar;

        return view('barang.stok.show', compact('barang', 'mutasis', 'stokMasuk', 'stokKeluar', 'stokAkhir'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        return view('barang.stok.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        StokMutasi::create($data);

        return redirect()->route('stok.index')->with('success', 'Mutasi stok berhasil dicatat.');
    }
}
