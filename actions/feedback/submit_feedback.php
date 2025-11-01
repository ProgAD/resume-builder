<?php
require '../../config/db.php'; // adjust path if needed

// Collect form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$feedback = $_POST['feedback'] ?? '';

// Validate input
$sql = "INSERT INTO feedback (name, email, feedback) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $feedback);

if ($stmt->execute()) {
    header('Location: ../../index.html?action=feedback&success=1');
    exit;
} else {
    header('Location: ../../index.html?action=feedback&success=0');
    exit;
}


?>