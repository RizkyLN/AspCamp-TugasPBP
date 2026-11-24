# AspCamp — Sistem Aspirasi & Suara Kampus

Aplikasi web berbasis Laravel untuk pengelolaan aspirasi mahasiswa, pelaporan anonim menggunakan kode kelola, serta tindak lanjut keputusan oleh pengelola.

## Tim Pengembang

| No | Nama Lengkap | NIM | Username GitHub | Peran / Fokus Modul |
| :--- | :--- | :--- | :--- | :--- |
| 1 | Rizky Lainin Nafis | 241240001524 | @RizkyLN | Arsitektur Dasar, Autentikasi, Database & Fitur Suara Utama |
| 2 | *(Diisi Anggota 2)* | *(NIM)* | *(Username)* | Modul Pengelola Suara & Tindak Lanjut Keputusan |
| 3 | *(Diisi Anggota 3)* | *(NIM)* | *(Username)* | Modul Edit Aspirasi Anonim & Refactoring Form |

## Log Kontribusi & Pembaruan Fitur

### 1. Rizky Lainin Nafis (@RizkyLN) — *Initial Release*
- Membangun struktur dasar aplikasi Laravel (`TugasPBP`) dan skema migrasi database (`users`, `suara`, `kategori`, `suara_lampiran`, `suara_riwayat`).
- Mengimplementasikan sistem autentikasi (`AuthController`), middleware hak akses (`CheckRole`), dan manajemen profil pengguna (`ProfilController`).
- Membuat fitur pengiriman dan pengelolaan suara pengguna (`SuaraController`) serta pengiriman aspirasi anonim (`AnonimController`).

### 2. [Nama Anggota 2] — *Pembaruan Modul Pengelola*
*(Akan diisi oleh Anggota 2 saat commit)*

### 3. [Nama Anggota 3] — *Pembaruan Modul Anonim & Komponen Form*
*(Akan diisi oleh Anggota 3 saat commit)*

---

## Cara Menjalankan Proyek di Lokal

1. Clone repository ini:
   ```bash
   git clone [https://github.com/RizkyLN/AspCamp-TugasPBP.git](https://github.com/RizkyLN/AspCamp-TugasPBP.git)
   cd AspCamp-TugasPBP