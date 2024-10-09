<?php
include('src/config/koneksi.php');
$success_message = '';
$error_message = '';

// Ambil data pelanggan dari database
$sql = "SELECT customer_id, name, contact, address FROM customers";
$result = $conn->query($sql);

$conn->close();
?>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-2">Data Pelanggan</h2>
            <p class="card-description">Home / <code>Data Pelanggan</code></p>
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
                            <th>Nama Pelanggan</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
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
                                echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada pelanggan ditemukan</td></tr>";
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