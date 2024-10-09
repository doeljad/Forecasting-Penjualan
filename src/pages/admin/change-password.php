<?php
// Check if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$koneksiPath =  'src/config/koneksi.php';
if (!file_exists($koneksiPath)) {
    die("Error: Unable to include 'koneksi.php'. File does not exist.");
}
include($koneksiPath);

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";
if (isset($_POST['submit'])) {

    if (!isset($_SESSION['user_id'])) {
        die('Session expired. Please log in again.');
    }

    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        // Siapkan dan bind
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_password);
            $stmt->fetch();
            
            if ($current_password = $stored_password) {
                // Debug: Password saat ini cocok
                error_log("Current password is correct.");

                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                if ($update_stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }
                
                $update_stmt->bind_param("si", $new_password, $user_id);
                if ($update_stmt->execute()) {
                    $success = "Password successfully changed.";
                    // Debug: Password berhasil diubah
                    error_log("Password successfully changed.");
                } else {
                    $error = "Error updating password: " . htmlspecialchars($update_stmt->error);
                    // Debug: Update gagal
                    error_log("Error updating password: " . htmlspecialchars($update_stmt->error));
                }
                $update_stmt->close();
            } else {
                $error = "Current password is incorrect.";
                // Debug: Password saat ini tidak cocok
                error_log("Current password is incorrect.");
            }
        } else {
            $error = "User not found.";
            // Debug: Pengguna tidak ditemukan
            error_log("User not found.");
        }

        $stmt->close();
        $conn->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .container {
        max-width: 400px;
        margin-top: 100px;
    }

    .card {
        padding: 20px;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h2 class="text-center">Change Password</h2>
            <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">Change Password</button>
            </form>
        </div>
    </div>
    <!-- Link JS Bootstrap and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>