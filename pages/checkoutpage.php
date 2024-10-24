<?php
include 'config.php'; // Koneksi ke database

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Pastikan ID valid
if ($id > 0) {
    // Query database untuk mengambil data berdasarkan ID
    $query = mysqli_query($conn, "SELECT * FROM tb_costume WHERE id = $id");

    // Cek apakah query berhasil
    if ($query) {
        $costume = mysqli_fetch_assoc($query);

        // Jika data ditemukan, tampilkan detailnya
        if ($costume) {
            $image_query = mysqli_query($conn, "SELECT * FROM images WHERE id = " . $costume['image_id']);
            $image = mysqli_fetch_assoc($image_query);
            // addon price query
            $addon_price_query = mysqli_query($conn, "SELECT * FROM rental_prices WHERE costume_id = $id");

            // Simpan data addon price dalam array dengan costume_id sebagai kunci
            $addon = [];
            while ($row = mysqli_fetch_assoc($addon_price_query)) {
                $addon[] = $row;
            }


            // Ambil data ukuran terkait
            $size_query = mysqli_query($conn, "SELECT * FROM cos_sizes WHERE costume_id = $id");
            $sizes = [];
            while ($row = mysqli_fetch_assoc($size_query)) {
                $sizes[] = $row;
            }
        } else {
            echo "Data tidak ditemukan.";
            exit;
        }
    } else {
        echo "Terjadi kesalahan pada query.";
        exit;
    }
} else {
    echo "ID tidak valid.";
    exit;
}
?>
<link rel="stylesheet" href="../../css/detailspage.css">
<link rel="stylesheet" href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

<div class="container">
    <h1>Checkout for <?= htmlspecialchars($costume['costume_name']); ?></h1>
    <img src="../.<?= htmlspecialchars($image['file_path']); ?>" class="img-checkout" alt="">

    <!-- Form Pemesanan -->
    <form action="/rentalcos/proses_checkout.php" method="POST">
        <input type="hidden" name="costume_id" value="<?= htmlspecialchars($costume['id']); ?>">


        <!-- Pilih Ukuran Kostum -->
        <div class="mb-3">
            <label for="size" class="form-label">Pilih Ukuran:</label>
            <select name="size" id="size" class="form-select" required>
                <option value="">Pilih ukuran</option>
                <?php foreach ($sizes as $size): ?>
                    <option value="<?= htmlspecialchars($size['price']); ?>">
                        <?= htmlspecialchars($size['size']); ?> (Rp. <?= number_format($size['price'], 0, ',', '.'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <!-- Pilih Durasi Rental -->
        <div class="mb-3">
            <label for="duration" class="form-label">Durasi Rental:</label>
            <div class="input-group">
                <button class="btn btn-outline-secondary" type="button" id="decrease-duration">-</button>
                <input type="number" name="duration" id="duration" class="form-control text-center" value="3" min="1" max="30" required>
                <button class="btn btn-outline-secondary" type="button" id="increase-duration">+</button>
            </div>
        </div>

        <!-- Menampilkan Biaya Rental Berdasarkan Durasi -->
        <div class="mb-3">
            <label for="total-cost" class="form-label">Biaya Durasi Rental:</label>
            <p id="total-cost">Rp. 0</p>
        </div>

        <!-- Input Nama -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Input Nomor Telepon -->
        <div class="mb-3">
            <label for="phone" class="form-label">Nomor Telepon:</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <!-- Input Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
    </form>
    <!-- Total Biaya -->
    <div class="mt-4">
        <h3>Total Biaya: <span id="final-cost">Rp. 0</span></h3>
    </div>
</div>

<script>
    let addonData = <?= json_encode($addon); ?>; // Mengambil data addon dari PHP
    let durationInput = document.getElementById('duration');
    let sizeSelect = document.getElementById('size');
    let totalCostElement = document.getElementById('total-cost');
    let finalCostElement = document.getElementById('final-cost');

    function calculateCost() {
        let selectedDuration = parseInt(durationInput.value);
        let selectedSizePrice = parseInt(sizeSelect.value);
        let totalCost = 0;

        // Loop melalui setiap addon untuk menghitung biaya total berdasarkan durasi
        addonData.forEach(function(addon) {
            let addonDuration = parseInt(addon.duration);
            let addonPrice = parseInt(addon.price);

            // Hitung total biaya durasi rental
            let multiplier = Math.floor(selectedDuration / addonDuration);
            totalCost += multiplier * addonPrice;
        });

        // Hitung total biaya final dengan menambahkan harga ukuran
        let finalCost = totalCost + selectedSizePrice;

        totalCostElement.innerText = `Rp. ${totalCost.toLocaleString('id-ID')}`;
        finalCostElement.innerText = `Rp. ${finalCost.toLocaleString('id-ID')}`;
    }

    document.getElementById('decrease-duration').addEventListener('click', function() {
        if (parseInt(durationInput.value) > parseInt(durationInput.min)) {
            durationInput.value = parseInt(durationInput.value) - 1;
            calculateCost(); // Menghitung ulang biaya
        }
    });

    document.getElementById('increase-duration').addEventListener('click', function() {
        if (parseInt(durationInput.value) < parseInt(durationInput.max)) {
            durationInput.value = parseInt(durationInput.value) + 1;
            calculateCost(); // Menghitung ulang biaya
        }
    });

    sizeSelect.addEventListener('change', calculateCost);

    // Panggil calculateCost saat halaman dimuat
    window.onload = calculateCost;
</script>