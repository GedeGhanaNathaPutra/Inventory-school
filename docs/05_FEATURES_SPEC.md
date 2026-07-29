# Spesifikasi Fitur

## F1 — Manajemen Data Barang
**Aktor**: Ka. TU (kelola penuh), semua role (lihat sesuai hak akses)

**Deskripsi**: CRUD data barang inventaris, mengikuti format pembukuan Ka. TU, dengan 2 dimensi kategori: sumber dana (BOS/Komite) dan jenis pemakaian (Inventaris/Non-Inventaris).

**Field utama** (sesuai format Ka. TU): tanggal pembukuan, nama barang, keterangan nomor/ukuran, merek/type, kuantitas, nama satuan, kategori (BOS/Komite), jenis barang (Inventaris/Non-Inventaris), kelengkapan dokumen, kondisi barang, harga, keterangan — ditambah field pendukung sistem: kode barang (auto), lokasi/ruangan, status.

**Aturan**:
- Kode barang auto-generate format `[BOS|KOM]-[TAHUN]-[NOMOR URUT]`, contoh `BOS-2026-0007`
- **Barang Inventaris** = barang yang masih bisa dipakai ≥1 tahun ke depan (meja, kursi, komputer, dst)
- **Barang Non-Inventaris** = barang habis pakai/dipakai <1 tahun (kertas, buku, ATK, dst) — biasanya tidak perlu dilacak kondisi kerusakannya karena sifatnya habis pakai, cukup dilacak stoknya (lihat F5)
- Barang tidak dihapus permanen — hanya diubah status jadi `dihapuskan` (write-off), butuh approval Kepsek (khusus barang inventaris)
- Ada filter & pencarian: kategori (BOS/Komite), jenis barang (Inventaris/Non-Inventaris), kondisi, lokasi, tahun

**Acceptance criteria**:
- [ ] Ka. TU bisa tambah barang baru dengan kode otomatis dan seluruh field sesuai format Ka. TU
- [ ] Semua role bisa lihat list barang (dengan batasan sesuai `04_ROLES_PERMISSIONS.md`)
- [ ] Barang bisa difilter per kategori BOS/Komite dan per jenis Inventaris/Non-Inventaris

---

## F2 — Serah Terima Barang
**Aktor**: Waka Sarpras (proses), Ka. TU (catat), Ka. Prodi (terima)

**Deskripsi**: Digitalisasi berita acara serah terima barang dari Waka Sarpras ke bagian pengguna (Ka. Prodi/unit lain).

**Alur**:
1. Waka Sarpras/Ka. TU buat draft serah terima → pilih barang & jumlah
2. Pilih penerima (Ka. Prodi/unit)
3. Kondisi barang dicatat saat serah terima (snapshot kondisi)
4. Cetak/generate berita acara (PDF) dengan nomor otomatis
5. Penerima acknowledge (tombol "Terima" di sistem)

**Output**: Berita acara PDF, lokasi barang otomatis ter-update ke ruangan/prodi penerima

**Acceptance criteria**:
- [ ] Berita acara punya nomor unik otomatis
- [ ] Status serah terima berubah "selesai" setelah penerima acknowledge
- [ ] Lokasi barang otomatis pindah setelah serah terima selesai

---

## F3 — Rekap Data 3 Pihak
**Aktor**: Kepsek & Waka Sarpras (lihat semua), Ka. TU (sumber data master), Ka. Prodi (kontribusi data prodinya)

**Deskripsi**: Dashboard rekap yang membandingkan data dari 3 sumber:
- **Data master** (Ka. TU) — seluruh barang tercatat di sistem
- **Data pemakaian** (Ka. Prodi) — barang yang benar-benar ada & dipakai di tiap prodi
- **Data distribusi** (Waka Sarpras) — status serah terima & distribusi barang

**Fitur tambahan**: highlight barang yang datanya tidak sinkron antar 3 sumber (misal: tercatat di data Ka. TU tapi belum ada konfirmasi terima dari Ka. Prodi) agar bisa ditindaklanjuti.

**Acceptance criteria**:
- [ ] Dashboard menampilkan 3 sumber data berdampingan
- [ ] Ada indikator visual untuk data yang tidak sinkron

---

## F4 — Kondisi Barang & Foto 3 Arah
**Aktor**: Ka. TU, Waka Sarpras, Ka. Prodi (khusus barang di area masing-masing)

**Deskripsi**: Update status kondisi barang (Baik / Rusak Ringan / Rusak Sedang / Rusak Berat).

**Aturan wajib**:
- Jika kondisi dipilih selain "Baik" → **wajib upload 3 foto** (foto arah 1, 2, 3) sebelum bisa disimpan — form tidak boleh submit jika foto belum lengkap
- Riwayat kondisi tersimpan sebagai record baru (tidak menimpa data lama), sehingga histori kerusakan barang bisa dilihat dari waktu ke waktu
- Foto disimpan terstruktur: `storage/app/public/kondisi-barang/{barang_id}/{tanggal}_foto{1,2,3}.jpg`

**Acceptance criteria**:
- [ ] Sistem menolak simpan kalau kondisi ≠ baik tapi foto belum 3-3-nya lengkap
- [ ] Riwayat kondisi barang bisa dilihat sebagai timeline

---

## F5 — Stok Barang
**Aktor**: Ka. TU (utama), Waka Sarpras (lihat & update saat distribusi)

**Deskripsi**: Tracking jumlah stok tiap barang, termasuk mutasi masuk (dari pengadaan) dan keluar (serah terima / rusak berat / write-off).

**Fitur**: Riwayat mutasi stok per barang; filter stok menipis (kandidat v2)

**Acceptance criteria**:
- [ ] Setiap serah terima & pengadaan otomatis mencatat mutasi stok
- [ ] Jumlah stok barang selalu sinkron dengan total mutasi

---

## F6 — Laporan
**Aktor**: Kepsek, Waka Sarpras, Ka. TU

**Jenis laporan**:
- Laporan per kategori (BOS vs Komite)
- Laporan per jenis barang (Inventaris vs Non-Inventaris)
- Laporan per kondisi (baik / rusak ringan / sedang / berat)
- Laporan per lokasi/ruangan/prodi
- Laporan status pengadaan (progress alur pengajuan)
- Laporan barang yang berstatus "kurang" (butuh permintaan) per ruangan
- Export ke PDF & Excel

**Aturan**:
- Semua jenis laporan di atas punya **filter Tahun Ajaran** — laporan hanya menampilkan data yang tercatat pada tahun ajaran terpilih, sehingga tiap tahun ajaran punya laporannya sendiri dan tidak tercampur dengan tahun lain
- Default filter = tahun ajaran yang sedang `aktif`, tapi bisa diganti untuk lihat laporan tahun sebelumnya

**Acceptance criteria**:
- [ ] Semua jenis laporan di atas bisa di-export ke PDF dan Excel
- [ ] Laporan bisa difilter berdasarkan rentang tanggal dan Tahun Ajaran

---

## F7 — Alur Pengadaan Barang (Procurement Workflow)
**Aktor**: Ka. Prodi (ajukan), Waka Sarpras (approve & proses), Kepsek (approval anggaran), Ka. TU (catat final)

**Deskripsi**: Digitalisasi alur pengajuan barang baru dari permintaan sampai barang diterima & digunakan. Pengajuan bisa muncul dari 2 sumber:
1. **Manual** — Ka. Prodi/Waka Sarpras membuat pengajuan baru langsung
2. **Otomatis** — dipicu dari fitur Data Barang per Ruangan (F8) saat sistem mendeteksi `jumlah_tersedia < jumlah_dibutuhkan` di suatu ruangan, lalu Ka. Prodi klik tombol "Ajukan Permintaan"

Detail state & diagram lengkap ada di `06_WORKFLOW_ALUR_BARANG.md`.

**Acceptance criteria**:
- [ ] Setiap pengajuan bisa dilacak statusnya real-time oleh semua pihak terkait
- [ ] Ada riwayat/log setiap perubahan status pengajuan
- [ ] Pengajuan yang dibuat otomatis dari F8 tetap tercatat asalnya (`kebutuhan_ruangan_id`) dan formnya terisi otomatis (nama barang, kategori, jumlah kekurangan)

---

## F8 — Data Barang per Ruangan (Kartu Inventaris Ruangan)
**Aktor**: Ka. TU, Waka Sarpras (lihat semua ruangan), Ka. Prodi (lihat & isi kebutuhan untuk ruangan di prodinya)

**Deskripsi**: Rekap barang per ruangan dalam format kartu, menampilkan untuk setiap nama barang di ruangan tersebut:
- Jumlah tersedia
- Breakdown kondisi: Baik / Rusak Ringan / Rusak Berat
- Keterangan
- Jumlah dibutuhkan (target ideal) & status kecukupan (Cukup / Kurang / Sudah Diajukan)

**Aturan**:
- Jumlah & breakdown kondisi dihitung otomatis dari data di F1 (tabel `barang`, dikelompokkan per ruangan + nama barang)
- Kolom "Keterangan" & "Jumlah Dibutuhkan" diisi manual oleh Ka. Prodi/penanggung jawab ruangan
- **Deteksi kekurangan otomatis**: begitu `jumlah_tersedia < jumlah_dibutuhkan`, sistem menampilkan badge "Kurang X unit" dan tombol **"Ajukan Permintaan"**
- Klik "Ajukan Permintaan" langsung membuat draft pengajuan (F7) yang otomatis terisi nama barang, kategori, dan jumlah kekurangan, lalu mengikuti Alur Pengadaan Barang (permintaan → Waka Sarpras/Ka. Prodi → RAPBS → dibelanjakan → diserahkan ke Waka Sarpras → diberikan ke bagian pengguna → didata)
- Baris yang sudah punya pengajuan aktif berstatus "Sudah Diajukan" agar tidak dobel pengajuan

**Acceptance criteria**:
- [ ] Setiap ruangan punya halaman rekap yang menampilkan seluruh barang di ruangan itu dengan breakdown kondisi
- [ ] Ka. Prodi bisa mengisi/update jumlah dibutuhkan untuk ruangan yang menjadi tanggung jawabnya
- [ ] Sistem otomatis menandai baris "Kurang" dan memunculkan tombol Ajukan Permintaan saat stok < kebutuhan
- [ ] Rekap ini bisa di-export ke PDF/Excel per ruangan (terhubung ke F6)

---

## F9 — Manajemen User & Role
**Aktor**: Kepsek / Ka. TU

**Deskripsi**: CRUD user, assign role, assign prodi (khusus `ka_prodi`), aktifkan/nonaktifkan akun.

**Acceptance criteria**:
- [ ] User baru wajib diberi salah satu dari 4 role saat dibuat
- [ ] User dengan role `ka_prodi` wajib terhubung ke satu `prodi`

---

## F10 — Manajemen Tahun Ajaran
**Aktor**: Ka. TU (kelola), Kepsek (lihat)

**Deskripsi**: Kelola daftar tahun ajaran (misal `2025/2026`, `2026/2027`) yang jadi dasar pengelompokan seluruh data transaksi (barang, kebutuhan ruangan, pengajuan, serah terima) sehingga tiap tahun ajaran punya laporannya sendiri-sendiri.

**Aturan**:
- Hanya boleh **1 tahun ajaran berstatus `aktif`** dalam satu waktu
- Data baru (barang masuk, kebutuhan ruangan, pengajuan, serah terima) otomatis ditandai dengan tahun ajaran yang sedang aktif
- Saat tahun ajaran baru dimulai, Ka. TU tinggal mengaktifkan tahun ajaran baru — data lama tetap tersimpan & tetap bisa dilihat lewat filter di F6

**Acceptance criteria**:
- [ ] Hanya 1 tahun ajaran yang bisa berstatus aktif pada satu waktu (validasi sistem)
- [ ] Semua transaksi baru otomatis terhubung ke tahun ajaran aktif tanpa perlu input manual
