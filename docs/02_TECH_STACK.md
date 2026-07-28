# Tech Stack & Arsitektur

## Kenapa Stack Ini?
Karena aplikasi akan dipakai **lokal di jaringan sekolah tanpa internet**, stack dipilih dengan kriteria:
- Mudah di-hosting di 1 PC/server lokal (tanpa perlu layanan cloud)
- Familiar untuk tim IT sekolah / mudah dicari developer lokal untuk maintenance ke depan
- Ringan, stabil, dan gampang di-backup manual

## Stack Terpilih

| Layer | Teknologi | Alasan |
|---|---|---|
| Backend | Laravel 11 (PHP 8.2+) | Full-stack framework, dokumentasi lengkap, cocok untuk aplikasi CRUD + role-based access control |
| Database | MySQL 8 / MariaDB | Ringan & stabil untuk instalasi lokal, kompatibel dengan XAMPP/Laragon |
| Frontend | Blade + Tailwind CSS + Alpine.js | Tidak butuh build SPA terpisah (React/Vue), cocok untuk hosting lokal sederhana tanpa perlu server Node terus-menerus |
| Auth | Laravel Breeze (session-based) + custom role middleware | Simple, tanpa perlu API token/OAuth eksternal (karena offline) |
| File Storage | Local filesystem (`storage/app/public`) | Untuk menyimpan foto kondisi barang (3 arah), tanpa perlu cloud storage |
| Export Laporan | `barryvdh/laravel-dompdf` (PDF), `maatwebsite/excel` (Excel) | Untuk cetak berita acara serah terima & laporan rekap |
| Web Server | Apache/Nginx + PHP-FPM, jalan di 1 PC lokal sekolah | Diakses semua device di sekolah lewat LAN (misal `http://192.168.1.10`) |
| Backup | Scheduled `mysqldump` + backup folder `storage/` | Backup rutin ke external drive/USB karena tidak ada redundansi cloud |

## Struktur Folder (Laravel default + tambahan khusus project)
```
app/
  Models/               # Barang, SerahTerima, Pengajuan, KondisiHistory, dst
  Http/
    Controllers/
    Middleware/         # RoleMiddleware -> cek role: kepsek, waka_sarpras, ka_tu, ka_prodi
    Requests/           # Form validation per fitur
resources/
  views/
    barang/
    serah-terima/
    pengajuan/
    laporan/
    layouts/
database/
  migrations/
  seeders/              # Seed 4 akun default (1 per role) untuk testing
routes/
  web.php
storage/
  app/public/
    kondisi-barang/     # Foto 3 arah barang rusak, dikelompokkan per barang_id
    berita-acara/       # PDF hasil generate serah terima
```

## Kebutuhan Environment
- PHP >= 8.2, Composer
- MySQL 8 / MariaDB
- Node.js (hanya dipakai saat build Tailwind CSS, tidak dipakai saat runtime)
- Web server lokal: Laragon (Windows) atau LAMP/LEMP stack (Linux) di PC/server sekolah

## Catatan Keamanan Lokal
- Karena tanpa internet, tidak wajib HTTPS eksternal — tapi password tetap wajib di-hash (`bcrypt`, default Laravel)
- Batasi akses jaringan (misal Wi-Fi sekolah dengan password terpisah untuk perangkat admin) agar tidak sembarang orang bisa akses IP server
- Backup database rutin (harian/mingguan) wajib karena tidak ada redundansi cloud

## Alternatif (kalau kebutuhan berubah)
Kalau ke depan sekolah butuh akses dari luar (misal Kepsek mau cek laporan dari rumah), stack ini tetap bisa di-deploy ke hosting/VPS biasa tanpa perlu ganti bahasa pemrograman — cukup pindahkan `.env` dan database ke server online.
