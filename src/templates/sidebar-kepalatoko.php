<?php 
// Tangkap halaman yang sedang dibuka
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
 ?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
            <a class="nav-link" href="?page=dashboard">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Manajemen Barang</li>

        <li class="nav-item <?php echo ($page == 'data-barang') ? 'active' : ''; ?>">
            <a class="nav-link" href="?page=data-barang">
                <i class="mdi mdi-package menu-icon"></i>
                <span class="menu-title">Data Barang</span>
            </a>
        </li>
        <li class="nav-item nav-category">Manajemen Customer</li>
        <li class="nav-item <?php echo ($page == 'data-customer') ? 'active' : ''; ?>">
            <a class="nav-link" href="?page=data-customer">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">Data Customer</span>
            </a>
        </li>
        <li class="nav-item nav-category">Manajemen Penjualan</li>
        <li class="nav-item <?php echo ($page == 'data-penjualan') ? 'active' : ''; ?>">
            <a class="nav-link" href="?page=data-penjualan">
                <i class="mdi mdi-cart-outline menu-icon"></i>
                <span class="menu-title">Data Penjualan</span>
            </a>
        </li>
        <li class="nav-item nav-category">Laporan</li>
        <li class="nav-item <?php echo ($page == 'history-forecast') ? 'active' : ''; ?>">
            <a class="nav-link" href="?page=history-forecast">
                <i class="mdi mdi-file-document menu-icon"></i>
                <span class="menu-title">Hasil Prediksi</span>
            </a>
        </li>
    </ul>
</nav>