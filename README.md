#  Proyek RPL: Aplikasi Crowdfunding "BantuSesama"

Aplikasi **BantuSesama** adalah platform penggalangan dana berbasis web yang dirancang untuk menjembatani donatur publik dengan pengelola aksi sosial secara transparan, aman, dan real-time. Proyek ini dibangun menggunakan arsitektur modular PHP Native dan Bootstrap 5 sebagai pemenuhan Tugas Akhir mata kuliah Rekayasa Perangkat Lunak.

---

##  Informasi Kelompok 
*   **Anggota 1:** Raisyi Salsabila (2411102441210) — *Full-Stack Developer & DevOps*
*   **Anggota 2:** Nabiilah (2411102441202) — *System Analyst & UI/UX Designer*
*   **Anggota 3:** Gabrilla Aszahra Samad (2411102441174) — *Software QA & Technical Writer*

---

##  Akses Tautan Resmi
*   **Live Website (Hosting):** [http://bantusesama-rpl.infinityfreeapp.com](http://bantusesama-rpl.infinityfreeapp.com)
*   **Repositori GitHub:** [https://github.com/GABRILLA/bantusesama](https://github.com/GABRILLA/bantusesama)

---

##  Fitur Utama Aplikasi
1.  **Visualisasi Progress Bar:** Grafik interaktif ketercapaian dana publik secara dinamis di halaman utama.
2.  **Sistem Submisi Donasi:** Formulir donatur mandiri yang dilengkapi fitur wajib unggah berkas fisik bukti transfer.
3.  **Kotak Informasi Rekening:** Petunjuk nomor rekening resmi lembaga penyaluran untuk memvalidasi alur simulasi pengujian.
4.  **Session Security Enforcement:** Proteksi enkapsulasi pada direktori `admin.php` guna menangkal eksploitasi bypass URL ilegal.
5.  **Dashboard Kontrol Panel Admin:** Fitur bagi administrator untuk melakukan aksi *Approve/Reject* transaksi donasi serta memodifikasi target dana kampanye langsung ke database.

---

##  Struktur File Proyek
*   `index.php` — Beranda utama publik dan formulir donasi.
*   `donatur.php` — Halaman transparansi log riwayat donatur yang sukses terverifikasi.
*   `login.php` — Gerbang otentikasi kredensial keamanan akun admin.
*   `admin.php` — Dashboard manajemen data, verifikasi struk, dan update target.
*   `logout.php` — Penghancuran token sesi aktif (session destroy).
*   `uploads/` — Direktori penyimpanan lokal berkas gambar bukti transfer.

---

## Panduan Instalasi & Dependensi
Aplikasi ini berjalan tanpa menggunakan framework eksternal (*Zero Dependencies*), sehingga sangat ringan dan kompatibel di berbagai web server.

### Cara Menjalankan Secara Lokal (Localhost):
1.  Unduh seluruh kode sumber dari repositori ini berupa file `.zip` atau lakukan `git clone`.
2.  Pindahkan folder proyek ke dalam direktori server lokal Anda (misal: `C:/xampp/htdocs/bantusesama/`).
3.  Buka **phpMyAdmin**, buat database baru dengan nama `if0_42095823_bantusesama` (atau sesuaikan), lalu import file database `.sql` Anda.
4.  Sesuaikan parameter konfigurasi host, user, dan password pada baris kode koneksi database di file proyek Anda.
5.  Akses aplikasi melalui browser dengan mengetik URL: `http://localhost/bantusesama`.

