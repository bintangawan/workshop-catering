# Workshop Catering

Starter praktik pemesanan katering dengan Laravel 13, Blade, MySQL, Vite, Tailwind CSS, dan SweetAlert2. Branch `dev-bintang` berisi implementasi login dan lima halaman bisnis. Dashboard admin memakai data pesanan dan menu dari database.

## Prasyarat

| Perangkat | Versi |
| --- | --- |
| PHP | 8.3 atau lebih baru, dengan ekstensi PDO MySQL, cURL, DOM/XML, Fileinfo, Mbstring, OpenSSL, dan Tokenizer |
| Composer | 2.x |
| MySQL | 8.0 atau 8.4 LTS |
| Node.js | 22 LTS mulai 22.12 atau 24 LTS, beserta npm |
| Git | Untuk mengambil proyek |

## Menjalankan starter

```sh
git clone https://github.com/bintangawan/workshop-catering.git
cd workshop-catering
git switch dev-bintang
composer install
npm ci
```

Salin `.env.example` ke `.env`. Di PowerShell gunakan `Copy-Item .env.example .env`, atau di Linux/macOS gunakan `cp .env.example .env`. Untuk praktik database, buat database MySQL lokal `workshop_catering` dan sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, serta `DB_PASSWORD` di `.env`.

```sh
php artisan key:generate
npm run build
php artisan serve
```

Buka [http://127.0.0.1:8000](http://127.0.0.1:8000). Saat mengedit Blade dan aset, jalankan `npm run dev` di terminal kedua agar perubahan dimuat oleh Vite. Jalankan `php artisan test` untuk memeriksa starter.

## Struktur

| Lokasi | Fungsi |
| --- | --- |
| `routes/web.php` | Route web dan pengelompokan akses admin/pelanggan saat praktik |
| `app/Http/Controllers/` | Controller HTTP. Validasi input form dan respons berada di sini untuk praktik awal. |
| `app/Services/CateringService.php` | Aturan pemesanan, hapus atau nonaktifkan menu, dan transisi status |
| `app/Models/` | Model `User`, `Menu`, dan `Order` beserta relasi Eloquent |
| `database/migrations/` | Tiga migration yang dibuat pada tahap 1 |
| `resources/views/layouts/app.blade.php` | Layout utama Blade yang memuat aset Vite secara global |
| `resources/views/admin/` | Blade untuk pengelolaan menu dan pesanan masuk |
| `resources/views/customer/` | Blade untuk katalog, formulir pemesanan, dan riwayat pelanggan |
| `resources/css/app.css` dan `resources/js/app.js` | Tailwind CSS dan notifikasi SweetAlert2 |

Untuk mengirim notifikasi dari controller, tambahkan flash session `->with('success', 'Menu berhasil disimpan.')` atau `->with('error', 'Menu tidak tersedia.')` pada respons redirect. Layout membaca flash session dan menampilkannya dengan SweetAlert2. Pesan validasi form akan ditampilkan di bawah input dengan `@error`.

## Peta lima halaman praktik

| Halaman | Route rencana | Controller | Blade |
| --- | --- | --- | --- |
| Kelola Menu | `GET /admin/menus` | `AdminMenuController` | `admin/menus/index.blade.php` dan partial form |
| Pesanan Masuk | `GET /admin/orders` | `AdminOrderController` | `admin/orders/index.blade.php` |
| Katalog Menu | `GET /menus` | `CatalogController` | `customer/catalog/index.blade.php` |
| Form Pemesanan | `GET /menus/{menu}/order`, `POST /menus/{menu}/order` | `CustomerOrderController` | `customer/orders/create.blade.php` |
| Pesanan Saya | `GET /orders` | `CustomerOrderController` | `customer/orders/index.blade.php` |

Admin membuat menu aktif. Pelanggan membukanya dari katalog, memilih jumlah porsi, lalu membuat pesanan. Server mengambil harga dari menu dan mencatatnya di pesanan. Admin memindahkan status dari `pending` ke `confirmed` lalu `completed`. Pelanggan melihat perubahan di daftar pesanannya sendiri.

Migration dibuat dalam urutan `users` (termasuk `role`), `menus` (`name`, `description`, `price`, `is_active`), lalu `orders` (`user_id`, `menu_id`, `quantity`, `unit_price`, `status`). Harga disimpan sebagai rupiah bulat. Total dihitung dari `quantity × unit_price`. Menu yang punya pesanan dinonaktifkan agar relasi pesanan tetap utuh.

## Dependensi proyek

- **PHP** — Laravel Framework 13.x dan Tinker 3.x. PHPUnit, Mockery, Collision, dan Pint dipakai saat pengembangan.
- **Frontend** — Vite 8, Laravel Vite Plugin 3, Tailwind CSS 4, plugin Tailwind untuk Vite, dan SweetAlert2 11. Blade memuat CSS dan JavaScript sekali melalui layout utama.

Versi rinci disimpan di `composer.lock` dan `package-lock.json` agar instalasi di laptop peserta konsisten.

## Menjalankan backend tahap 3

Jalankan perintah ini setelah membuat database MySQL lokal dan menyesuaikan `.env`.

```sh
php artisan migrate --seed
npm run build
php artisan serve
```

Buka `/login`. Seeder menyediakan akun berikut pada lingkungan `local` atau `testing`.

| Role | Email | Kata sandi |
| --- | --- | --- |
| Admin | `admin@catering.test` | `Workshop123!` |
| Pelanggan | `pelanggan@catering.test` | `Workshop123!` |

Seeder juga menambahkan tiga menu. Buat pesanan pertama dari akun pelanggan untuk melihat metrik dashboard terisi. Nilai pesanan pada dashboard mencakup ketiga status selama tujuh hari kalender terakhir (WIB). Jalankan `php artisan test` dengan ekstensi PDO SQLite untuk pengujian otomatis. Lihat [prompt3.md](prompt3.md) untuk peta file dan langkah pengerjaan. Prototipe dengan data dummy dapat dilihat pada commit `6a32291`.
