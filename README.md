# Sistem Informasi Percetakan

Sistem manajemen percetakan dengan Laravel 11, MySQL, dan Tailwind CSS.

## Tech Stack
- Laravel 11
- MySQL 8.0
- Laravel Breeze + Spatie Permission
- Tailwind CSS

## Installation

1. Install dependencies:
```bash
composer install
npm install
```

2. Database sudah dibuat: `percetakan_app`

3. Run migrations & seed:
```bash
php artisan migrate --seed
```

4. Build assets:
```bash
npm run build
```

5. Jalankan:
```bash
php artisan serve
```

## Default Users (password: password)

- **Owner**: owner@percetakan.com (Full access)
- **Kasir**: kasir@percetakan.com (Pesanan & Pembayaran)
- **Operator**: operator@percetakan.com (Produksi only)

## Fitur Utama

### Owner/Admin
- Master Data (Bahan & Finishing)
- Laporan Omzet & Keuangan
- Full Dashboard

### Kasir
- Buat Pesanan
- Proses Pembayaran (DP/Lunas)
- Upload Desain
- Cetak Invoice

### Operator Produksi
- Dashboard Produksi
- Download Desain
- Update Status (TANPA lihat harga)

## Database Seeder

System sudah ter-seed dengan:
- 3 Users (owner, kasir, operator)
- 10 Bahan (Flexi, Art Carton, HVS, dll)
- 10 Finishing (Mata Ayam, Laminasi, Lipat, dll)
- Roles & Permissions lengkap

## Kalkulasi Harga

**Banner**: (Panjang × Lebar) × Qty × Harga + Finishing
**Kertas**: Qty × Harga + Finishing

## Status Pesanan

1. Menunggu Desain
2. Siap Cetak
3. Proses Cetak
4. Selesai
5. Diambil

---
Built with Laravel 11
