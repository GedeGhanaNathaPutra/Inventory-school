# PRD — Aplikasi Inventaris Sekolah

## 1. Latar Belakang
Sekolah membutuhkan sistem digital untuk mencatat, melacak, dan melaporkan seluruh barang inventaris (aset) — baik yang berasal dari dana BOS maupun dana Komite — mulai dari pengadaan, serah terima, penggunaan, hingga kondisi/kerusakan barang. Saat ini pendataan dilakukan manual oleh Ka. TU, dan perlu disinkronkan dengan data dari Waka Sarpras dan Ka. Prodi agar tidak ada selisih data.

## 2. Tujuan
- Memusatkan data barang inventaris sekolah dalam satu sistem
- Mempermudah & mendokumentasikan proses serah terima barang antar bagian
- Melacak kondisi barang (baik / rusak ringan / rusak sedang / rusak berat) dengan bukti foto
- Menyediakan laporan & rekap yang bisa dibandingkan antara Ka. TU, Ka. Prodi, dan Waka Sarpras
- Mendigitalkan alur pengadaan barang, dari permintaan sampai barang diterima & dipakai

## 3. Target Pengguna & Role

| Role | Deskripsi |
|---|---|
| **Kepsek** | Penanggung jawab penuh — approval akhir & monitoring seluruh data/laporan |
| **Waka Sarpras** | Penanggung jawab penuh 2 — kelola pengadaan, serah terima, distribusi barang |
| **Ka. TU** | Admin utama / pendataan — input & maintain data master barang |
| **Ka. Prodi** | Pelaksana — ajukan kebutuhan barang, terima & pakai barang, lapor kondisi barang di prodinya |

Detail hak akses lengkap ada di `04_ROLES_PERMISSIONS.md`.

## 4. Kategori Barang
Barang diklasifikasikan dalam **2 dimensi** yang independen satu sama lain:

**Berdasarkan sumber dana:**
- **Barang BOS** — dibeli dari dana Bantuan Operasional Sekolah
- **Barang Komite** — dibeli dari dana Komite Sekolah

**Berdasarkan jenis pemakaian:**
- **Barang Inventaris** — barang yang masih bisa digunakan hingga 1 tahun ke depan (meja, kursi, komputer, printer, dst)
- **Barang Non-Inventaris** — barang yang habis pakai/digunakan kurang dari 1 tahun (kertas, buku, ATK, dst)

Contoh kombinasi: sebuah printer dari dana BOS akan tercatat sebagai `kategori: bos` + `jenis_barang: inventaris`, sedangkan kertas dari dana Komite tercatat sebagai `kategori: komite` + `jenis_barang: non_inventaris`.

## 5. Ruang Lingkup Fitur (High Level)
1. Manajemen Data Barang
2. Serah Terima Barang
3. Rekap Data (dari Ka. TU, Ka. Prodi, Waka Sarpras)
4. Kondisi Barang (dengan foto 3 arah untuk barang rusak)
5. Stok Barang
6. Laporan (per tahun ajaran)
7. Alur Pengadaan Barang (dari permintaan sampai penggunaan — manual maupun otomatis)
8. Data Barang per Ruangan (rekap per ruangan: nama barang, jumlah, kondisi, keterangan, kebutuhan) dengan deteksi kekurangan otomatis yang memicu permintaan barang
9. Manajemen Tahun Ajaran (setiap tahun ajaran punya laporannya sendiri)

Detail tiap fitur ada di `05_FEATURES_SPEC.md`.

## 6. Di Luar Ruang Lingkup (v1)
- Integrasi dengan sistem keuangan sekolah
- Aplikasi mobile native (cukup web responsive, bisa dibuka dari HP via LAN)
- Notifikasi via WhatsApp/SMS (kandidat fitur v2)
- Barcode/QR scanning barang (kandidat fitur v2)

## 7. Asumsi
- Format data barang mengikuti format umum Kartu Inventaris Barang (KIB) sekolah — **perlu dikonfirmasi ulang** dengan format asli Ka. TU sebelum development database dimulai
- Aplikasi berjalan di jaringan lokal sekolah (LAN), tanpa akses internet
- Satu server lokal (PC/mini server) menjadi host aplikasi & database
- Semua user (4 role) sudah punya akun terdaftar oleh admin (Ka. TU/Kepsek), tidak ada self-registration

## 8. Kriteria Sukses
- Semua barang BOS & Komite tercatat dengan kode unik
- Setiap serah terima memiliki bukti digital (berita acara) yang bisa dicetak
- Barang berkondisi rusak selalu punya foto 3 arah sebagai bukti
- Kepsek & Waka Sarpras bisa melihat rekap real-time tanpa harus minta laporan manual ke Ka. TU
- Alur pengadaan barang bisa dilacak statusnya dari pengajuan sampai barang dipakai
