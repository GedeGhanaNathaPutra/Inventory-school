<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKondisiRequest;
use App\Models\Barang;
use App\Models\KondisiHistory;
use Illuminate\Support\Facades\Storage;

class KondisiController extends Controller
{
    public function create(Barang $barang)
    {
        if ($barang->status !== 'aktif') abort(404);

        // ponytail: ka_prodi only for their own prodi items
        if (request()->user()->role === 'ka_prodi') {
            $prodiId = request()->user()->prodi_id;
            if (! $barang->ruangan || $barang->ruangan->prodi_id !== $prodiId) {
                abort(403);
            }
        }

        return view('barang.kondisi.create', compact('barang'));
    }

    public function store(StoreKondisiRequest $request, Barang $barang)
    {
        if ($barang->status !== 'aktif') abort(404);

        $data = $request->safe()->except(['foto_1', 'foto_2', 'foto_3']);
        $data['barang_id'] = $barang->id;
        $data['dilaporkan_oleh'] = $request->user()->id;

        $date = now()->format('Y-m-d-His');
        $path = "kondisi-barang/{$barang->id}";

        foreach (['foto_1', 'foto_2', 'foto_3'] as $f) {
            if ($request->hasFile($f)) {
                $data[$f] = $request->file($f)->storeAs($path, "{$date}_{$f}.jpg", 'public');
            }
        }

        KondisiHistory::create($data);

        $barang->update(['kondisi' => $data['kondisi']]);

        return redirect()->route('kondisi.history', $barang)
            ->with('success', 'Kondisi barang berhasil diperbarui.');
    }

    public function history(Barang $barang)
    {
        $histories = $barang->kondisiHistories()
            ->with('dilaporkanOleh')
            ->latest()
            ->get();

        return view('barang.kondisi.history', compact('barang', 'histories'));
    }
}
