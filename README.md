# Final_Project_Pemweb

# Nama Kelompok 
1. Irsyaad Nuur Wicaksana (23081010241)
2. Mohammad Sahrul Faza (23081010242)
3. Achmad Salman Pasha (23081010262)
4. Nathanael Kristian Sujarwo (23081010271)
5. Erwin Anindya Prasojo (23081010273)

# Pengembangan Sistem Penjualan dan Manajemen Berbasis Website
# Studi Kasus: Kafe Ngacup Surabaya 

# Deskripsi Singkat = Tujuan dari proyek ini adalah untuk membangun sebuah sistem berbasis web yang akan membantu proses penjualan dan manajemen produk, pesanan, dan pengguna di Kafe Ngacup Surabaya. Sistem ini akan melayani dua tipe pengguna utama, yaitu pengguna umum (pelanggan atau anggota) dan admin, masing-masing dengan fitur yang berbeda-beda sesuai dengan peran mereka.

# Alur Sistem

# Terdapat tiga aktor utama:
1. Guest: hanya dapat register dan login.
2. Member: setelah login dapat melihat menu, memesan produk, memilih metode pembayaran, dan membayar.
3. Admin: memiliki akses penuh untuk mengelola member, menu, serta status pesanan.

# Fitur-fitur yang ditangani oleh sistem antara lain:
1. Registrasi & Login pengguna
2. Manajemen profil dan tampilan menu
3. Proses pemesanan dan pembayaran
4. Manajemen menu dan member (khusus admin)
5. Validasi serta update status pesanan (oleh admin)

# Activity Diagram – User
- User melakukan login → sistem memvalidasi akun.
- Setelah berhasil, user diarahkan ke tampilan menu.
- user memilih item dan membuat pesanan → sistem menambahkannya ke keranjang.
- user memilih metode pembayaran → sistem mengirim notifikasi ke admin.
- Setelah itu, pengguna melakukan checkout → sistem memperbarui status pesanan.

# Activity Diagram – Admin
- Admin login → sistem memvalidasi akun dan menampilkan dashboard.
- Admin melakukan manajemen produk (tambah/edit menu).
- Admin dapat mengelola user dan pesanan.
- Sistem menampilkan daftar user dan daftar pesanan.
- Admin dapat mengubah status pesanan → sistem mengirim notifikasi ke pengguna.

# Teknologi yang Digunakan
- Frontend: HTML, CSS, PHP
- Backend: PHP
- Database: MySQL

# Fitur Utama
🔐 Autentikasi (Login & Register)
📋 Manajemen Menu Makanan/Minuman
🛒 Pemesanan Produk dan Pembayaran
🧑‍💼 Manajemen Pengguna dan Notifikasi Pesanan (Admin)
📈 Dashboard Admin Interaktif


