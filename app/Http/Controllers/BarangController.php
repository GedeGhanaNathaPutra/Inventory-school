<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('ruangan');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('jenis_barang')) {
            $query->where('jenis_barang', $request->jenis_barang);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pembukuan', $request->tahun);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_barang', 'like', "%{$s}%")
                  ->orWhere('kode_barang', 'like', "%{$s}%")
                  ->orWhere('merek_type', 'like', "%{$s}%");
            });
        }

        // ponytail: ka_prodi only sees prodi-related items via ruangan->prodi_id
        if ($request->user()->role === 'ka_prodi') {
            $prodiId = $request->user()->prodi_id;
            $query->whereHas('ruangan', fn ($q) => $q->where('prodi_id', $prodiId));
        }

        $barang = $query->latest()->paginate(20);
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('barang.index', compact('barang', 'ruangans'));
    }

    public function create()
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        return view('barang.create', compact('ruangans'));
    }

    public function store(StoreBarangRequest $request)
    {
        $data = $request->validated();
        $data['kode_barang'] = $this->generateKodeBarang($data['kategori']);
        $data['dicatat_oleh'] = $request->user()->id;

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load('ruangan', 'dicatatOleh');
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        return view('barang.edit', compact('barang', 'ruangans'));
    }

    public function update(UpdateBarangRequest $request, Barang $barang)
    {
        $barang->update($request->validated());
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->update(['status' => 'dihapuskan']);
        return redirect()->route('barang.index')->with('success', 'Barang dihapuskan (write-off).');
    }

    private function generateKodeBarang(string $kategori): string
    {
        $prefix = strtoupper($kategori);
        $tahun = now()->format('Y');

        $last = Barang::where('kode_barang', 'like', "{$prefix}-{$tahun}-%")
            ->orderBy('kode_barang', 'desc')
            ->first();

        $next = $last ? (int) substr($last->kode_barang, -4) + 1 : 1;

        return "{$prefix}-{$tahun}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
