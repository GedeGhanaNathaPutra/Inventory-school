<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengajuanExport implements FromQuery, WithHeadings, WithMapping
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query(): Builder
    {
        $q = Pengajuan::with('diajukanOleh', 'items');

        if ($this->filters['status'] ?? null) {
            $q->where('status', $this->filters['status']);
        }
        if ($this->filters['kategori'] ?? null) {
            $q->where('kategori', $this->filters['kategori']);
        }
        if ($this->filters['date_from'] ?? null) {
            $q->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if ($this->filters['date_to'] ?? null) {
            $q->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $q;
    }

    public function headings(): array
    {
        return ['Kode', 'Kategori', 'Diajukan Oleh', 'Status', 'Jumlah Item', 'Tanggal'];
    }

    public function map($row): array
    {
        return [
            $row->kode_pengajuan,
            strtoupper($row->kategori),
            $row->diajukanOleh?->name ?? '',
            str_replace('_', ' ', $row->status),
            $row->items->count(),
            $row->created_at?->format('Y-m-d') ?? '',
        ];
    }
}
