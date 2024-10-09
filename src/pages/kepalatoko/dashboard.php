<?php
include('src/config/koneksi.php');


// Menghitung total barang
$sql_products = "SELECT COUNT(*) AS total_products FROM products";
$result_products = $conn->query($sql_products);
$row_products = $result_products->fetch_assoc();
$total_products = $row_products['total_products'];

// Menghitung total customer
$sql_customers = "SELECT COUNT(*) AS total_customers FROM customers";
$result_customers = $conn->query($sql_customers);
$row_customers = $result_customers->fetch_assoc();
$total_customers = $row_customers['total_customers'];

// Menghitung total penjualan
$sql_sales = "SELECT COUNT(*) AS total_sales FROM sales";
$result_sales = $conn->query($sql_sales);
$row_sales = $result_sales->fetch_assoc();
$total_sales = $row_sales['total_sales'];

// Mengambil data penjualan per minggu untuk grafik
$sql_sales_data = "SELECT DATE_FORMAT(sale_date, '%Y-%u') AS week, SUM(total_price) AS total_price 
                   FROM sales GROUP BY week ORDER BY week ASC";
$result_sales_data = $conn->query($sql_sales_data);

$sales_weeks = [];
$sales_totals = [];

while ($row_sales_data = $result_sales_data->fetch_assoc()) {
    $sales_weeks[] = $row_sales_data['week'];
    $sales_totals[] = $row_sales_data['total_price'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <!-- Link Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Link Bootstrap Datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
    </script>
    <style>
    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .card .card-body {
        padding: 1.5rem;
        display: flex;
        align-items: center;
    }

    .card .card-title {
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .card .card-text {
        font-size: 2rem;
        margin-left: auto;
    }

    .card .icon {
        font-size: 3rem;
    }

    a.card {
        text-decoration: none;
        color: inherit;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4 text-center">Dashboard</h1>
        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="?page=data-barang" class="card border border-success text-dark text-center">
                    <div class="card-body">
                        <i class="bi bi-box-seam icon"></i>
                        <div>
                            <h5 class="card-title">Total Barang</h5>
                            <p class="card-text"><?php echo $total_products; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="?page=data-customer" class="card border border-warning text-dark text-center">
                    <div class="card-body">
                        <i class="bi bi-person-fill icon"></i>
                        <div>
                            <h5 class="card-title">Total Customer</h5>
                            <p class="card-text"><?php echo $total_customers; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="?page=data-penjualan" class="card border border-danger text-dark text-center">
                    <div class="card-body">
                        <i class="bi bi-cart-fill icon"></i>
                        <div>
                            <h5 class="card-title">Total Penjualan</h5>
                            <p class="card-text"><?php echo $total_sales; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <h1 class="my-4 text-center">Grafik Data Penjualan/Minggu</h1>
        <div class="row">
            <div class="col-md-12">
                <canvas id="performanceLine"></canvas>
            </div>
        </div>
    </div>


    <!-- Link JS Bootstrap and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        if ($("#performanceLine").length) {
            const ctx = document.getElementById('performanceLine').getContext('2d');
            var graphGradient = ctx.createLinearGradient(0, 0, 0, 400);
            var graphGradient2 = ctx.createLinearGradient(0, 0, 0, 400);
            graphGradient.addColorStop(0, 'rgba(26, 115, 232, 0.18)');
            graphGradient.addColorStop(1, 'rgba(26, 115, 232, 0.02)');
            graphGradient2.addColorStop(0, 'rgba(0, 208, 255, 0.19)');
            graphGradient2.addColorStop(1, 'rgba(0, 208, 255, 0.03)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($sales_weeks); ?>,
                    datasets: [{
                        label: 'Total Penjualan',
                        data: <?php echo json_encode($sales_totals); ?>,
                        backgroundColor: graphGradient,
                        borderColor: '#1F3BB3',
                        borderWidth: 1.5,
                        fill: true,
                        pointBorderWidth: 1,
                        pointRadius: [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
                        pointHoverRadius: [2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2],
                        pointBackgroundColor: ['#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3',
                            '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3',
                            '#1F3BB3', '#1F3BB3', '#1F3BB3', '#1F3BB3'
                        ],
                        pointBorderColor: ['#fff', '#fff', '#fff', '#fff', '#fff', '#fff',
                            '#fff', '#fff', '#fff', '#fff', '#fff', '#fff', '#fff'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: {
                            tension: 0.4,
                        }
                    },
                    scales: {
                        y: {
                            border: {
                                display: false
                            },
                            grid: {
                                display: true,
                                color: "#F0F0F0",
                                drawBorder: false
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                font: {
                                    size: 11,
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        x: {
                            border: {
                                display: false
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                font: {
                                    size: 11,
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 59, 179, 1)',
                        }
                    }
                }
            });
        }
    });
    </script>
</body>

</html>