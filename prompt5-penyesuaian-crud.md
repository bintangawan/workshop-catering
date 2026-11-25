# Prompt 5 — Penyesuaian CRUD Kelola Menu

Lanjutkan dari tahap 4 **Workshop Catering** di branch latihan. Fokus pada halaman admin **Kelola Menu**. Di versi sebelumnya, tombol **Hapus** diganti menjadi **Nonaktifkan** jika menu sudah pernah dipesan. Buat kedua tindakan mudah dibedakan dan uji alur CRUD dari browser.

## Alur CRUD

| Operasi | Aksi admin | Hasil yang diharapkan |
|---|---|---|
| Create | Klik **Tambah menu**, isi form modal, dan simpan. | Menu muncul di daftar admin. Menu aktif juga tampil di katalog pelanggan. |
| Read | Buka **Kelola Menu**. | Kartu menu menampilkan nama, deskripsi, harga, status aktif dan foto bila tersedia. |
| Update | Klik **Edit** dan simpan perubahan dari modal. | Nama, harga, status atau foto diperbarui. Total pesanan yang lama tetap memakai harga saat dipesan. |
| Delete | Klik **Hapus** pada menu yang belum pernah dipesan, lalu konfirmasi. | Menu hilang dari daftar dan katalog. File foto ikut dibersihkan jika ada. Jika dibatalkan, menu tetap ada. |
| Nonaktifkan | Klik **Nonaktifkan** pada menu aktif yang punya pesanan. | Menu hilang dari katalog, tetapi tetap terlihat di admin bersama riwayat pesanan dan fotonya. |

## Perubahan yang dikerjakan

1. Ubah `resources/views/admin/menus/index.blade.php`. Tampilkan aksi **Edit** dan **Hapus** di setiap kartu. Untuk menu yang punya riwayat pesanan, render **Hapus** sebagai tombol nonaktif dengan pesan singkat tentang alasannya. Untuk menu aktif yang punya pesanan, sediakan **Nonaktifkan** di kartu yang sama.
2. Gunakan route `admin.menus.destroy` yang sudah ada untuk hapus atau nonaktifkan. Form mengirim `POST` dengan `@csrf` dan `@method('DELETE')`, serta meminta konfirmasi SweetAlert2. Label konfirmasi harus sesuai dengan tindakan yang tampil di kartu.
3. Pertahankan pemeriksaan di server pada `CateringService::deleteMenu()`. Service memeriksa keberadaan pesanan sebelum menghapus. Jika sudah ada pesanan, nonaktifkan menu dan pertahankan file fotonya. Foreign key tetap melindungi riwayat. Gunakan struktur route, controller, dan migration yang sudah ada.
4. Sesuaikan `tests/Feature/ExampleTest.php` untuk memeriksa kondisi tombol aktif dan nonaktif. Pakai kembali tes penghapusan menu serta foto, pembatasan role, dan riwayat pesanan.

## Cara verifikasi

- Sebagai admin, buat **menu baru** yang belum pernah dipesan. Cek daftar, edit dari modal, batalkan konfirmasi Hapus sekali, lalu konfirmasi Hapus. Pastikan menu dan foto hilang.
- Buat pesanan dari akun pelanggan, lalu buka menu yang dipesan dari admin. Pastikan **Hapus** terlihat tetapi nonaktif, dan aksi **Nonaktifkan** tetap tersedia. Setelah nonaktif, cek katalog pelanggan dan cek pesanan lama masih tampil.
- Jalankan `php artisan test` dan `npm run build`. Laporkan file yang diubah dan hasil cek Create, Read, Update, dan Delete per skenario. Berhenti setelah diff siap ditinjau sebelum commit atau push dari branch latihan.
