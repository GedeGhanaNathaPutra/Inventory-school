# Alur Proses Bisnis (Workflow)

## 1. Alur Pengadaan / Penambahan Barang

Sesuai kebutuhan: permintaan diajukan → Waka Sarpras/Ka. Prodi → diteruskan ke RAPBS (data item sesuai kategori) → dibelanjakan → diserahkan ke Waka Sarpras → diberikan ke bagian pengguna → didata.

```mermaid
stateDiagram-v2
    [*] --> Diajukan: Ka. Prodi ajukan permintaan barang
    Diajukan --> DiteruskanRAPBS: Diteruskan ke RAPBS (data item sesuai kategori BOS/Komite)
    DiteruskanRAPBS --> Disetujui: Kepsek & Waka Sarpras setujui anggaran
    DiteruskanRAPBS --> Ditolak: Anggaran tidak disetujui
    Disetujui --> Dibelanjakan: Barang dibeli sesuai anggaran
    Dibelanjakan --> DiserahkanWaka: Barang diserahkan ke Waka Sarpras
    DiserahkanWaka --> DiserahkanPengguna: Waka Sarpras serahkan ke bagian pemakai
    DiserahkanPengguna --> Didata: Ka. TU catat barang resmi ke Data Barang
    Didata --> [*]
    Ditolak --> [*]
```

**Detail tiap tahap**:
1. **Diajukan** — Ka. Prodi (atau Waka Sarpras) mengajukan kebutuhan barang: nama barang, jumlah, kategori (BOS/Komite), alasan kebutuhan
2. **Diteruskan RAPBS** — Ka. Prodi/Waka Sarpras input data ke rencana anggaran (RAPBS), dikelompokkan sesuai kategori
3. **Disetujui** — Kepsek & Waka Sarpras menyetujui anggaran
4. **Dibelanjakan** — barang dibeli (bisa lampirkan bukti nota belanja)
5. **Diserahkan ke Waka Sarpras** — barang fisik diterima & diverifikasi Waka Sarpras
6. **Diserahkan ke Pengguna** — proses ini terhubung dengan fitur **Serah Terima Barang** (lihat F2 di `05_FEATURES_SPEC.md`)
7. **Didata** — Ka. TU mencatat barang secara resmi ke tabel `barang` dengan kode barang baru

---

## 2. Alur Serah Terima Barang

```mermaid
sequenceDiagram
    participant WS as Waka Sarpras
    participant SYS as Sistem
    participant KP as Ka. Prodi / Unit Pengguna
    WS->>SYS: Buat draft serah terima (pilih barang + jumlah)
    SYS->>SYS: Generate nomor berita acara otomatis
    WS->>SYS: Submit serah terima (status: diproses)
    SYS->>KP: Notifikasi barang siap diterima
    KP->>SYS: Konfirmasi terima (acknowledge)
    SYS->>SYS: Update lokasi barang & status jadi "selesai"
```

---

## 3. Alur Pelaporan Kondisi Barang Rusak

```mermaid
flowchart TD
    A[User pilih barang] --> B[Ubah kondisi: Rusak Ringan / Sedang / Berat]
    B --> C{Upload foto 3 arah lengkap?}
    C -- Belum lengkap --> D[Sistem tolak simpan]
    D --> B
    C -- Lengkap 3 foto --> E[Simpan sebagai riwayat kondisi baru]
    E --> F[Barang berstatus rusak & muncul di laporan kondisi]
```

---

## 4. Alur Rekap 3 Pihak

- **Ka. TU** — sumber data master (seluruh barang tercatat di sistem)
- **Ka. Prodi** — konfirmasi barang yang benar-benar ada & dipakai di prodinya masing-masing
- **Waka Sarpras** — status distribusi/serah terima barang

Sistem menampilkan **dashboard perbandingan** ketiga sumber data ini, dan menandai barang yang datanya tidak sinkron (misal: tercatat di data Ka. TU tapi belum dikonfirmasi oleh Ka. Prodi terkait) supaya bisa segera ditindaklanjuti oleh Waka Sarpras/Kepsek.

## 5. Alur Deteksi Kekurangan Barang → Permintaan Otomatis

Terhubung ke fitur Data Barang per Ruangan (F8). Setiap kali Ka. Prodi mengisi/update `jumlah_dibutuhkan` untuk suatu barang di ruangannya, sistem otomatis membandingkan dengan stok yang tersedia di ruangan itu.

```mermaid
flowchart TD
    A[Ka. Prodi isi/update jumlah kebutuhan di ruangan] --> B[Sistem hitung jumlah tersedia vs jumlah dibutuhkan]
    B --> C{Tersedia < Dibutuhkan?}
    C -- Tidak, cukup --> D[Status: Cukup - tidak ada aksi]
    C -- Ya, kurang --> E[Status: Kurang - tampil badge & tombol Ajukan Permintaan]
    E --> F[Ka. Prodi klik Ajukan Permintaan]
    F --> G[Sistem buat draft pengajuan otomatis: nama barang, kategori, jumlah = selisih kekurangan]
    G --> H[Lanjut ke Alur Pengadaan Barang - lihat bagian 1]
```

**Catatan**:
- Selama pengajuan masih berjalan (belum berstatus `selesai`/`ditolak`), baris kebutuhan tsb berstatus `sudah_diajukan` agar tidak dobel pengajuan untuk kebutuhan yang sama
- Setelah pengajuan `selesai` dan barang baru tercatat di ruangan tsb, sistem otomatis menghitung ulang status (`cukup`/`kurang`) di periode berikutnya

## 6. Laporan per Tahun Ajaran

Setiap tahun ajaran (`tahun_ajaran`) punya set laporannya sendiri — data barang, kebutuhan ruangan, dan pengajuan yang dicatat dalam periode tahun ajaran tersebut otomatis ditandai `tahun_ajaran_id` yang sedang aktif. Di halaman Laporan (F6), tersedia filter dropdown "Tahun Ajaran" sehingga Kepsek/Waka Sarpras/Ka. TU bisa membandingkan laporan antar tahun (misal: laporan 2025/2026 vs 2026/2027) tanpa data tercampur.
