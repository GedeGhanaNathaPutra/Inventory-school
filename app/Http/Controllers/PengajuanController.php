<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    private const STATUSES = [
        'diajukan', 'diteruskan_rapbs', 'disetujui', 'ditolak',
        'dibelanjakan', 'diserahkan_waka', 'diserahkan_pengguna', 'selesai',
    ];

    public function index(Request $request)
    {
        $query = Pengajuan::with('diajukanOleh', 'items');

        if ($request->user()->role === 'ka_prodi') {
            $query->where('diajukan_oleh', $request->user()->id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $pengajuans = $query->latest()->paginate(20);
        return view('pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        return view('pengajuan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'required|in:bos,komite',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.estimasi_harga' => 'nullable|numeric|min:0',
            'items.*.keterangan' => 'nullable|string',
        ]);

        $data['kode_pengajuan'] = $this->generateKodePengajuan($data['kategori']);
        $data['diajukan_oleh'] = $request->user()->id;
        $data['status'] = 'diajukan';

        $pengajuan = Pengajuan::create($data);

        foreach ($data['items'] as $item) {
            $pengajuan->items()->create($item);
        }

        $this->logStatus($pengajuan, 'diajukan', $request->user()->id, 'Pengajuan dibuat');

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('diajukanOleh', 'items', 'logs.updatedBy');
        return view('pengajuan.show', compact('pengajuan'));
    }

    // --- Status transitions ---

    public function forwardToRapbs(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'diajukan');
        $this->requireRole($request, ['waka_sarpras']);

        $pengajuan->update(['status' => 'diteruskan_rapbs']);
        $this->logStatus($pengajuan, 'diteruskan_rapbs', $request->user()->id, 'Diteruskan ke RAPBS');

        return back()->with('success', 'Pengajuan diteruskan ke RAPBS.');
    }

    public function approve(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'diteruskan_rapbs');
        $this->requireRole($request, ['kepsek']);

        $pengajuan->update(['status' => 'disetujui']);
        $this->logStatus($pengajuan, 'disetujui', $request->user()->id, 'Anggaran disetujui');

        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'diteruskan_rapbs');
        $this->requireRole($request, ['kepsek']);

        $data = $request->validate(['catatan' => 'nullable|string']);
        $pengajuan->update(['status' => 'ditolak', 'catatan' => $data['catatan'] ?? $pengajuan->catatan]);
        $this->logStatus($pengajuan, 'ditolak', $request->user()->id, $data['catatan'] ?? 'Anggaran ditolak');

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function markDibelanjakan(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'disetujui');
        $this->requireRole($request, ['waka_sarpras', 'ka_tu']);

        $pengajuan->update(['status' => 'dibelanjakan']);
        $this->logStatus($pengajuan, 'dibelanjakan', $request->user()->id, 'Barang dibelanjakan');

        return back()->with('success', 'Status diubah: dibelanjakan.');
    }

    public function markDiserahkanWaka(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'dibelanjakan');
        $this->requireRole($request, ['waka_sarpras']);

        $pengajuan->update(['status' => 'diserahkan_waka']);
        $this->logStatus($pengajuan, 'diserahkan_waka', $request->user()->id, 'Barang diterima Waka Sarpras');

        return back()->with('success', 'Status diubah: diserahkan ke Waka Sarpras.');
    }

    public function markDiserahkanPengguna(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'diserahkan_waka');
        $this->requireRole($request, ['waka_sarpras']);

        $pengajuan->update(['status' => 'diserahkan_pengguna']);
        $this->logStatus($pengajuan, 'diserahkan_pengguna', $request->user()->id, 'Barang diserahkan ke pengguna');

        return back()->with('success', 'Status diubah: diserahkan ke pengguna.');
    }

    public function markDidata(Request $request, Pengajuan $pengajuan)
    {
        $this->validateStatus($pengajuan, 'diserahkan_pengguna');
        $this->requireRole($request, ['ka_tu']);

        DB::transaction(function () use ($pengajuan, $request) {
            $pengajuan->update(['status' => 'selesai']);
            $this->logStatus($pengajuan, 'selesai', $request->user()->id, 'Barang dicatat ke data master');

            foreach ($pengajuan->items as $item) {
                $barang = Barang::create([
                    'kode_barang' => $this->generateKodeBarang($pengajuan->kategori),
                    'tanggal_pembukuan' => now()->format('Y-m-d'),
                    'nama_barang' => $item->nama_barang,
                    'kuantitas' => $item->jumlah,
                    'nama_satuan' => $item->satuan,
                    'kategori' => $pengajuan->kategori,
                    'jenis_barang' => 'inventaris',
                    'kondisi' => 'baik',
                    'harga' => $item->estimasi_harga,
                    'keterangan' => $item->keterangan,
                    'dicatat_oleh' => $request->user()->id,
                    'status' => 'aktif',
                ]);

                $item->update(['barang_id' => $barang->id]);
            }
        });

        return back()->with('success', 'Barang berhasil dicatat ke data master. Pengajuan selesai.');
    }

    // --- helpers ---

    private function validateStatus(Pengajuan $pengajuan, string $expected): void
    {
        if ($pengajuan->status !== $expected) {
            abort(422, "Status harus '{$expected}'.");
        }
    }

    private function requireRole(Request $request, array $roles): void
    {
        if (! in_array($request->user()->role, $roles)) {
            abort(403);
        }
    }

    private function logStatus(Pengajuan $pengajuan, string $status, int $userId, ?string $keterangan = null): void
    {
        PengajuanLog::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => $status,
            'updated_by' => $userId,
            'keterangan' => $keterangan,
        ]);
    }

    private function generateKodePengajuan(string $kategori): string
    {
        $prefix = 'PJ-' . strtoupper($kategori);
        $tahun = now()->format('Y');
        $last = Pengajuan::where('kode_pengajuan', 'like', "{$prefix}-{$tahun}-%")
            ->orderBy('kode_pengajuan', 'desc')->first();
        $next = $last ? (int) substr($last->kode_pengajuan, -4) + 1 : 1;
        return "{$prefix}-{$tahun}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    private function generateKodeBarang(string $kategori): string
    {
        $prefix = strtoupper($kategori);
        $tahun = now()->format('Y');
        $last = Barang::where('kode_barang', 'like', "{$prefix}-{$tahun}-%")
            ->orderBy('kode_barang', 'desc')->first();
        $next = $last ? (int) substr($last->kode_barang, -4) + 1 : 1;
        return "{$prefix}-{$tahun}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
