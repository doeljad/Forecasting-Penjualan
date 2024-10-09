<?php
include('src/config/koneksi.php');

// Mengambil data riwayat prediksi dari database
$sql_history = "SELECT f.forecast_date, p.name AS product_name, f.forecasted_quantity, f.forecast_period 
                FROM forecasts f 
                JOIN products p ON f.product_id = p.product_id 
                ORDER BY f.forecast_date DESC";
$result_history = $conn->query($sql_history);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>History Forecast</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="my-4">History Forecast</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal Prediksi</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Prediksi</th>
                    <th>Periode Prediksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_history->num_rows > 0) {
                    while ($row = $result_history->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['forecast_date'] . "</td>";
                        echo "<td>" . $row['product_name'] . "</td>";
                        echo "<td>" . $row['forecasted_quantity'] . "</td>";
                        echo "<td>" . $row['forecast_period'] . " Bulan</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada riwayat prediksi ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>