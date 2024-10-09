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

// Ambil data penjualan dari database dengan JOIN ke tabel produk dan pelanggan
$sql = "
    SELECT sales.sale_id, products.name AS product_name, customers.name AS customer_name, sales.sale_date, sales.quantity, sales.total_price 
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
            <h2 class="mb-2">Data Penjualan</h2>
            <p class="card-description">Home / <code>Data Penjualan</code></p>
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
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada penjualan ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('.table').DataTable();
});
</script>