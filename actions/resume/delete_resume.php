<?php
header('Content-Type: application/json');
require_once '../../config/db.php'; // adjust path if needed

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['deleteId']) || empty($data['deleteId'])) {
    echo json_encode(['success' => false, 'message' => 'Missing resume ID']);
    exit;
}

$deleteId = intval($data['deleteId']);

try {
    // Prepare delete query
    $stmt = $conn->prepare("DELETE FROM resumes WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Resume deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Resume not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete resume']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
