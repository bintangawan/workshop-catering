# Prompt 6 — Testing Akhir Workshop Catering

Lanjutkan pada branch latihan yang sudah menyelesaikan tahap 1–5. Gunakan PRD, migration, dan implementasi terkini untuk memeriksa apakah alur admin dan pelanggan siap didemokan. Tahap ini menguji fitur yang sudah ada. Catat temuan dan reproduksinya sebelum mengubah kode.

## 1. Persiapan dan tes otomatis

1. Periksa branch, commit terakhir, dan perubahan lokal dengan `git status -sb` serta `git log -1 --oneline`.
2. Jalankan `composer install` dan `npm ci` jika dependensi belum tersedia. Jalankan `npm run build`, `php artisan route:list`, dan `php artisan test`. Gunakan konfigurasi SQLite in-memory yang sudah ada di `phpunit.xml` untuk tes otomatis. Pastikan ekstensi PDO SQLite aktif dan konfigurasi pengujian tidak menunjuk ke database aktif. Mulai dari skenario yang ada di `tests/Feature/ExampleTest.php`.
3. Untuk uji integrasi di browser, siapkan database **MySQL lokal khusus pengujian**. Setelah menyesuaikan `.env` dan `APP_URL`, jalankan `php artisan migrate --seed`, `php artisan migrate:status`, `php artisan storage:link`, dan `php artisan serve`. Gunakan migration biasa agar data yang sudah ada di database uji tetap tersimpan. Hindari `migrate:fresh` dan `migrate:reset`.
4. Buka aplikasi dalam dua sesi terpisah, misalnya tab biasa untuk admin dan jendela privat untuk pelanggan. Akun demo di lingkungan lokal adalah `admin@catering.test` dan `pelanggan@catering.test`, dengan kata sandi `Workshop123!`. Buat akun pelanggan baru untuk mengetes registrasi. Jika MySQL tidak tersedia, laporkan pengujian MySQL sebagai **Belum diuji**. Uji browser dengan SQLite boleh dicatat sebagai hasil terpisah.

## 2. Skenario uji

| ID | Skenario | Langkah dan hasil yang diharapkan |
| --- | --- | --- |
| T01 | Login, logout, dan hak akses | Tamu yang membuka halaman terlindungi diarahkan ke login. Kata sandi salah ditolak. Admin masuk ke dashboard dan pelanggan ke katalog. Akses silang ke halaman atau aksi role lain ditolak (403). Setelah logout, halaman terlindungi meminta login kembali. |
| T02 | Registrasi | Daftar dari tautan pada halaman login. Coba konfirmasi kata sandi yang berbeda dan email duplikat, lalu daftar dengan data valid. Akun baru masuk ke katalog, bisa login kembali, dan tetap ber-role pelanggan walau request menyertakan role admin. |
| T03 | Tambah, lihat, dan edit menu | Klik **Tambah menu** dan **Edit**. Form terbuka dalam modal. Coba input tidak valid dan pastikan modal muncul kembali dengan pesan serta nilai teks yang dipertahankan. Simpan menu dan cek nama, harga, serta status terbaru di admin dan katalog sesuai ketersediaannya. |
| T04 | Upload foto | Coba JPG, PNG, atau WebP yang valid. Pastikan foto bisa dibuka dari kartu admin dan katalog. Tolak berkas non-gambar serta berkas di atas 2 MB. Edit tanpa foto baru mempertahankan foto lama. Jika foto diganti, URL baru tampil dan file lama sudah terhapus. |
| T05 | Hapus menu tanpa pesanan | Tambah satu menu sementara dengan foto. Klik **Hapus**, batalkan konfirmasi, dan cek menu tetap ada. Ulangi dan konfirmasi, lalu pastikan menu hilang dari daftar dan katalog serta file fotonya terhapus. |
| T06 | Pesan menu dan harga historis | Pesan 5 porsi dengan harga Rp25.000 per porsi. Estimasi di form dan total yang disimpan adalah **Rp125.000**. Jumlah nol, pecahan, atau di atas 65.535 ditolak. Edit harga menu menjadi Rp30.000 dan pastikan total pesanan lama tetap Rp125.000. Periksa bahwa harga dan ID pelanggan berasal dari server serta sesi login. |
| T07 | Lindungi riwayat saat menu dinonaktifkan | Untuk menu yang sudah dipesan, **Hapus** terlihat nonaktif dan **Nonaktifkan** tersedia jika menu aktif. Nonaktifkan menu, lalu cek menu hilang dari katalog, pemesanan baru ditolak, dan riwayat serta foto tetap tersedia. |
| T08 | Status dan kepemilikan pesanan | Admin mengubah **Menunggu → Dikonfirmasi → Selesai**. Lompatan Menunggu langsung ke Selesai dan perubahan status oleh pelanggan ditolak. Cek status di Pesanan Saya dan pastikan akun pelanggan lain tidak melihat pesanan tersebut. |
| T09 | Dashboard | Cek dua grafik, ringkasan tujuh hari kalender, menu aktif, dan pesanan terbaru. Cocokkan indikator dengan data uji setelah membuat pesanan dan mengubah status. Nilai pesanan adalah jumlah porsi dikali harga saat dipesan, termasuk pesanan dari ketiga status dalam periode. |
| T10 | Tampilan dan kondisi kosong | Coba viewport desktop dan ponsel. Cek navigasi, modal, notifikasi SweetAlert2, tabel yang bisa digulir secara horizontal, dan pesan saat daftar pesanan kosong. |

## 3. Laporan akhir

Tampilkan ringkasan perintah dan hasilnya, branch dan commit yang diuji, serta mesin database yang dipakai **untuk masing-masing jenis tes**. Isi tabel ini dengan hasil yang benar-benar diamati.

| ID | Database dan cara uji | Hasil aktual | Status | Bukti atau catatan |
| --- | --- | --- | --- | --- |
| T01 dan seterusnya | SQLite otomatis, MySQL browser, atau lainnya | Isi dari eksekusi | Lulus / Gagal / Belum diuji | Output tes, tangkapan layar, atau langkah reproduksi |

Untuk temuan gagal, tulis langkah reproduksi, hasil yang diharapkan, hasil aktual, dan dampaknya terhadap demo. Tutup dengan keputusan **Siap demo** atau **Perlu perbaikan**, beserta alasannya. Jika pengujian MySQL masih tertunda, sebutkan terpisah dari keberhasilan tes SQLite. Berhenti setelah laporan siap ditinjau.
