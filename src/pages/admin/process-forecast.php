<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
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
// include('src/config/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $forecastPeriod = isset($_POST['forecast_period']) ? $_POST['forecast_period'] : null;

    if ($productId && $forecastPeriod) {
        // Get sales data for linear regression calculation
        $sqlSales = "SELECT sale_date, quantity FROM sales WHERE product_id = ?";
        $stmt = $conn->prepare($sqlSales);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $resultSales = $stmt->get_result();

        $dates = [];
        $quantities = [];
        while ($row = $resultSales->fetch_assoc()) {
            $dates[] = strtotime($row['sale_date']);
            $quantities[] = $row['quantity'];
        }

        // Check if there is enough data for prediction
        if (count($dates) < 2) {
            echo json_encode(['error' => 'Not enough data for prediction.']);
            exit;
        }

        // Calculate linear regression model
        $n = count($dates);
        $sumX = array_sum($dates);
        $sumY = array_sum($quantities);
        $sumXY = 0;
        $sumX2 = 0;
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $dates[$i] * $quantities[$i];
            $sumX2 += $dates[$i] * $dates[$i];
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        // Calculate predicted values
        $predictedDates = [];
        $predictedValues = [];
        $currentDate = end($dates);
        $interval = 30 * 24 * 60 * 60; // 30 days in seconds

        for ($i = 1; $i <= $forecastPeriod * 30; $i += 30) {
            $currentDate += $interval;
            $predictedDates[] = date("Y-m-d", $currentDate);
            $predictedValues[] = $slope * $currentDate + $intercept;
        }

        // Insert predicted values into the forecasts table
        $sqlInsertForecast = "INSERT INTO forecasts (product_id, forecast_date, forecasted_quantity, forecast_period) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsertForecast);

        foreach ($predictedDates as $index => $forecastDate) {
            $forecastedQuantity = $predictedValues[$index];
            $stmtInsert->bind_param("isdi", $productId, $forecastDate, $forecastedQuantity, $forecastPeriod);
            $stmtInsert->execute();
        }

        // Return predicted values in JSON format
        echo json_encode([
            'dates' => $predictedDates,
            'predicted_values' => $predictedValues
        ]);
    } else {
        echo json_encode(['error' => 'Missing required POST parameters.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}

$conn->close();
?>