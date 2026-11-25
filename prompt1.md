# Prompt 1 — Fondasi database Workshop Catering

Kamu membantu mahasiswa membangun fondasi database aplikasi Workshop Catering dari starter Laravel 13 pada repositori `bintangawan/workshop-catering`. Gunakan struktur Laravel yang sudah ada. Sumber kebutuhan adalah `PRD.md` dan gambar ERD yang dilampirkan. Kerjakan pada branch latihan mahasiswa dari `main`.

## Cakupan

1. Baca PRD dan ERD, lalu jelaskan singkat alur admin dan pelanggan serta relasi data. Cek model dan migration yang sudah ada agar tidak membuat tabel atau model ganda.
2. Buat tiga migration MySQL terpisah dalam urutan `users → menus → orders`, masing-masing dengan `up()` dan `down()`. Ikuti spesifikasi berikut.

| Tabel    | Kolom                                                                                                                                                                                                           |
| -------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `users`  | `id()`, `name` string 120, `email` string 255 UNIQUE, `password` string 255, `role` enum `admin/customer` default `customer`, `rememberToken()`, `timestamps()`                                                 |
| `menus`  | `id()`, `name` string 150, `description` text nullable, `price` unsigned integer, `is_active` boolean default true, `timestamps()`                                                                              |
| `orders` | `id()`, `user_id` FK ke `users.id`, `menu_id` FK ke `menus.id`, `quantity` unsigned small integer, `unit_price` unsigned integer, `status` enum `pending/confirmed/completed` default `pending`, `timestamps()` |

3. Pakai `restrictOnDelete()` untuk kedua FK pada `orders`. Satu pengguna dan satu menu masing-masing bisa punya banyak pesanan. Harga per porsi disimpan sebagai rupiah bulat. Harga historis ada di `orders.unit_price` dan total dihitung dari `quantity × unit_price`.
4. Sesuaikan model yang sudah ada. Pertahankan `User::orders()` dan `Menu::orders()` sebagai `HasMany`, serta `Order::customer()` melalui FK `user_id` dan `Order::menu()` sebagai `BelongsTo`. Pertahankan cast password dan atribut yang sudah relevan. Samakan default model untuk role, menu aktif, dan status dengan migration. Tambahkan `Order::totalPrice(): int`. Atribut role, harga pesanan, identitas pelanggan, dan status ditetapkan secara eksplisit oleh kode aplikasi pada tahap fitur.
5. Periksa sintaks PHP. Kalau database MySQL lokal workshop siap, jalankan `php artisan migrate` lalu `php artisan migrate:status`. Gunakan database latihan dan hindari perintah reset skema. Jika koneksi belum tersedia, jelaskan pengujian yang sudah dilakukan dan apa yang belum bisa diperiksa.

Akhiri dengan daftar file yang diubah, ringkasan relasi dan default, serta hasil verifikasi. Tahap ini hanya mencakup model dan migration. Login, seeder, route, controller, service, Blade, dan perubahan dependensi dikerjakan di tahap terpisah. Biarkan mahasiswa meninjau diff sebelum commit atau push.

PROMPT:
Kerjakan tahap 1 Workshop Catering sesuai prompt1.md yang saya lampirkan. Gunakan PRD.md dan gambar ERD sebagai acuan.

Mulai dari branch main repositori bintangawan/workshop-catering dan buat branch latihan saya sendiri. Baca proses bisnisnya, lalu buat tiga migration MySQL dan sesuaikan model User, Menu, serta Order yang sudah ada.

Kerjakan migration dan model saja. Setelah selesai, tampilkan daftar file yang diubah, ringkasan relasi dan aturan database, serta hasil verifikasi. Berhenti setelah diff siap saya tinjau sebelum commit atau push.
