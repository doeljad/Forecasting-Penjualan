<?php
// Ambil parameter dari URL
$params = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Definisikan route
$routes = [
    // Login 
    'login' => 'login.php',

    // Admin
    'dashboard' => 'src/pages/admin/dashboard.php',
    'change-password' => 'src/pages/admin/change-password.php',
    'kategori-produk' => 'src/pages/admin/kategori-produk.php',
    'stok-produk' => 'src/pages/admin/stok-produk.php',
    'data-penjualan' => 'src/pages/admin/data-penjualan.php',
    'data-barang' => 'src/pages/admin/data-barang.php',
    'data-customer' => 'src/pages/admin/data-customer.php',
    'data-suplier' => 'src/pages/admin/data-suplier.php',
    'forecast' => 'src/pages/admin/forecast.php',
    'history-forecast' => 'src/pages/admin/history-forecast.php',
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