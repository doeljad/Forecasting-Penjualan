<?php
include('src/config/koneksi.php');

// Ambil data supplier dari database
$sql = "SELECT supplier_id, nama_supplier, alamat, telepon, email FROM supplier";
$result = $conn->query($sql);

$conn->close();
?>

<!-- Bagian HTML dan JavaScript di bawah ini juga harus disesuaikan untuk merefleksikan perubahan pada tabel dan kolom database. -->
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-2">Daftar Supplier</h2>
            <p class="card-description"> Home /<code>Supplier</code></p>
            <!-- Tabel untuk menampilkan data supplier -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th> No. </th>
                            <th> Nama Supplier </th>
                            <th> Alamat </th>
                            <th> Telepon </th>
                            <th> Email </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($row["nama_supplier"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["alamat"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["telepon"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Tidak ada supplier ditemukan</td></tr>";
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