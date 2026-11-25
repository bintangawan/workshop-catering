# Prompt 3 — Backend Workshop Catering

Lanjutkan di branch latihan yang sudah punya migration tahap 1 dan template Blade tahap 2. Baca `PRD.md`, cek ERD, lalu hubungkan tampilan yang sudah ada ke MySQL. Pertahankan struktur sederhana dengan dua controller dan **satu service**. Fokus pada bagian yang dibutuhkan untuk menjalankan alur admin dan pelanggan.

## File yang dibuat

| File | Isi |
|---|---|
| `app/Http/Controllers/AuthController.php` | Validasi login, autentikasi Laravel, regenerasi sesi, tujuan berdasarkan role, dan logout. |
| `app/Http/Controllers/CateringController.php` | Dashboard, CRUD menu, katalog, form dan penyimpanan pesanan, pesanan masuk, dan riwayat pelanggan. Validasi input serta respons HTTP dibuat di sini. |
| `app/Services/CateringService.php` | Logika transaksi untuk menyimpan pesanan dan snapshot harga, menghapus atau menonaktifkan menu, serta menjaga urutan status. |

## File yang diperbarui

| File | Perubahan |
|---|---|
| `routes/web.php` | Daftarkan route asli. Gunakan `guest` untuk login dan `auth` plus gate untuk halaman dan aksi tiap role. |
| `app/Providers/AppServiceProvider.php` | Tambahkan gate `access-admin` dan `access-customer` memakai konstanta role dari model `User`. |
| `bootstrap/app.php` | Arahkan pengguna yang sudah login dari halaman login ke route awal `/`. |
| `database/seeders/DatabaseSeeder.php` | Buat dua akun dan tiga menu untuk lingkungan `local` atau `testing`. Seeder dapat dijalankan ulang tanpa menggandakan data. |
| `resources/views/welcome.blade.php` | Ubah portal pilih peran menjadi form email dan kata sandi. |
| `resources/views/layouts/admin.blade.php`, `resources/views/layouts/customer.blade.php`, `resources/views/components/brand.blade.php` | Gunakan navigasi route asli, nama dari sesi, dan logout lewat POST. |
| `resources/views/admin/dashboard.blade.php` | Ganti kartu dan grafik fixture menjadi data yang dikirim controller. |
| `resources/views/admin/menus/index.blade.php`, `resources/views/admin/menus/form.blade.php` | Hubungkan daftar, form tambah/edit bersama, dan aksi hapus atau nonaktifkan. Form edit bisa dibuka dengan parameter `?edit={id}`. |
| `resources/views/admin/orders/index.blade.php`, `resources/views/components/order-table.blade.php` | Tampilkan relasi pelanggan dan menu, total historis, status, serta aksi status admin. |
| `resources/views/customer/catalog/index.blade.php`, `resources/views/customer/orders/create.blade.php`, `resources/views/customer/orders/index.blade.php` | Pakai data asli untuk katalog, form porsi, dan pesanan pengguna yang login. |
| `resources/views/components/menu-photo.blade.php` | Pertahankan ilustrasi lokal untuk menu contoh. Gunakan placeholder untuk menu lain. Foto tidak menjadi atribut database. |
| `resources/js/app.js`, `resources/css/app.css` | Pertahankan SweetAlert2, estimasi harga di klien, dan styling yang dipakai. Lepas kode simulasi dan gaya elemen demo yang sudah tidak dipakai. |
| `tests/Feature/ExampleTest.php`, `README.md` | Sesuaikan pengujian utama dan tulis cara menjalankan tahap 3. |

Hapus `resources/js/catering-preview.js` dan `resources/demo/catering.php` setelah referensinya diganti. Gunakan tiga migration dan tiga model tahap 1 yang sudah tersedia.

## Peta route

| Akses | Metode dan URL | Aksi |
|---|---|---|
| Tamu | `GET /login`, `POST /login` | Tampilkan dan proses login. Batasi percobaan login. |
| Sudah login | `GET /`, `POST /logout` | Arahkan sesuai role atau akhiri sesi. |
| Admin | `GET /admin` | Dashboard. |
| Admin | `GET /admin/menus`, `POST /admin/menus`, `PATCH /admin/menus/{menu}`, `DELETE /admin/menus/{menu}` | Kelola Menu. |
| Admin | `GET /admin/orders`, `PATCH /admin/orders/{order}/status` | Pesanan Masuk dan perubahan status. |
| Pelanggan | `GET /menus` | Katalog menu aktif. |
| Pelanggan | `GET /menus/{menu}/order`, `POST /menus/{menu}/order` | Form Pemesanan dan penyimpanan pesanan. |
| Pelanggan | `GET /orders` | Pesanan Saya. |

## Langkah dan aturan

1. **Login dan role.** Gunakan `Auth::attempt()` dengan email dan kata sandi, lalu regenerasi sesi. Ambil role dari akun yang terautentikasi. Admin diarahkan ke dashboard dan pelanggan ke katalog. Saat logout, invalidasi sesi dan regenerasi token CSRF. Batasi aksi mutasi dengan gate di server, termasuk saat URL dipanggil langsung.
2. **CRUD menu.** Validasi nama maksimum 150 karakter, deskripsi opsional maksimum 1.000 karakter, harga rupiah bulat 1–4.294.967.295, dan `is_active` boolean. Pakai satu partial form untuk tambah dan edit. Hapus menu yang belum dipesan. Nonaktifkan menu yang punya pesanan. Pada penghapusan dan penyimpanan pesanan, kunci baris menu dalam transaksi sebelum memeriksa ulang kondisi.
3. **Pemesanan.** Katalog hanya menampilkan menu aktif. Jumlah porsi wajib bilangan bulat 1–65.535. Isi `user_id` dari sesi, `menu_id` dari route, dan `unit_price` dari harga menu yang dibaca ulang pada transaksi. Mulai dengan `pending`. Total riwayat berasal dari `Order::totalPrice()`. Baris pesanan pelanggan harus dibatasi sejak query melalui relasi akun yang login.
4. **Status.** Hanya admin yang dapat mengubah `pending → confirmed → completed`. Cocokkan status sebelumnya di kondisi query update agar request lama atau ganda tidak melompati status. Tampilkan label Menunggu, Dikonfirmasi, dan Selesai.
5. **Dashboard.** Pakai tujuh hari kalender terakhir WIB untuk total pesanan, nilai pesanan, jumlah yang perlu konfirmasi, tren harian, dan distribusi status. Tampilkan tiga status, termasuk saat jumlahnya nol. Kartu menu aktif menghitung seluruh menu aktif. Nilai pesanan merupakan penjumlahan `quantity × unit_price` dan tidak menunjukkan kas masuk. Tampilkan empat pesanan terbaru dengan relasi yang sudah dimuat.
6. **Blade.** Pertahankan tampilan tahap 2. Gunakan `@csrf`, `@method`, `old()`, dan `@error`. Untuk perubahan atau hapus, tampilkan konfirmasi dan flash SweetAlert2. Estimasi di browser hanya untuk tampilan. Harga final dihitung server.

## Menjalankan dan memverifikasi

Atur `.env` ke database MySQL lokal workshop. Jalankan `php artisan migrate --seed`, `npm ci`, `npm run build`, dan `php artisan serve`. Akun contoh `admin@catering.test` dan `pelanggan@catering.test` memakai kata sandi `Workshop123!` pada lingkungan lokal workshop.

- Pastikan tamu diarahkan ke login. Coba kata sandi salah, login dua role, akses silang yang ditolak, dan logout.
- Tambah serta edit menu, lalu hapus menu yang belum dipesan. Pesan **5 porsi seharga Rp25.000** dari akun pelanggan. Pastikan harga total **Rp125.000** dan status **Menunggu**.
- Ubah harga menu menjadi Rp30.000 dan periksa total lama tetap Rp125.000. Nonaktifkan menu melalui aksi hapus admin, lalu periksa menu hilang dari katalog dan pesanan lama masih terlihat.
- Konfirmasi dan selesaikan pesanan dari akun admin. Coba transisi yang melompati status dan permintaan dari role pelanggan. Pastikan pesanan akun lain tidak muncul di riwayat.
- Jalankan `php artisan route:list`, `php artisan test`, dan `npm run build`. Tes otomatis memakai SQLite. Laporkan secara terpisah jika eksekusi di MySQL belum bisa dilakukan.

Akhiri dengan daftar file, peta alur `route → controller → service/model → Blade`, dan hasil verifikasi. Berhenti setelah diff siap ditinjau sebelum commit atau push dari branch latihan mahasiswa.
