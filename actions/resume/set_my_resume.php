<?php
session_start();
require '../../config/db.php'; // adjust path if needed

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../dashboard.php?status=unauthorized");
    exit;
}

$user_id = $_SESSION['id'];
$resume_id = $_POST['resume_id'] ?? null;

if (!$resume_id) {
    header("Location: ../../dashboard.php?status=missing_id");
    exit;
}

try {
    // Step 1: Reset all user's resumes to type = 0
    $stmt = $conn->prepare("UPDATE resumes SET type = 0 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Step 2: Set the selected resume as type = 1
    $stmt = $conn->prepare("UPDATE resumes SET type = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $resume_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: ../../dashboard.php?status=success");
    } else {
        header("Location: ../../dashboard.php?status=failed");
    }

    $stmt->close();
    $conn->close();
    exit;

} catch (Exception $e) {
    header("Location: ../../dashboard.php?status=error");
    exit;
}
?>
