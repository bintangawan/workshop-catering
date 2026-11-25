# Prompt 4 — Registrasi, modal, dan foto menu

Lanjutkan dari hasil tahap 3 Workshop Catering pada branch latihan. Kerjakan tiga temuan audit ini dengan tetap memakai Laravel 13, Blade, Tailwind CSS, dan satu `CateringService.php`. Gunakan route, controller, dan komponen yang sudah ada.

## 1. Registrasi pelanggan

- Tambahkan tautan **Daftar sebagai pelanggan** pada halaman login. Sediakan halaman `GET /register` dan proses `POST /register` untuk tamu. Gunakan `welcome.blade.php` untuk kedua tampilan.
- Minta nama lengkap, email unik, kata sandi minimal 8 karakter dan maksimal 72 byte, serta konfirmasi kata sandi. Normalisasi email dan gunakan cast password `hashed` yang sudah tersedia.
- Tetapkan `User::ROLE_CUSTOMER` di server. Tambahkan CSRF dan pembatasan percobaan registrasi. Setelah berhasil, login otomatis, regenerasi sesi, dan arahkan ke katalog. Akun baru dapat login kembali setelah logout.

## 2. Form tambah dan edit dalam modal

- Ganti tautan scroll **Tambah menu** dengan tombol yang membuka elemen native `<dialog>` melalui `showModal()`. Pakai partial form yang sama untuk tambah dan edit.
- Tombol **Edit** boleh memakai query `?edit={id}` dan membuka dialog otomatis. Modal tetap bisa ditutup lewat tombol **Tutup** atau Escape. Jika Laravel mengembalikan error validasi, buka modal kembali dengan nilai `old()` dan pesan di dekat input.

## 3. Unggah foto menu

- Buat migration tambahan di `database/migrations/2026_11_26_000003_add_image_path_to_menus_table.php` dengan `image_path` berupa `VARCHAR(255) NULL` pada tabel `menus`. Implementasikan `up()` dan `down()`. Jalankan migration baru pada database tahap 3 yang sudah ada.
- Tambahkan input `image` opsional melalui `enctype="multipart/form-data"`. Validasi di server bahwa file benar-benar gambar JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.
- Simpan foto di disk `public` pada direktori `menus/`. Database hanya menyimpan path. Tampilkan foto melalui komponen `menu-photo.blade.php` untuk kartu admin, katalog, dan form pemesanan. Pertahankan gambar contoh atau placeholder sebagai cadangan.
- Saat foto diganti, hapus file lama setelah perubahan database berhasil. Menyimpan edit tanpa file baru mempertahankan foto lama. Hapus file foto ketika menu benar-benar dihapus dan pertahankan foto ketika menu hanya dinonaktifkan. Pakai service yang sama untuk seluruh penanganan file menu.

## Peta file

| File | Pekerjaan |
|---|---|
| `routes/web.php` | Tambahkan route registrasi tamu. |
| `app/Http/Controllers/AuthController.php` | Validasi registrasi, tetapkan role pelanggan, buat akun, dan autentikasi. |
| `resources/views/welcome.blade.php` | Tautan daftar, form registrasi, dan tautan kembali ke login. |
| `resources/views/admin/menus/index.blade.php` | Tombol dan dialog tambah/edit, serta pembukaan ulang saat terjadi error. |
| `resources/views/admin/menus/form.blade.php` | Form multipart, input foto, tampilan foto sebelumnya, dan pesan kesalahan. |
| `resources/js/app.js`, `resources/css/app.css` | Interaksi buka/tutup modal dan styling responsif. |
| `app/Http/Controllers/CateringController.php` | Validasi upload dan arahkan penyimpanan menu ke service. |
| `app/Services/CateringService.php` | Simpan menu dan file, ganti foto, dan bersihkan file setelah perubahan database. |
| `database/migrations/2026_11_26_000003_add_image_path_to_menus_table.php` | Tambahkan dan rollback kolom foto opsional. |
| `resources/views/components/menu-photo.blade.php` | Pakai URL foto unggahan atau visual cadangan. |
| `tests/Feature/ExampleTest.php` | Cek registrasi pelanggan serta penyimpanan, penggantian, dan penghapusan foto. |

## Verifikasi

Jalankan `php artisan migrate`, `php artisan storage:link`, `npm run build`, dan `php artisan test`. Pastikan `APP_URL` cocok dengan alamat aplikasi agar URL foto benar. Gunakan database latihan dan jalankan `migrate` biasa supaya data tahap 3 tetap tersimpan. Untuk berkas mendekati 2 MB, atur `upload_max_filesize` minimal 2 MB dan `post_max_size` lebih besar dari ukuran unggahan.

1. Daftar dari halaman login, cek diarahkan ke katalog, lalu logout dan login lagi memakai akun baru. Cek email ganda dan pastikan pengiriman role `admin` tetap menghasilkan akun pelanggan.
2. Klik **Tambah menu** dan pastikan modal muncul tanpa menggeser halaman ke formulir bawah. Coba validasi gagal, kemudian cek modal terbuka kembali dengan error dan isian yang dipertahankan.
3. Unggah foto valid, cek tampil di admin dan pelanggan, edit tanpa foto baru, lalu ganti foto. Coba file non-gambar dan file di atas batas ukuran. Pastikan file dibuang saat menu dihapus, dan tetap ada saat menu hanya dinonaktifkan.

Laporkan daftar file, hasil menjalankan migration, dan hasil uji. Berhenti setelah perubahan siap ditinjau sebelum commit atau push dari branch latihan.
