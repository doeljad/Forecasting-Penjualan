<?php
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