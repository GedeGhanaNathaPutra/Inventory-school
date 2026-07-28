<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreKondisiRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_sedang,rusak_berat',
            'keterangan' => 'nullable|string',
            'foto_1' => 'nullable|image|max:2048',
            'foto_2' => 'nullable|image|max:2048',
            'foto_3' => 'nullable|image|max:2048',
            'tanggal_lapor' => 'required|date',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $v) {
                if ($v->errors()->isNotEmpty()) return;

                $data = $v->validated();

                if ($data['kondisi'] !== 'baik') {
                    foreach (['foto_1', 'foto_2', 'foto_3'] as $f) {
                        if (! $this->hasFile($f)) {
                            $v->errors()->add($f, "Foto {$f} wajib diupload jika kondisi tidak 'Baik'.");
                        }
                    }
                }
            },
        ];
    }
}
