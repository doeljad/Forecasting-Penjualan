<?php
// include('src/config/koneksi.php');
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "materialstore";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Mengambil data produk dari database
$sql_products = "SELECT product_id, name FROM products";
$result_products = $conn->query($sql_products);

$conn->close();
?>

<body>
    <div class="container">
        <h2 class="my-4">Forecasting</h2>
        <form id="forecastForm" method="post">
            <div class="form-group">
                <label for="product_id">Pilih Produk</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <option value="">Pilih Produk</option>
                    <?php
                    if ($result_products->num_rows > 0) {
                        while ($row = $result_products->fetch_assoc()) {
                            echo "<option value='" . $row["product_id"] . "'>" . $row["name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="forecast_period">Pilih Periode</label>
                <select class="form-control" id="forecast_period" name="forecast_period" required>
                    <option value="1">Bulan Ini</option>
                    <option value="2">Bulan Depan</option>
                    <option value="3">3 Bulan Kedepan</option>
                    <option value="4">6 Bulan Kedepan</option>
                    <option value="5">1 Tahun Kedepan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="mt-4" style="display: none;">
            <canvas id="forecastChart"></canvas>
            <p id="periodDescription"></p> <!-- Tambahkan elemen untuk menampilkan keterangan periode -->
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#forecastForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "src/pages/admin/process-forecast.php", // Ensure this URL is correct
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        let data = response;
                        let labels = data.dates;
                        let values = data.predicted_values;
                        let period = $('#forecast_period option:selected').text();

                        let ctx = document.getElementById('forecastChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Forecast (' + period + ')',
                                    data: values,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    xAxes: [{
                                        type: 'time',
                                        time: {
                                            unit: 'month'
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }],

                                },
                                plugins: {
                                    legend: {
                                        display: false,
                                    }
                                }
                            }
                        });

                        $('#periodDescription').text('Forecast Period: ' + period);
                        $('.mt-4').css('display', 'block');
                    } catch (e) {
                        console.error('Parsing JSON failed:', e);
                        console.log('Response:', response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error: ' + textStatus + ': ' + errorThrown);
                    console.log('Response Text:', jqXHR.responseText);
                }
            });
        });
    });
    </script>
</body>

</html>