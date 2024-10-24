<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="css/card.css">
<!-- content -->
<main>
    HOMEPAGE
    <section>
        <?php
        include 'config.php';

        // Ambil data dari tabel
        $costume_query = mysqli_query($conn, "SELECT * FROM tb_costume");
        $image_query = mysqli_query($conn, "SELECT * FROM images");
        $size_query = mysqli_query($conn, "SELECT * FROM cos_sizes");
        $addon_price_query = mysqli_query($conn, "SELECT * FROM rental_prices");

        // Simpan data gambar dalam array dengan ID sebagai kunci
        $images = [];
        while ($row = mysqli_fetch_assoc($image_query)) {
            $images[$row['id']] = $row;
        }

        // Simpan data ukuran dalam array dengan costume_id sebagai kunci
        $sizes = [];
        while ($row = mysqli_fetch_assoc($size_query)) {
            $sizes[$row['costume_id']][] = $row; // Mengelompokkan ukuran berdasarkan costume_id
        }

        // Simpan data addon price dalam array dengan costume_id sebagai kunci
        $addon = [];
        while ($row = mysqli_fetch_assoc($addon_price_query)) {
            $addon[$row['costume_id']][] = $row; // Mengelompokkan addon price berdasarkan costume_id
        }

        // Ambil data kostum dan gabungkan dengan data gambar dan ukuran
        $costumes = [];
        while ($cos = mysqli_fetch_assoc($costume_query)) {
            $cos['image'] = isset($images[$cos['image_id']]) ? $images[$cos['image_id']] : null;
            $cos['sizes'] = isset($sizes[$cos['id']]) ? $sizes[$cos['id']] : [];
            $cos['addon'] = isset($addon[$cos['id']]) ? $addon[$cos['id']] : [];
            $costumes[] = $cos;
        }
        //fungsi mencari harga terendah
        function minPrice($data)
        {
            $min = $data[0]['price'];
            foreach ($data as $row) {
                if ($row['price'] < $min) {
                    $min = $row['price'];
                }
            }
            return $min;
        }
        // fungsi mencari harga tertnggi
        function maxPrice($data)
        {
            $max = $data[0]['price'];
            foreach ($data as $row) {
                if ($row['price'] > $max) {
                    $max = $row['price'];
                }
            }
            return $max;
        }
        ?>
        <!-- Loop data -->
        <div class="container-fluid mx-5">
            <div class="row">
                <?php foreach ($costumes as $cos): ?>
                    <div class="col-md-6 col-lg-4 col-xl-2 mb-4 ">
                        <div class="card h-100 rounded-0">
                            <img src="<?= htmlspecialchars($cos['image']['file_path']); ?>" alt="">
                            <p><?= htmlspecialchars($cos['costume_name']); ?></p>
                            <p><?= minPrice($cos['sizes']) ?></p>
                            <p><?= maxPrice($cos['sizes']) ?></p>
                            <ul>
                                <?php foreach ($cos['sizes'] as $size): ?>
                                    <li>Size: <?= htmlspecialchars($size['size']); ?>, Price: <?= htmlspecialchars($size['price']); ?></li>
                                <?php endforeach; ?>
                                <?php foreach ($cos['addon'] as $addon): ?>
                                    <p><?= htmlspecialchars($addon['duration']) ?></p>
                                    <p><?= htmlspecialchars($addon['price']) ?></p>
                                <?php endforeach; ?>
                            </ul>
                            <a href="costume/<?= $cos['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </section>
</main>