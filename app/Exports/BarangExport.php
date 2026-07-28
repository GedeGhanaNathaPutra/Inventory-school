<?php

namespace App\Exports;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangExport implements FromQuery, WithHeadings, WithMapping
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query(): Builder
    {
        $q = Barang::with('ruangan')->where('status', 'aktif');

        if ($this->filters['kategori'] ?? null) {
            $q->where('kategori', $this->filters['kategori']);
        }
        if ($this->filters['kondisi'] ?? null) {
            $q->where('kondisi', $this->filters['kondisi']);
        }
        if ($this->filters['ruangan_id'] ?? null) {
            $q->where('ruangan_id', $this->filters['ruangan_id']);
        }
        if ($this->filters['jenis_barang'] ?? null) {
            $q->where('jenis_barang', $this->filters['jenis_barang']);
        }
        if ($this->filters['date_from'] ?? null) {
            $q->whereDate('tanggal_pembukuan', '>=', $this->filters['date_from']);
        }
        if ($this->filters['date_to'] ?? null) {
            $q->whereDate('tanggal_pembukuan', '<=', $this->filters['date_to']);
        }

        return $q;
    }

    public function headings(): array
    {
        return [
            'Kode', 'Nama Barang', 'Kategori', 'Jenis', 'Merek/Type',
            'Qty', 'Satuan', 'Kondisi', 'Harga', 'Ruangan', 'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->kode_barang,
            $row->nama_barang,
            strtoupper($row->kategori),
            str_replace('_', ' ', $row->jenis_barang),
            $row->merek_type ?? '',
            $row->kuantitas,
            $row->nama_satuan,
            str_replace('_', ' ', $row->kondisi),
            $row->harga ?: 0,
            $row->ruangan?->nama_ruangan ?? '',
            $row->status,
        ];
    }
}
