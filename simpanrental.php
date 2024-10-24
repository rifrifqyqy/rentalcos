<?php
include 'config.php'; // Ensure this file contains your database connection settings

// Handle file upload
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['gambar']['tmp_name'];
    $fileName = $_FILES['gambar']['name'];
    $fileSize = $_FILES['gambar']['size'];
    $fileType = $_FILES['gambar']['type'];
    $fileNameCmps = explode('.', $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Define allowed file extensions and max file size (e.g., 2MB)
    $allowedExtensions = array('jpg', 'jpeg', 'png');
    $maxFileSize = 2 * 1024 * 1024;

    if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize) {
        // Directory where the file will be saved
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Insert image metadata into the database
            $sql_image = "INSERT INTO images (file_name, file_path) VALUES (?, ?)";
            $stmt = $conn->prepare($sql_image);
            $stmt->bind_param("ss", $fileName, $dest_path);
            $stmt->execute();
            $image_id = $stmt->insert_id; // Get the ID of the newly inserted image
            $stmt->close();
        } else {
            die("There was an error uploading the file, please try again.");
        }
    } else {
        die("Invalid file extension or file size too large.");
    }
} else {
    die("No file uploaded or upload error.");
}

// Retrieve form data
$costume_name = $_POST['costume_name'];
$base_price = $_POST['base_price'];

// Prepare rental durations and prices
$rental_durations = $_POST['rental_duration'];
$rental_prices = $_POST['rental_price'];

// Insert costume into database
$sql_costume = "INSERT INTO tb_costume (costume_name, base_price, image_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_costume);
$stmt->bind_param("ssi", $costume_name, $base_price, $image_id);
$stmt->execute();
$costume_id = $stmt->insert_id; // Get the ID of the newly inserted costume
$stmt->close();

// Insert rental durations and prices
$sql_duration_price = "INSERT INTO rental_prices (costume_id, duration, price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_duration_price);

foreach ($rental_durations as $index => $duration) {
    $price = $rental_prices[$index];
    $stmt->bind_param("iis", $costume_id, $duration, $price);
    $stmt->execute();
}
$stmt->close();

// Handle sizes and their prices
$sizes = $_POST['size'];
$prices = $_POST['price'];

$sql_size_price = "INSERT INTO cos_sizes (costume_id, size, price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql_size_price);

foreach ($sizes as $index => $size) {
    $price = $prices[$index];
    $stmt->bind_param("ssi", $costume_id, $size, $price);
    $stmt->execute();
}
$stmt->close();

// Redirect to a confirmation page or admin dashboard
header("Location: index.php?status=success");
exit();
?>
