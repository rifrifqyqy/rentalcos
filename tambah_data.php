<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Rental Costume</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar px-5 py-3 border-bottom">
        <p>RentalCos Admin</p>
        <ul class="d-flex gap-2 bg-danger">
            <li>
                <a href="#">Beranda</a>
            </li>
            <li>
                <a href="#">Produk</a>
            </li>
            <li>
                <a href="#">Kontak</a>
            </li>
        </ul>
    </nav>
    <!-- end navbar -->

    <form action="simpanrental.php" method="POST" enctype="multipart/form-data">
        <!-- Input costume name -->
        <div class="mb-3 row">
            <label for="costume_name" class="col-sm-2 col-form-label">Costume Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="costume_name" name="costume_name" placeholder="Enter costume name" required>
            </div>
        </div>

        <!-- Input base price -->
        <div class="mb-3 row">
            <label for="base_price" class="col-sm-2 col-form-label">Base Price</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="base_price" name="base_price" placeholder="Enter base price">
            </div>
        </div>

        <!-- Input for rental duration with price -->
        <div class="mb-3 row">
            <label for="rental_duration" class="col-sm-2 col-form-label">Rental Duration (days)</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" id="rental_duration" name="rental_duration[]" placeholder="Enter duration in days" min="1" required>
            </div>
            <label for="rental_price" class="col-sm-2 col-form-label">Price for Duration</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="rental_price" name="rental_price[]" placeholder="Enter price for this duration" required>
            </div>
        </div>
        <div id="additional-durations"></div>
        <div class="mb-3 row">
            <button type="button" id="add-duration" class="btn btn-primary">Add Rental Duration</button>
        </div>

        <!-- Existing fields for image, sizes, and prices -->
        <!-- Input gambar -->
        <div class="d-flex gap-4 flex-column">
            <input type="file" id="gambar" name="gambar" accept="image/*" required>
            <!-- Menampilkan gambar yang diinput dengan php -->
            <div id="img-preview"></div>
        </div>

        <div class="mb-3 row">
            <label for="size" class="col-sm-2 col-form-label">Size</label>
            <div class="col-sm-10">
                <select class="form-select" id="size" name="size[]">
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="price" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="price" name="price[]" placeholder="Enter price">
            </div>
        </div>
        <div id="additional-sizes"></div>
        <div class="mb-3 row">
            <button type="button" id="add-size" class="btn btn-primary">Add Size</button>
        </div>

        <!-- Submit button -->
        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>

    <script>
        // Preview image script
        document.getElementById('gambar').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = document.getElementById('img-preview');
                    imgPreview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width: 200px; height: 200px; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Add additional size and price inputs
        document.getElementById('add-size').addEventListener('click', function() {
            const sizeWrapper = document.createElement('div');
            sizeWrapper.className = 'mb-3 row';

            const sizeLabel = document.createElement('label');
            sizeLabel.className = 'col-sm-2 col-form-label';
            sizeLabel.innerText = 'Size';
            sizeWrapper.appendChild(sizeLabel);

            const sizeDiv = document.createElement('div');
            sizeDiv.className = 'col-sm-10';
            sizeWrapper.appendChild(sizeDiv);

            const sizeSelect = document.createElement('select');
            sizeSelect.className = 'form-select';
            sizeSelect.name = 'size[]';
            sizeSelect.innerHTML = `
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            `;
            sizeDiv.appendChild(sizeSelect);

            const priceWrapper = document.createElement('div');
            priceWrapper.className = 'mb-3 row';

            const priceLabel = document.createElement('label');
            priceLabel.className = 'col-sm-2 col-form-label';
            priceLabel.innerText = 'Price';
            priceWrapper.appendChild(priceLabel);

            const priceDiv = document.createElement('div');
            priceDiv.className = 'col-sm-10';
            priceWrapper.appendChild(priceDiv);

            const priceInput = document.createElement('input');
            priceInput.type = 'text';
            priceInput.className = 'form-control';
            priceInput.name = 'price[]';
            priceInput.placeholder = 'Enter price';
            priceDiv.appendChild(priceInput);

            document.getElementById('additional-sizes').appendChild(sizeWrapper);
            document.getElementById('additional-sizes').appendChild(priceWrapper);
        });

        // Add additional rental duration and price inputs
        document.getElementById('add-duration').addEventListener('click', function() {
            const durationWrapper = document.createElement('div');
            durationWrapper.className = 'mb-3 row';

            const durationLabel = document.createElement('label');
            durationLabel.className = 'col-sm-2 col-form-label';
            durationLabel.innerText = 'Rental Duration (days)';
            durationWrapper.appendChild(durationLabel);

            const durationDiv = document.createElement('div');
            durationDiv.className = 'col-sm-4';
            durationWrapper.appendChild(durationDiv);

            const durationInput = document.createElement('input');
            durationInput.type = 'number';
            durationInput.className = 'form-control';
            durationInput.name = 'rental_duration[]';
            durationInput.placeholder = 'Enter duration in days';
            durationInput.min = '1';
            durationWrapper.appendChild(durationInput);

            const priceLabel = document.createElement('label');
            priceLabel.className = 'col-sm-2 col-form-label';
            priceLabel.innerText = 'Price for Duration';
            durationWrapper.appendChild(priceLabel);

            const priceDiv = document.createElement('div');
            priceDiv.className = 'col-sm-4';
            durationWrapper.appendChild(priceDiv);

            const priceInput = document.createElement('input');
            priceInput.type = 'text';
            priceInput.className = 'form-control';
            priceInput.name = 'rental_price[]';
            priceInput.placeholder = 'Enter price for this duration';
            durationWrapper.appendChild(priceInput);

            document.getElementById('additional-durations').appendChild(durationWrapper);
        });
    </script>
</body>

</html>
