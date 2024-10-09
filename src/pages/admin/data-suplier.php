<?php
include('src/config/koneksi.php');
$success_message = '';
$error_message = '';

// Tambah supplier baru jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $nama_supplier = $_POST['nama_supplier'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        $email = $_POST['email'];

        $sql = "INSERT INTO supplier (nama_supplier, alamat, telepon, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nama_supplier, $alamat, $telepon, $email);

        if ($stmt->execute()) {
            $success_message = "Supplier baru berhasil ditambahkan!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    // Edit supplier
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $supplier_id = $_POST['supplier_id'];
        $nama_supplier = $_POST['nama_supplier'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];
        $email = $_POST['email'];

        $sql = "UPDATE supplier SET nama_supplier=?, alamat=?, telepon=?, email=? WHERE supplier_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama_supplier, $alamat, $telepon, $email, $supplier_id);

        if ($stmt->execute()) {
            $success_message = "Supplier berhasil diperbarui!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $stmt->error;
        }
    }

    // Hapus supplier
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $supplier_id = $_POST['supplier_id'];

        $sql = "DELETE FROM supplier WHERE supplier_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $supplier_id);

        if ($stmt->execute()) {
            $success_message = "Supplier berhasil dihapus!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $stmt->error;
        }
    }
}

// Ambil data supplier dari database
$sql = "SELECT supplier_id, nama_supplier, alamat, telepon, email FROM supplier";
$result = $conn->query($sql);

$conn->close();
?>

<!-- Bagian HTML dan JavaScript di bawah ini juga harus disesuaikan untuk merefleksikan perubahan pada tabel dan kolom database. -->
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-success float-end" onclick="showAddSupplierModal()">
                Tambah Supplier
            </button>
            <h2 class="mb-2">Daftar Supplier</h2>
            <p class="card-description"> Home /<code>Supplier</code></p>
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
                                echo "<td>" . $row["nama_supplier"] . "</td>";
                                echo "<td>" . $row["alamat"] . "</td>";
                                echo "<td>" . $row["telepon"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>
                                    <button class='btn btn-warning btn-sm' onclick='showEditSupplierModal(" . json_encode($row) . ")'>Edit</button>
                                    <button class='btn btn-danger btn-sm' onclick='deleteSupplier(" . $row["supplier_id"] . ")'>Delete</button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Tidak ada supplier ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Supplier -->
<div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel">Tambah Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" name="action" value="add" id="modalAction">
                    <input type="hidden" name="supplier_id" id="supplierId">
                    <div class="form-group">
                        <label for="nama_supplier">Nama Supplier</label>
                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                    </div>
                    <!-- continued -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="deleteSupplierForm" action="" method="post" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="supplier_id" id="deleteSupplierId">
</form>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('.table').DataTable();
});
script >
    script >
    function showAddSupplierModal() {
        $('#supplierModalLabel').text('Tambah Supplier');
        $('#modalAction').val('add');
        $('#supplierId').val('');
        $('#nama_supplier').val('');
        $('#alamat').val('');
        $('#telepon').val('');
        $('#email').val('');
        $('#supplierModal').modal('show');
    }

function showEditSupplierModal(supplier) {
    $('#supplierModalLabel').text('Edit Supplier');
    $('#modalAction').val('edit');
    $('#supplierId').val(supplier.supplier_id);
    $('#nama_supplier').val(supplier.nama_supplier);
    $('#alamat').val(supplier.alamat);
    $('#telepon').val(supplier.telepon);
    $('#email').val(supplier.email);
    $('#supplierModal').modal('show');
}

function deleteSupplier(supplier_id) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#deleteSupplierId').val(supplier_id);
            $('#deleteSupplierForm').submit();
        }
    })
}

$('#supplierModal').on('hidden.bs.modal', function() {
    $('#supplierModalLabel').text('Tambah Supplier');
    $('#modalAction').val('add');
    $('#supplierId').val('');
    $('#nama_supplier').val('');
    $('#alamat').val('');
    $('#telepon').val('');
    $('#email').val('');
});
</script>