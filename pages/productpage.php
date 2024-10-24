<?php
include 'config.php'; // Koneksi ke database

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Pastikan ID valid
if ($id > 0) {
    // Query database untuk mengambil data berdasarkan ID
    $query = mysqli_query($conn, "SELECT * FROM tb_costume WHERE id = $id");

    if ($query) {
        $costume = mysqli_fetch_assoc($query);

        // Jika data ditemukan, tampilkan detailnya
        if ($costume) {
            // Ambil data gambar terkait
            $image_query = mysqli_query($conn, "SELECT * FROM images WHERE id = " . $costume['image_id']);
            $image = mysqli_fetch_assoc($image_query);

            // Ambil data ukuran terkait
            $size_query = mysqli_query($conn, "SELECT * FROM cos_sizes WHERE costume_id = $id");
            $sizes = [];
            while ($row = mysqli_fetch_assoc($size_query)) {
                $sizes[] = $row;
            }
        } else {
            echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>Terjadi kesalahan pada query.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID tidak valid.</div>";
    exit;
}
?>
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/detailspage.css">
<link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

<main class="container-fluid details-container row px-5">
    <section class="col-8">
        <h1 class="details-header">Details of <?= htmlspecialchars($costume['costume_name']); ?></h1>
        <img src=".<?= htmlspecialchars($image['file_path']); ?>" alt="Costume Image" class=" details-image">
        <p class="details-description">Description: <?= htmlspecialchars($costume['description']); ?></p>
        <h2 class="details-header">Sizes and Prices</h2>
        <ul class="details-sizes">
            <?php foreach ($sizes as $size): ?>
                <li>Size: <?= htmlspecialchars($size['size']); ?>, Price: <?= htmlspecialchars($size['price']); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="col-4">
        <a href="<?= $costume['id']; ?>/checkout" class="btn btn-primary">
            Pesan Sekarang
        </a>

    </section>

</main>