<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.css">
</head>

<body>
    <?php
    include('src/config/koneksi.php');
    $success_message = '';
    $error_message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'add') {
            $name = $_POST['name'];
            $contact = $_POST['contact'];
            $address = $_POST['address'];

            $stmt = $conn->prepare("INSERT INTO customers (name, contact, address) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $contact, $address);

            if ($stmt->execute()) {
                $success_message = "Pelanggan baru berhasil ditambahkan!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        if (isset($_POST['action']) && $_POST['action'] == 'edit') {
            $customer_id = $_POST['customer_id'];
            $name = $_POST['name'];
            $contact = $_POST['contact'];
            $address = $_POST['address'];

            $stmt = $conn->prepare("UPDATE customers SET name=?, contact=?, address=? WHERE customer_id=?");
            $stmt->bind_param("sssi", $name, $contact, $address, $customer_id);

            if ($stmt->execute()) {
                $success_message = "Pelanggan berhasil diperbarui!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            $customer_id = $_POST['customer_id'];

            $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id=?");
            $stmt->bind_param("i", $customer_id);

            if ($stmt->execute()) {
                $success_message = "Pelanggan berhasil dihapus!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $sql = "SELECT customer_id, name, contact, address FROM customers";
    $result = $conn->query($sql);
    $conn->close();
    ?>

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-success float-end" data-toggle="modal"
                    data-target="#addCustomerModal">
                    Tambah Pelanggan
                </button>
                <h2 class="mb-2">Data Pelanggan</h2>
                <p class="card-description"> Home /<code>Data Pelanggan</code></p>
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
                    <table class="table table-striped" id="customerTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pelanggan</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
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
                                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                                    echo "<td>
                                        <button class='btn btn-warning btn-sm' onclick='editCustomer(" . json_encode($row) . ")'>Edit</button>
                                        <button class='btn btn-danger btn-sm' onclick='deleteCustomer(" . $row["customer_id"] . ")'>Delete</button>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>Tidak ada pelanggan ditemukan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Pelanggan -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Tambah Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="action" value="add" id="modalAction">
                        <input type="hidden" name="customer_id" id="customerId">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact">Kontak</label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteCustomerForm" action="" method="post" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="customer_id" id="deleteCustomerId">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $('#customerTable').DataTable();
    });

    function editCustomer(customer) {
        $('#addCustomerModalLabel').text('Edit Pelanggan');
        $('#modalAction').val('edit');
        $('#customerId').val(customer.customer_id);
        $('#name').val(customer.name);
        $('#contact').val(customer.contact);
        $('#address').val(customer.address);
        $('#addCustomerModal').modal('show');
    }

    function deleteCustomer(id) {
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
                $('#deleteCustomerId').val(id);
                $('#deleteCustomerForm').submit();
            }
        });
    }

    $('#addCustomerModal').on('hidden.bs.modal', function() {
        $('#addCustomerModalLabel').text('Tambah Pelanggan');
        $('#modalAction').val('add');
        $('#customerId').val('');
        $('#name').val('');
        $('#contact').val('');
        $('#address').val('');
    });
    </script>
</body>

</html>