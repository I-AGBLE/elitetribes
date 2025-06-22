<?php
require_once 'config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ' . ROOT_URL . 'admin/dev_mod/all_tribesmen.php');
    exit;
}

$profileUserId = (int)$_GET['id'];

// Fetch current admin status
$stmt = $connection->prepare("SELECT is_admin FROM tribesmen WHERE id = ?");
$stmt->bind_param("i", $profileUserId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user) {
    $newAdmin = $user['is_admin'] ? 0 : 1; // Toggle admin status
    $update = $connection->prepare("UPDATE tribesmen SET is_admin = ? WHERE id = ?");
    $update->bind_param("ii", $newAdmin, $profileUserId);
    $update->execute();
    $update->close();
}

// Redirect back to user details
header('Location: all_user_details.php?id=' . urlencode($profileUserId));
exit;