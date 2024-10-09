<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <!-- <style>
    .profile-icon {
        font-size: 50px;
        /* Sesuaikan ukuran ikon */
        color: #000;
        /* Sesuaikan warna ikon */
    }
    </style> -->
</head>

</html>

<?php 
$koneksiPath =  'src/config/koneksi.php';
if (!file_exists($koneksiPath)) {
    die("Error: Unable to include 'koneksi.php'. File does not exist.");
}
include($koneksiPath);
// Mengambil data user
$user_id = $_SESSION['user_id']; // Ganti dengan ID user yang diinginkan
$sql = "SELECT username FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Output data dari setiap baris
    while($row = $result->fetch_assoc()) {
        $username = $row["username"];
    }
} else {
    echo "0 results";
}
$conn->close();
?>
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="index.html">
                <img src="src/assets/images/NJL.png" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="index.html">
                <img src="src/assets/images/njl-mini.png" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text"><span id="greeting"></span>, <span class="text-black fw-bold">Admin</span></h1>
                <!-- <h3 class="welcome-sub-text">Your performance summary this week </h3> -->
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">

            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-container">
                        <i class="mdi mdi-account-circle profile-icon" style="font-size: 50px;"></i>
                        <div class="online-indicator"></div>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <div class="profile-container">
                                    <i class="mdi mdi-account-circle profile-icon" style="font-size: 50px;"></i>
                                    <div class="online-indicator"></div>
                                    <p class="mb-1 fw-semibold"><?php echo $username; ?></p>
                                </div>
                                <a href="?page=change-password" class="dropdown-item"><i
                                        class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>Ganti
                                    Password
                                    <span class="badge badge-pill badge-danger">1</span></a>
                                <a class="dropdown-item" href="signout.php"><i
                                        class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                            </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>