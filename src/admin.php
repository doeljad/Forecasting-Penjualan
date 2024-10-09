<body class="with-welcome-text">
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];
            if ($role == 1) {
                include('src/templates/navbar.php');
            } elseif ($role == 2) {
                include('src/templates/navbar-kepalatoko.php');
            } else {
                echo "Role tidak dikenali. Hubungi administrator.";
                exit;
            }
        } else {
            echo "Role tidak ditemukan. Hubungi administrator.";
            exit;
        }
        ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php
            if (isset($role)) {
                if ($role == 1) {
                    include('src/templates/sidebar.php');
                } elseif ($role == 2) {
                    include('src/templates/sidebar-kepalatoko.php');
                }
            }
            ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            // Include the appropriate routes file based on the role
                            if (isset($role)) {
                                if ($role == 1) {
                                    include('src/config/routes.php');
                                } elseif ($role == 2) {
                                    include('src/config/routeskepala.php');
                                } else {
                                    echo "Role tidak dikenali. Hubungi administrator.";
                                }
                            } else {
                                echo "Role tidak ditemukan. Hubungi administrator.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>