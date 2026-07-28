# Role & Hak Akses

## Ringkasan Role
| Role (kode) | Posisi | Tanggung Jawab Utama |
|---|---|---|
| `kepsek` | Kepala Sekolah | Approval akhir, monitoring seluruh sistem & laporan |
| `waka_sarpras` | Waka Sarana Prasarana | Approval pengajuan, kelola serah terima & distribusi barang |
| `ka_tu` | Kepala Tata Usaha | Admin utama, input & maintain data master barang |
| `ka_prodi` | Kepala Program Studi | Ajukan kebutuhan barang, terima & pakai barang, lapor kondisi barang di prodinya |

## Matriks Hak Akses

| Fitur / Aksi | Kepsek | Waka Sarpras | Ka. TU | Ka. Prodi |
|---|:---:|:---:|:---:|:---:|
| Lihat dashboard & semua data | ✅ | ✅ | ✅ | ❌ *(hanya data prodi sendiri)* |
| Tambah/edit/hapus data barang master | ❌ | 👁️ lihat saja | ✅ | ❌ |
| Ajukan permintaan barang | ❌ | ✅ | ❌ | ✅ |
| Teruskan pengajuan ke RAPBS | ❌ | ✅ | ❌ | ❌ |
| Approval akhir anggaran RAPBS | ✅ | 👁️ | ❌ | ❌ |
| Update status "dibelanjakan" | ❌ | ✅ | ✅ *(bantu input)* | ❌ |
| Buat & proses serah terima barang | ❌ | ✅ | ✅ *(catat)* | ✅ *(terima/acknowledge)* |
| Update kondisi barang + upload foto 3 arah | ❌ | ✅ | ✅ | ✅ *(barang di prodinya saja)* |
| Lihat rekap 3 pihak (TU/Prodi/Waka) | ✅ | ✅ | ✅ | ❌ *(hanya rekap prodinya)* |
| Export laporan (PDF/Excel) | ✅ | ✅ | ✅ | ❌ |
| Kelola user & role | ✅ | ❌ | ✅ *(opsional, sesuai kebijakan sekolah)* | ❌ |
| Nonaktifkan/hapus barang (write-off) | ✅ *(approve)* | ✅ *(ajukan)* | ✅ *(eksekusi di sistem)* | ❌ |

Legenda: ✅ = akses penuh · 👁️ = lihat saja · ❌ = tidak ada akses

## Catatan Implementasi (Laravel)
- Gunakan **middleware role** di setiap route group, misal:
  ```php
  Route::middleware(['auth', 'role:kepsek,waka_sarpras'])->group(function () {
      // route khusus approval
  });
  ```
- Untuk `ka_prodi`, semua query data barang/pengajuan/kondisi **wajib difilter** berdasarkan `prodi_id` milik user yang login — jangan hanya disembunyikan di UI, tapi juga dibatasi di level controller/query.
- Kalau ke depan kebutuhan hak akses makin kompleks (misal ada sub-role atau permission granular per menu), pertimbangkan pakai package `spatie/laravel-permission` daripada enum role sederhana.
- Simpan `role` sebagai kolom di tabel `users` untuk v1 (sesuai `03_DATABASE_SCHEMA.md`) — cukup untuk 4 role tetap seperti sekarang.
