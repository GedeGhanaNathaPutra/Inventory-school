<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'ka_tu';
    }

    public function rules(): array
    {
        return [
            'tanggal_pembukuan' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'keterangan_nomor_ukuran' => 'nullable|string',
            'merek_type' => 'nullable|string|max:255',
            'kuantitas' => 'required|integer|min:1',
            'nama_satuan' => 'required|string|max:50',
            'kategori' => 'required|in:bos,komite',
            'jenis_barang' => 'required|in:inventaris,non_inventaris',
            'kelengkapan_dokumen' => 'nullable|string|max:255',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_sedang,rusak_berat',
            'harga' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'ruangan_id' => 'nullable|exists:ruangan,id',
        ];
    }
}
