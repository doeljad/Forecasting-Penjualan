<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MaterialStore</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.9.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/1.0.1/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/typicons/2.1.2/typicons.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- endinject -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="src/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="src/assets/images/cv-assalam-mini.png" />
</head>

<body class="with-welcome-text">
    <?php include('src/admin.php') ?>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="src/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="src/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="src/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="src/assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="src/assets/js/off-canvas.js"></script>
    <script src="src/assets/js/template.js"></script>
    <script src="src/assets/js/settings.js"></script>
    <script src="src/assets/js/hoverable-collapse.js"></script>
    <script src="src/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="src/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="src/assets/js/dashboard.js"></script>
    <!-- <script src="src/assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function getGreetingMessage() {
        var now = new Date();
        var hour = now.getHours();
        var greeting;

        if (hour >= 5 && hour < 12) {
            greeting = "Selamat Pagi";
        } else if (hour >= 12 && hour < 15) {
            greeting = "Selamat Siang";
        } else if (hour >= 15 && hour < 18) {
            greeting = "Selamat Sore";
        } else {
            greeting = "Selamat Malam";
        }

        return greeting;
    }

    document.getElementById('greeting').innerText = getGreetingMessage();
    </script>
</body>

</html>