<?php
session_start();
require_once '../includes/db.php';

// Cek login admin
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Utility: random string
function random_filename($ext) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $rand = '';
    for ($i = 0; $i < 12; $i++) $rand .= $chars[rand(0, strlen($chars)-1)];
    return $rand . '.' . $ext;
}

// Handle tambah produk
if(isset($_POST['add'])){
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $cat = mysqli_real_escape_string($db, $_POST['category']);
    $price = floatval($_POST['price']);
    $desc = mysqli_real_escape_string($db, $_POST['description']);
    $stock = intval($_POST['stock']);
    $img = '';
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imgname = random_filename($ext);
        $target = "../assets/img/product/".$imgname;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $img = $imgname;
    }
    mysqli_query($db, "INSERT INTO products (name, category, price, description, image, stock) VALUES ('$name','$cat',$price,'$desc','$img',$stock)");
}

// Handle delete produk
if(isset($_GET['del'])){
    $id = intval($_GET['del']);
    $r = mysqli_fetch_assoc(mysqli_query($db,"SELECT image FROM products WHERE id=$id"));
    if($r && $r['image'] && file_exists("../assets/img/product/".$r['image'])){
        unlink("../assets/img/product/".$r['image']);
    }
    mysqli_query($db,"DELETE FROM products WHERE id=$id");
    header('Location: manage_products.php');
    exit();
}

// Handle edit produk
if(isset($_POST['edit'])){
    $id = intval($_POST['prod_id']);
    $name = mysqli_real_escape_string($db, $_POST['edit_name']);
    $cat = mysqli_real_escape_string($db, $_POST['edit_category']);
    $price = floatval($_POST['edit_price']);
    $desc = mysqli_real_escape_string($db, $_POST['edit_description']);
    $stock = intval($_POST['edit_stock']);
    $qimg = '';
    if(isset($_FILES['edit_image']) && $_FILES['edit_image']['size'] > 0){
        $ext = strtolower(pathinfo($_FILES['edit_image']['name'], PATHINFO_EXTENSION));
        $imgname = random_filename($ext);
        move_uploaded_file($_FILES['edit_image']['tmp_name'], "../assets/img/product/".$imgname);
        $qimg = ", image='$imgname'";
    }
    mysqli_query($db, "UPDATE products SET name='$name', category='$cat', price=$price, description='$desc', stock=$stock $qimg WHERE id=$id");
    header('Location: manage_products.php');
    exit();
}

// Data produk
$res_produk = mysqli_query($db, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk | Admin La Belle Peau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
    <style>
        body {background:linear-gradient(120deg,#FFD6E0,#FFF0F3);font-family:'Poppins',Arial,sans-serif;overflow-x:hidden;}
        .sidebar-item.active{background-color:#ec4899!important;color:#fff!important;}
        .modal-bg {background:rgba(0,0,0,0.2);}
        .table-fixed {table-layout:fixed;}
    </style>
</head>
<body>
    <div class="flex flex-col md:flex-row">

        <!-- SIDEBAR (copy dari dashboard.php) -->
        <aside class="w-full md:w-64 bg-white/50 backdrop-blur-xl shadow-lg md:min-h-screen">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-8">
                    <img src="../assets/img/logo.png" alt="Logo" class="w-12 h-12 rounded-xl shadow">
                    <div>
                        <h1 class="text-pink-500 font-bold text-lg">La Belle Peau</h1>
                        <p class="text-pink-400 text-xs">Admin Panel</p>
                    </div>
                </div>
                <nav class="space-y-3">
                    <a href="dashboard.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path></svg>
                        <span class="hidden md:block">Dashboard</span>
                    </a>
                    <a href="manage_products.php" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path></svg>
                        <span class="hidden md:block">Produk</span>
                    </a>
                    <a href="manage_orders.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span class="hidden md:block">Pesanan</span>
                    </a>
                    <a href="settings.php" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-pink-500 hover:bg-pink-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="hidden md:block">Settings</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 py-8 px-3 md:px-10">
            <!-- FORM TAMBAH PRODUK -->
            <section class="mb-10 bg-white/60 backdrop-blur-xl rounded-2xl shadow-xl p-4 md:p-8">
                <h2 class="text-pink-600 font-bold text-xl mb-6">Tambah Produk Baru</h2>
                <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div>
                        <label class="block mb-2 text-pink-500 font-semibold">Nama Produk</label>
                        <input type="text" name="name" required class="w-full py-2 px-4 rounded-xl bg-pink-100/60 border border-pink-300 mb-3"/>
                        <label class="block mb-2 text-pink-500 font-semibold">Kategori</label>
                        <select name="category" required class="w-full py-2 px-4 rounded-xl bg-pink-100/60 border border-pink-300 mb-3">
                            <option value="">Pilih kategori</option>
                            <option>Serum</option>
                            <option>Toner</option>
                            <option>Cleanser</option>
                            <option>Moisturizer</option>
                            <option>Sunscreen</option>
                            <option>Lip Balm</option>
                            <option>Mask</option>
                            <option>Micellar</option>
                        </select>
                        <label class="block mb-2 text-pink-500 font-semibold">Harga (Rp)</label>
                        <input type="number" name="price" required min="0" class="w-full py-2 px-4 rounded-xl bg-pink-100/60 border border-pink-300 mb-3"/>
                        <label class="block mb-2 text-pink-500 font-semibold">Stok</label>
                        <input type="number" name="stock" required min="0" class="w-full py-2 px-4 rounded-xl bg-pink-100/60 border border-pink-300 mb-3"/>
                    </div>
                    <div>
                        <label class="block mb-2 text-pink-500 font-semibold">Deskripsi</label>
                        <textarea name="description" required class="w-full py-2 px-4 rounded-xl bg-pink-100/60 border border-pink-300 mb-3"></textarea>
                        <label class="block mb-2 text-pink-500 font-semibold">Foto Produk</label>
                        <input type="file" name="image" accept="image/*" class="w-full py-2 px-4 bg-white border border-pink-300 rounded-xl mb-3"/>
                        <button type="submit" name="add" class="w-full py-2 bg-pink-400 text-white font-bold rounded-xl hover:bg-pink-600 transition mt-5">Tambah Produk</button>
                    </div>
                </form>
            </section>

            <!-- TABEL PRODUK -->
            <section class="mb-12 bg-white/60 backdrop-blur-xl rounded-2xl shadow-xl p-3 md:p-7">
                <h2 class="text-pink-600 font-bold text-xl mb-6">Daftar Produk</h2>
                <div class="overflow-auto">
                    <table class="min-w-[650px] w-full table-fixed text-xs md:text-base">
                        <thead>
                            <tr class="bg-pink-100/80 font-bold text-pink-500">
                                <th class="p-2 w-28">Foto</th>
                                <th class="p-2 w-40">Nama</th>
                                <th class="p-2 w-32">Kategori</th>
                                <th class="p-2 w-20">Harga</th>
                                <th class="p-2 w-16">Stok</th>
                                <th class="p-2 w-48">Deskripsi</th>
                                <th class="p-2 w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($p = mysqli_fetch_assoc($res_produk)): ?>
                            <tr class="border-b border-pink-100 text-gray-700 bg-white hover:bg-pink-100/80 transition">
                                <td class="p-2">
                                    <?php if($p['image']): ?>
                                        <img src="../assets/img/product/<?= htmlspecialchars($p['image']) ?>"
                                             class="w-14 h-14 rounded-xl object-cover bg-pink-300 border" alt="<?= htmlspecialchars($p['name']) ?>">
                                    <?php endif ?>
                                </td>
                                <td class="p-2 font-semibold text-pink-600"><?= htmlspecialchars($p['name']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($p['category']) ?></td>
                                <td class="p-2 text-pink-600 font-bold">Rp<?= number_format($p['price'],0,',','.') ?></td>
                                <td class="p-2"><?= (int)$p['stock'] ?></td>
                                <td class="p-2"><?= htmlspecialchars($p['description']) ?></td>
                                <td class="p-2 flex flex-col gap-2">
                                    <button onclick="openEditModal(<?= $p['id'] ?>)" class="bg-pink-500 text-white rounded-lg px-2 py-1 font-bold hover:bg-pink-700">Edit</button>
                                    <a href="?del=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin hapus produk?')" class="bg-pink-300 text-white rounded-lg px-2 py-1 font-bold hover:bg-pink-500">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <!-- MODAL EDIT PRODUK (sama logic upload acak) -->
        <div id="editModal" class="fixed inset-0 modal-bg flex items-center justify-center z-50" style="display:none;">
            <div class="bg-white/90 rounded-2xl shadow-2xl p-6 w-full max-w-md">
                <h3 class="text-pink-600 font-bold text-xl mb-4">Edit Produk</h3>
                <form method="post" enctype="multipart/form-data" id="editForm">
                    <input type="hidden" name="prod_id" id="edit_id"/>
                    <label class="block mb-1 text-pink-500 font-semibold">Nama Produk</label>
                    <input type="text" name="edit_name" id="edit_name" required class="w-full py-2 px-3 rounded-xl bg-pink-100/60 border border-pink-300 mb-2"/>
                    <label class="block mb-1 text-pink-500 font-semibold">Kategori</label>
                    <select name="edit_category" id="edit_category" required class="w-full py-2 px-3 rounded-xl bg-pink-100/60 border border-pink-300 mb-2">
                        <option>Serum</option>
                        <option>Toner</option>
                        <option>Cleanser</option>
                        <option>Moisturizer</option>
                        <option>Sunscreen</option>
                        <option>Lip Balm</option>
                        <option>Mask</option>
                        <option>Micellar</option>
                    </select>
                    <label class="block mb-1 text-pink-500 font-semibold">Harga (Rp)</label>
                    <input type="number" name="edit_price" id="edit_price" required min="0" class="w-full py-2 px-3 rounded-xl bg-pink-100/60 border border-pink-300 mb-2"/>
                    <label class="block mb-1 text-pink-500 font-semibold">Stok</label>
                    <input type="number" name="edit_stock" id="edit_stock" required min="0" class="w-full py-2 px-3 rounded-xl bg-pink-100/60 border border-pink-300 mb-2"/>
                    <label class="block mb-1 text-pink-500 font-semibold">Deskripsi</label>
                    <textarea name="edit_description" id="edit_description" required class="w-full py-2 px-3 rounded-xl bg-pink-100/60 border border-pink-300 mb-2"></textarea>
                    <label class="block mb-1 text-pink-500 font-semibold">Ganti Foto</label>
                    <input type="file" name="edit_image" accept="image/*" class="w-full py-2 px-3 bg-white border border-pink-300 rounded-xl mb-3"/>
                    <div class="flex gap-3 mt-3">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-xl">Batal</button>
                        <button type="submit" name="edit" class="flex-1 px-4 py-2 bg-pink-400 text-white font-bold rounded-xl hover:bg-pink-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
    // Modal Edit Produk
    function openEditModal(id){
        const row = document.querySelector('a[href="?del='+id+'"]').closest('tr');
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = row.children[1].innerText.trim();
        document.getElementById('edit_category').value = row.children[2].innerText.trim();
        document.getElementById('edit_price').value = row.children[3].innerText.replace('Rp','').replace(/\./g,'').replace(',','').trim();
        document.getElementById('edit_stock').value = row.children[4].innerText.trim();
        document.getElementById('edit_description').value = row.children[5].innerText.trim();
        document.getElementById('editModal').style.display='flex';
        document.body.style.overflow='hidden';
    }
    function closeEditModal(){
        document.getElementById('editModal').style.display='none';
        document.body.style.overflow='';
    }
    </script>
</body>
</html>
