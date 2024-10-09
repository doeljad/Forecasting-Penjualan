<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <!-- Tambahkan link ke CSS DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.css">
</head>

<body>
    <?php
    // Pastikan untuk menyertakan koneksi.php dengan benar di sini
    include('src/config/koneksi.php');
    $success_message = '';
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            // Delete existing product
            $product_id = $_POST['product_id'];

            $sql = "DELETE FROM products WHERE product_id='$product_id'";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Produk berhasil dihapus";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
            // Update existing product
            $product_id = $_POST['product_id'];
            $name = $_POST['name'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            $sql = "UPDATE products SET name='$name', category='$category', price='$price', stock_quantity='$stock_quantity' WHERE product_id='$product_id'";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Produk berhasil diperbarui";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Add new product
            $name = $_POST['name'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            $sql = "INSERT INTO products (name, category, price, stock_quantity) VALUES ('$name', '$category', '$price', '$stock_quantity')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Produk berhasil ditambahkan";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Ambil data produk dari database
    $sql = "SELECT product_id, name, category, price, stock_quantity FROM products";
    $result = $conn->query($sql);
    ?>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-success float-end" data-toggle="modal"
                    data-target="#addProductModal">
                    Tambah Produk
                </button>
                <h2 class="mb-2">Daftar Produk</h2>
                <p class="card-description"> Home /<code>Produk</code></p>
                <!-- Tabel untuk menampilkan data produk -->
                <div class="table-responsive">
                    <table class="table table-striped" id="productTable">
                        <thead>
                            <tr>
                                <th> No. </th>
                                <th> Nama Produk </th>
                                <th> Kategori </th>
                                <th> Harga </th>
                                <th> Jumlah Stok </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["category"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["stock_quantity"]) . "</td>";
                                    echo "<td>
                                            <button class='btn btn-warning btn-sm' onclick='editProduct(" . json_encode($row) . ")'>Edit</button>
                                            <button class='btn btn-danger btn-sm' onclick='deleteProduct(" . $row["product_id"] . ")'>Delete</button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Tidak ada produk ditemukan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Form untuk menghapus produk -->
                <form id="deleteProductForm" action="" method="post" style="display: none;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="product_id" id="deleteProductId">
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah Produk -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addProductForm" action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Kategori</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity">Jumlah Stok</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });

    function editProduct(product) {
        $('#addProductModalLabel').text('Edit Produk');
        $('#product_id').val(product.product_id);
        $('#name').val(product.name);
        $('#category').val(product.category);
        $('#price').val(product.price);
        $('#stock_quantity').val(product.stock_quantity);
        $('#addProductModal').modal('show');
    }

    function deleteProduct(productId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteProductId').val(productId);
                $('#deleteProductForm').submit();
            }
        });
    }

    $('#addProductModal').on('hidden.bs.modal', function() {
        $('#addProductModalLabel').text('Tambah Produk');
        $('#product_id').val('');
        $('#name').val('');
        $('#category').val('');
        $('#price').val('');
        $('#stock_quantity').val('');
    });
    </script>
</body>

</html>