<?php
require_once 'partials/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

$profileUserId = (int)$_GET['id'];

// Fetch current blocked status
$query = "SELECT blocked FROM tribesmen WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $profileUserId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($user) {
    $newBlocked = $user['blocked'] ? 0 : 1; // Toggle
    $updateQuery = "UPDATE tribesmen SET blocked = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ii", $newBlocked, $profileUserId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Redirect back to user details
header("Location: all_user_details.php?id=" . urlencode($profileUserId));
exit();