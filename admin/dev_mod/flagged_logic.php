<?php
require 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ' . ROOT_URL );
    exit;
}

$scroll_id = (int)$_GET['id'];

// Fetch current flagged status
$stmt = $connection->prepare("SELECT flagged FROM scrolls WHERE id = ?");
$stmt->bind_param("i", $scroll_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if ($post) {
    $newFlagged = $post['flagged'] ? 0 : 1; // Toggle
    $update = $connection->prepare("UPDATE scrolls SET flagged = ? WHERE id = ?");
    $update->bind_param("ii", $newFlagged, $scroll_id);
    $update->execute();
    $update->close();
}

header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . urlencode($scroll_id));
exit;