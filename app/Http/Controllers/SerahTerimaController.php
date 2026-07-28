<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\SerahTerima;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerahTerimaController extends Controller
{
    public function index(Request $request)
    {
        $query = SerahTerima::with('dariUser', 'keUser', 'ruanganTujuan');

        if ($request->user()->role === 'ka_prodi') {
            $query->where('ke_user_id', $request->user()->id);
        }

        $serahTerimas = $query->latest()->paginate(20);
        return view('serah-terima.index', compact('serahTerimas'));
    }

    public function create()
    {
        $barangs = Barang::where('status', 'aktif')->orderBy('nama_barang')->get();
        $users = User::where('is_active', true)->whereIn('role', ['ka_prodi', 'waka_sarpras'])->orderBy('name')->get();
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        return view('serah-terima.create', compact('barangs', 'users', 'ruangans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ke_user_id' => 'required|exists:users,id',
            'ruangan_tujuan_id' => 'required|exists:ruangan,id',
            'tanggal_serah_terima' => 'required|date',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barang,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.kondisi_saat_serah_terima' => 'required|in:baik,rusak_ringan,rusak_sedang,rusak_berat',
        ]);

        $data['nomor_berita_acara'] = $this->generateNomorBA();
        $data['dari_user_id'] = $request->user()->id;
        $data['dibuat_oleh'] = $request->user()->id;
        $data['status'] = 'draft';

        $serahTerima = SerahTerima::create($data);

        foreach ($data['items'] as $item) {
            $serahTerima->items()->create($item);
        }

        return redirect()->route('serah-terima.index')->with('success', 'Draft serah terima berhasil dibuat.');
    }

    public function show(SerahTerima $serahTerima)
    {
        $serahTerima->load('dariUser', 'keUser', 'ruanganTujuan', 'items.barang');
        return view('serah-terima.show', compact('serahTerima'));
    }

    public function acknowledge(Request $request, SerahTerima $serahTerima)
    {
        if ($serahTerima->status !== 'draft') {
            return back()->with('error', 'Serah terima sudah diproses.');
        }

        if ($request->user()->id !== $serahTerima->ke_user_id) {
            abort(403);
        }

        DB::transaction(function () use ($serahTerima) {
            $serahTerima->update(['status' => 'selesai']);

            foreach ($serahTerima->items as $item) {
                $item->barang->update(['ruangan_id' => $serahTerima->ruangan_tujuan_id]);
            }
        });

        return redirect()->route('serah-terima.show', $serahTerima)
            ->with('success', 'Serah terima telah diterima. Lokasi barang otomatis diperbarui.');
    }

    public function pdf(SerahTerima $serahTerima)
    {
        $serahTerima->load('dariUser', 'keUser', 'ruanganTujuan', 'items.barang');

        $pdf = Pdf::loadView('serah-terima.pdf', compact('serahTerima'));

        return $pdf->download("berita-acara-{$serahTerima->nomor_berita_acara}.pdf");
    }

    private function generateNomorBA(): string
    {
        $tahun = now()->format('Y');
        $last = SerahTerima::where('nomor_berita_acara', 'like', "BA-{$tahun}-%")
            ->orderBy('nomor_berita_acara', 'desc')
            ->first();

        $next = $last ? (int) substr($last->nomor_berita_acara, -4) + 1 : 1;

        return 'BA-' . $tahun . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
