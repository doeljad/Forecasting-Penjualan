<?php
include('src/config/koneksi.php');
$success_message = '';
$error_message = '';

// // Ambil data nama supplier dari database
// $sql_supplier = "SELECT supplier_id, nama_supplier FROM supplier";
// $result_supplier = $conn->query($sql_supplier);

// Ambil data produk dari database
$sql = "SELECT product_id, name, category, price, stock_quantity FROM products";
$result = $conn->query($sql);

$conn->close();
?>

<!-- Bagian HTML dan JavaScript di bawah ini juga telah disesuaikan untuk merefleksikan perubahan pada tabel dan kolom database. -->
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-2">Daftar Produk</h2>
            <p class="card-description"> Home /<code>Produk</code></p>
            <!-- Pesan sukses dan error menggunakan SweetAlert -->
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
            <!-- Tabel untuk menampilkan data produk -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> No. </th>
                            <th> Nama Produk </th>
                            <th> Kategori </th>
                            <th> Harga </th>
                            <th> Jumlah Stok </th>

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
 
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Tidak ada produk ditemukan</td></tr>";
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