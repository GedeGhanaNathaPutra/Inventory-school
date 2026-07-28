# AGENTS.md — Inventory-school

## Status

**Greenfield project.** No code exists yet. Only design docs under `docs/`. The docs directory is **untracked** — commit it before coding.

## Folder-name trap

The parent folder is named `Laravel next js` but the frontend is **Blade + Tailwind + Alpine.js**, not Next.js. Do not scaffold any SPA framework.

## Tech stack

| Layer | Choice |
|-------|--------|
| Backend | Laravel 11 (PHP 8.2+) |
| Database | MySQL 8 / MariaDB |
| Frontend | Blade + Tailwind CSS + Alpine.js (no React/Vue/Next.js) |
| Auth | Laravel Breeze session-based + custom role middleware |
| PDF | `barryvdh/laravel-dompdf` |
| Excel | `maatwebsite/excel` |

## Roles (stored as `users.role` enum)

- `kepsek` — principal, final approval & monitoring
- `waka_sarpras` — facilities VP, procurement & handover
- `ka_tu` — admin, master data entry
- `ka_prodi` — dept head, usage & condition reporting

## Must-read specs (read before coding)

`docs/01_PRD.md` — product requirements
`docs/02_TECH_STACK.md` — architecture
`docs/03_DATABASE_SCHEMA.md` — all 13 tables, FKs, indexes
`docs/04_ROLES_PERMISSIONS.md` — full permission matrix (24 actions)
`docs/05_FEATURES_SPEC.md` — feature acceptance criteria (F1–F9)
`docs/06_WORKFLOW_ALUR_BARANG.md` — state machines & sequence flows

## Critical domain rules (easy to miss)

- **Dual classification**: Every item has independent `kategori` (bos/komite) AND `jenis_barang` (inventaris/non_inventaris).
- **3-photo requirement**: When condition is not "baik", the user **must** upload 3 photos (`foto_1/2/3`). This is a hard validation rule.
- **Ka.Prodi data scoping**: All queries for Ka.Prodi **must** filter by `prodi_id` at the query/controller level, not just UI hiding (`04_ROLES_PERMISSIONS.md:37`).
- **No soft deletes** for items — items get `status = dihapuskan` (write-off) with an approval flow.
- **`barang.kondisi`** is a snapshot; `kondisi_history` is the immutable audit log.
- **8-state procurement**: `Diajukan → DiteruskanRAPBS → Disetujui/Ditolak → Dibelanjakan → DiserahkanWaka → DiserahkanPengguna → Didata` with strict transition rules.
- **Auto-generated kode_barang**: Format `BOS-2026-0007` or `KOM-2026-0001`.
- **Handover** auto-updates barang location upon recipient acknowledgment.

## First setup steps

1. `composer create-project laravel/laravel .` (or `laravel new .`)
2. `composer require barryvdh/laravel-dompdf maatwebsite/excel laravel/breeze --dev`
3. `php artisan breeze:install blade` (select Blade + Alpine.js stack)
4. `php artisan storage:link`
5. Copy `docs/03_DATABASE_SCHEMA.md` → migrations
6. Seed 4 default accounts (1 per role) for development

## Testing

- Laravel default: PHPUnit (expect `phpunit.xml` after scaffold)
- No JS test framework needed (minimal Alpine.js)

## Conventions to follow

- Middleware-based RBAC: `Route::middleware(['auth', 'role:kepsek,waka_sarpras'])` per route group.
- Views in `resources/views/{feature}/` subdirectories.
- File uploads to `storage/app/public/kondisi-barang/{barang_id}/` and `storage/app/public/berita-acara/`.
- No self-registration — accounts are created by Kepsek/Ka.TU only.
