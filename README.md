# 💄 La Belle Peau: Modern E-commerce Website untuk Skincare 🌸

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Website E-commerce modern untuk produk skincare dengan fitur lengkap: shopping cart, checkout, payment gateway, dan dashboard client/admin yang responsif.**

*Made with ❤️ by  Natakenshi Developer x Azzam Codex*

</div>

---

## ✨ Features

### 🛒 Shopping & E-commerce
- **🛍️ Shopping Cart AJAX:** Tambahkan produk ke keranjang tanpa reload halaman dengan notifikasi pop-up modern (mobile & desktop).
- **📦 Product Grid Responsive:** Tampilan produk grid yang rapi, mobile-first, dengan filter kategori pop-up yang stylish.
- **💳 Multi-Step Checkout:** Proses checkout lengkap dengan form pengiriman, pilihan metode pembayaran dari database, dan konfirmasi order.
- **➕➖ Cart Quantity Control:** Edit jumlah produk (+/-) di halaman checkout dengan auto-update total harga via AJAX.
- **🗑️ Auto Remove Item:** Produk otomatis terhapus dari keranjang jika quantity dikurangi hingga 0.

### 👥 User Management
- **🔐 Authentication System:** Login & Register user dengan validasi email dan password yang aman.
- **📊 Client Dashboard Modern:** Dashboard user dengan statistik pesanan, total belanja, riwayat order, dan navigasi mobile-first (bottom nav).
- **👤 Profile Management:** User dapat mengedit nama dan email profil mereka sendiri.
- **📜 Order History:** Tampilan riwayat pesanan lengkap dengan badge status (Pending, Processing, Completed, Cancelled).

### 🎛️ Admin Panel
- **🔧 Product Management:** CRUD lengkap produk dengan upload gambar (auto-rename random), kategori, stok, dan deskripsi.
- **📦 Order Management:** Admin dapat melihat semua pesanan user, mengubah status order, dan mengelola pembayaran.
- **💰 Payment Methods Management:** Kelola metode pembayaran (Bank Transfer, E-wallet, dll) dengan info rekening, instruksi, dan atas nama.
- **⚙️ Settings Panel:** Pengaturan website dari admin panel.

### 🎨 UI/UX Modern
- **📱 Mobile-First Design:** Desain responsif dengan navigasi bottom sticky untuk mobile dan sidebar untuk desktop.
- **🌈 Glassmorphism & Shadows:** Efek kaca blur, gradient pink, card shadow yang modern dan estetik.
- **🎭 Smooth Animations:** Animasi hover, transition, dan scroll yang halus menggunakan AOS (Animate on Scroll).
- **🍪 Cookie Consent:** Pop-up cookie modern dengan localStorage.

### 🔧 Technical Features
- **🗄️ Database Structure:** Relasi tabel yang rapi (users, products, cart, orders, order_items, payment_methods).
- **🔒 Session Management:** Keamanan session untuk user dan admin.
- **📤 AJAX Operations:** Update cart, add to cart, dan operasi dinamis tanpa reload page.
- **🖼️ Image Upload Handler:** Upload gambar produk dengan nama file random untuk keamanan.

---

## 📸 Preview

| Halaman | Deskripsi |
| :--- | :--- |
| **Homepage & Products** | Grid produk skincare dengan filter kategori pop-up dan hero banner blur. |
| **Shopping Cart & Checkout** | Keranjang belanja dengan edit qty (+/-), form checkout lengkap, dan pilihan payment. |
| **Client Dashboard** | Dashboard modern dengan bottom nav (mobile) dan sidebar (desktop), statistik pesanan. |
| **Admin Panel** | Manajemen produk, order, dan payment methods lengkap. |

![Homepage](https://via.placeholder.com/800x400/FFD6E0/EC4899?text=🏠+Homepage+La+Belle+Peau)
![Checkout](https://via.placeholder.com/800x400/FFF0F3/F472B6?text=🛒+Checkout+Page)
![Dashboard Client](https://via.placeholder.com/800x400/FECDD3/BE185D?text=📊+Client+Dashboard)

---

## 🚀 Quick Start

### Prerequisites
- **PHP 7.4+** (Disarankan PHP 8.0+)
- **MySQL / MariaDB** (via XAMPP/Laragon/MAMP)
- **Git**

1. **Clone Repository**
