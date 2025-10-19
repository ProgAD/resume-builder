<?php
$servername = "127.0.0.1";
$username = "root";
$password = "Code@123";
$database = "resume_builder"; // 🔁 Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
