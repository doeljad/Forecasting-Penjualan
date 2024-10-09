<?php
include('src/config/koneksi.php');
$success_message = '';
$error_message = '';

// Mengambil data produk dari database
$sql_products = "SELECT product_id, name, price, stock_quantity FROM products";
$result_products = $conn->query($sql_products);

// Mengambil data pelanggan dari database
$sql_customers = "SELECT customer_id, name FROM customers";
$result_customers = $conn->query($sql_customers);

// Tambah atau edit penjualan jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && ($_POST['action'] == 'add' || $_POST['action'] == 'edit')) {
        $product_id = $_POST['product_id'];
        $customer_id = $_POST['customer_id'];
        $sale_date = $_POST['sale_date'];
        $quantity = $_POST['quantity'];
        $sale_id = $_POST['sale_id'];
        $action = $_POST['action'];

        // Mengambil harga dan stok produk
        $sql = "SELECT price, stock_quantity FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $price = $product['price'];
        $stock_quantity = $product['stock_quantity'];
        $stmt->close();

        if ($quantity > $stock_quantity) {
            $error_message = "Jumlah melebihi stok yang tersedia.";
        } else {
            $total_price = $price * $quantity;

            if ($action == 'add') {
                $sql = "INSERT INTO sales (product_id, customer_id, sale_date, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisid", $product_id, $customer_id, $sale_date, $quantity, $total_price);
            } else {
                $sql = "UPDATE sales SET product_id=?, customer_id=?, sale_date=?, quantity=?, total_price=? WHERE sale_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisidi", $product_id, $customer_id, $sale_date, $quantity, $total_price, $sale_id);
            }

            if ($stmt->execute()) {
                $success_message = $action == 'add' ? "Penjualan baru berhasil ditambahkan!" : "Penjualan berhasil diperbarui!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // Hapus penjualan
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $sale_id = $_POST['sale_id'];

        $sql = "DELETE FROM sales WHERE sale_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sale_id);

        if ($stmt->execute()) {
            $success_message = "Penjualan berhasil dihapus!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Ambil data penjualan dari database dengan JOIN ke tabel produk dan pelanggan
$sql = "
    SELECT sales.sale_id, sales.product_id, sales.customer_id, products.name AS product_name, customers.name AS customer_name, sales.sale_date, sales.quantity, sales.total_price 
    FROM sales
    JOIN products ON sales.product_id = products.product_id
    JOIN customers ON sales.customer_id = customers.customer_id
";
$result = $conn->query($sql);

$conn->close();
?>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-success float-end" data-toggle="modal" data-target="#addSaleModal">
                Tambah Penjualan
            </button>
            <h2 class="mb-2">Data Penjualan</h2>
            <p class="card-description"> Home /<code>Data Penjualan</code></p>
            <?php if ($success_message) : ?>
            <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $success_message; ?>'
            });
            </script>
            <?php endif; ?>
            <?php if ($error_message) : ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error_message; ?>'
            });
            </script>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Produk</th>
                            <th>Pelanggan</th>
                            <th>Tanggal Penjualan</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["customer_name"]) . "</td>";
                                echo "<td>" . $row["sale_date"] . "</td>";
                                echo "<td>" . $row["quantity"] . "</td>";
                                echo "<td>" . $row["total_price"] . "</td>";
                                echo "<td>
                            <button class='btn btn-warning btn-sm' onclick='editSale(" . json_encode($row) . ")'>Edit</button>
                            <button class='btn btn-danger btn-sm' onclick='deleteSale(" . $row["sale_id"] . ")'>Delete</button>
                            </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Tidak ada penjualan ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Penjualan -->
<div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSaleModalLabel">Tambah Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="action" value="add" id="modalAction">
                    <input type="hidden" name="sale_id" id="saleId">
                    <div class="form-group">
                        <label for="product_id">ID Produk</label>
                        <select class="form-control" id="product_id" name="product_id" onchange="updatePriceAndStock()"
                            required>
                            <option value="">Pilih Produk</option>
                            <?php
                            if ($result_products->num_rows > 0) {
                                while ($row = $result_products->fetch_assoc()) {
                                    echo "<option value='" . $row["product_id"] . "' data-price='" . $row["price"] . "' data-stock='" . $row["stock_quantity"] . "'>" . $row["name"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="customer_id">ID Pelanggan</label>
                        <select class="form-control" id="customer_id" name="customer_id" required>
                            <option value="">Pilih Pelanggan</option>
                            <?php
                            if ($result_customers->num_rows > 0) {
                                while ($row = $result_customers->fetch_assoc()) {
                                    echo "<option value='" . $row["customer_id"] . "'>" . $row["name"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sale_date">Tanggal Penjualan</label>
                        <input type="date" class="form-control" id="sale_date" name="sale_date" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Jumlah</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"
                            oninput="calculateTotalPrice()" required>
                    </div>
                    <div class="form-group">
                        <label for="total_price">Total Harga</label>
                        <input type="number" class="form-control" id="total_price" name="total_price" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function calculateTotalPrice() {
    var quantity = document.getElementById("quantity").value;
    var productSelect = document.getElementById("product_id");
    var price = productSelect.options[productSelect.selectedIndex].getAttribute("data-price");
    var totalPrice = quantity * price;
    document.getElementById("total_price").value = totalPrice;
}

function updatePriceAndStock() {
    var productSelect = document.getElementById("product_id");
    var price = productSelect.options[productSelect.selectedIndex].getAttribute("data-price");
    var stock = productSelect.options[productSelect.selectedIndex].getAttribute("data-stock");
    calculateTotalPrice();
}

function editSale(sale) {
    document.getElementById("modalAction").value = "edit";
    document.getElementById("addSaleModalLabel").innerText = "Edit Penjualan";
    document.getElementById("saleId").value = sale.sale_id;
    document.getElementById("product_id").value = sale.product_id;
    document.getElementById("customer_id").value = sale.customer_id;
    document.getElementById("sale_date").value = sale.sale_date;
    document.getElementById("quantity").value = sale.quantity;
    document.getElementById("total_price").value = sale.total_price;
    $('#addSaleModal').modal('show');
}

function deleteSale(saleId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim permintaan penghapusan ke server
            $.post("", {
                action: "delete",
                sale_id: saleId
            }, function(data) {
                location.reload();
            });
        }
    })
}

$('#addSaleModal').on('hidden.bs.modal', function(e) {
    document.getElementById("modalAction").value = "add";
    document.getElementById("addSaleModalLabel").innerText = "Tambah Penjualan";
    document.getElementById("saleId").value = "";
    document.getElementById("product_id").value = "";
    document.getElementById("customer_id").value = "";
    document.getElementById("sale_date").value = "";
    document.getElementById("quantity").value = "";
    document.getElementById("total_price").value = "";
});
</script>