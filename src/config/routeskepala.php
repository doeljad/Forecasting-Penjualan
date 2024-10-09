<?php
// Ambil parameter dari URL
$params = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Definisikan route
$routes = [
    'dashboard' => 'src/pages/kepalatoko/dashboard.php',
    'kategori-produk' => 'src/pages/kepalatoko/kategori-produk.php',
    'stok-produk' => 'src/pages/kepalatoko/stok-produk.php',
    'data-penjualan' => 'src/pages/kepalatoko/data-penjualan.php',
    'data-barang' => 'src/pages/kepalatoko/data-barang.php',
    'data-customer' => 'src/pages/kepalatoko/data-customer.php',
    'data-suplier' => 'src/pages/kepalatoko/data-suplier.php',
    'history-forecast' => 'src/pages/kepalatoko/history-forecast.php',
];

// Periksa apakah URL ada di route
if (isset($routes[$params])) {
    // Tentukan halaman yang akan dimuat
    $page = $routes[$params];
    // Include halaman yang sesuai
    if (file_exists($page)) {
        include_once($page);
    } else {
        // Jika file tidak ditemukan, redirect ke halaman 404 atau halaman lain yang sesuai
        echo "<script>window.location.href = 'src/pages/error-404.html'</script>";
        exit(); // Penting untuk menghentikan eksekusi skrip setelah melakukan redirect
    }
} else {
    // Jika URL tidak ada di route, redirect ke halaman 404 atau halaman lain yang sesuai
    echo "<script>window.location.href = 'src/pages/error-404.html'</script>";
    exit(); // Penting untuk menghentikan eksekusi skrip setelah melakukan redirect
}
?>