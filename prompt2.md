# Prompt 2 — Blade dan styling Workshop Catering

Kerjakan tahap 2 pada branch latihan mahasiswa yang sudah memuat hasil `prompt1.md`. Baca `PRD.md`, cek model dan migration yang ada, lalu gunakan desain [Catering Dashboard Management Website di Google Stitch](https://stitch.google.com/projects/3177614277072323778) sebagai referensi visual. Gunakan sidebar terang, konten putih dengan latar abu-abu muda, aksen teal, tipografi Plus Jakarta Sans, dua grafik, dan kartu menu berfoto. Sederhanakan kepadatan layar agar sesuai dengan lima halaman di PRD.

## Halaman dan navigasi

| Tampilan        | Route pratinjau               | Isi minimum                                                                                                                                                                              |
| --------------- | ----------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Portal demo     | `/`                           | Pilihan masuk ke tampilan Admin atau Pelanggan melalui tautan, tanpa autentikasi.                                                                                                        |
| Dashboard admin | `/preview/admin`              | Empat kartu: total pesanan, nilai pesanan, perlu konfirmasi, dan menu aktif. Buat dua grafik dari data contoh: pesanan harian dan distribusi status. Tampilkan beberapa pesanan terbaru. |
| Kelola Menu     | `/preview/admin/menus`        | Daftar menu, indikator aktif, form tambah/edit yang berbagi partial, tombol nonaktifkan atau hapus sesuai aturan PRD.                                                                    |
| Pesanan Masuk   | `/preview/admin/orders`       | Tabel pesanan, pelanggan, porsi, total, status, dan aksi simulasi konfirmasi atau selesai.                                                                                               |
| Katalog Menu    | `/preview/menus`              | Menu aktif, deskripsi, harga per porsi, dan tautan ke pemesanan.                                                                                                                         |
| Form Pemesanan  | `/preview/menus/{menu}/order` | Satu menu per pesanan, input jumlah porsi dengan rentang 1–65.535, preview total yang berubah ketika jumlah diubah.                                                                      |
| Pesanan Saya    | `/preview/orders`             | Riwayat pengguna demo, harga per porsi saat dipesan, jumlah, total, dan status.                                                                                                          |

Dashboard dan portal adalah layar pendukung untuk pratinjau. Halaman bisnis tetap mengikuti lima halaman di PRD.

## Aturan implementasi

1. Gunakan Blade, Tailwind CSS global melalui Vite, dan SweetAlert2 yang sudah tersedia. Pisahkan layout dasar, layout admin, layout pelanggan, serta komponen Blade yang dipakai bersama. Pastikan navigasi dan tabel dapat dioperasikan di layar kecil.
2. Taruh fixture PHP terpusat, misalnya `resources/demo/catering.php`. Buat route GET pratinjau untuk merender Blade. Gunakan fixture untuk mengisi grafik, ringkasan, kartu, dan tabel. Simulasikan aksi lewat JavaScript dan penyimpanan sesi peramban bila perlu.
3. Cocokkan atribut bisnis dengan ERD. `menus` memakai `id`, `name`, `description`, `price`, `is_active`. `orders` memakai `id`, `user_id`, `menu_id`, `quantity`, `unit_price`, `status`, dan `created_at` untuk tanggal serta grafik. Pemetaan foto ilustrasi boleh ada sebagai data presentasi di luar tabel. Cari nama pelanggan dan menu melalui ID fixture. Riwayat pelanggan mengambil pesanan dengan `user_id` pengguna demo saja.
4. Hitung total dengan `quantity × unit_price` dalam rupiah bulat. Gunakan status `pending → confirmed → completed` dengan label Menunggu, Dikonfirmasi, dan Selesai. Tampilkan menu aktif saja di katalog. Nilai pesanan di dashboard adalah penjumlahan total, bukan data pembayaran.
5. Tahap ini khusus tampilan. Hindari query MySQL atau Eloquent, endpoint tulis, autentikasi, dan perubahan pada migration atau model. Pembayaran, DP, inventaris dapur, profit, langganan, dan pengiriman ada di luar cakupan PRD.
6. Jalankan `npm run build`, buka seluruh halaman dengan database tidak berjalan, dan coba navigasi, form, perubahan jumlah dan total, notifikasi, serta status demo. Tampilkan daftar file, URL pratinjau, dan hasil pengecekan. Berhenti setelah diff siap ditinjau sebelum commit atau push.

PROMPT:
Lanjutkan ke tahap 2 Workshop Catering pada branch latihan saya yang sudah menyelesaikan tahap 1. Ikuti prompt2.md yang saya lampirkan. Gunakan PRD, ERD, dan desain Google Stitch yang dirujuk di dalamnya sebagai acuan.

Bangun prototipe Blade dengan Tailwind CSS dan SweetAlert2. Buat lima halaman sesuai PRD, portal pemilihan peran demo, dan dashboard admin dengan grafik pesanan harian serta distribusi status.

Gunakan data dummy sesuai kolom migration. Total dihitung dari jumlah porsi × harga saat dipesan. Uji simulasi pemesanan dan perubahan status di browser dengan MySQL dimatikan.

Setelah selesai, kirim daftar file yang diubah, URL pratinjau, dan hasil pengujian. Berhenti setelah diff siap saya tinjau sebelum commit atau push.
