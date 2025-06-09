<?php


require 'config/database.php';

// Verify CSRF token if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'])) {
    $_SESSION['like'] = 'Invalid request';
    header('Location: ' . ROOT_URL . 'admin/');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['like'] = 'You must be logged in to like posts';
    header('Location: ' . ROOT_URL . 'signin.php');
    exit();
}

if (isset($_GET['id'])) {
    $scroll_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $tribesmen_id = $_SESSION['user_id'];
    
    // Validate IDs
    if ($scroll_id <= 0 || $tribesmen_id <= 0) {
        $_SESSION['like'] = 'Invalid request';
        header('Location: ' . ROOT_URL . 'admin/');
        exit();
    }

    // Check if a like entry exists using prepared statement
    $query_check = "SELECT id FROM likes WHERE scroll_id = ? AND tribesmen_id = ?";
    $stmt = mysqli_prepare($connection, $query_check);
    mysqli_stmt_bind_param($stmt, "ii", $scroll_id, $tribesmen_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Entry exists – delete the like
        $row = mysqli_fetch_assoc($result);
        $like_id = $row['id'];

        // Delete using prepared statement
        $query_delete = "DELETE FROM likes WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query_delete);
        mysqli_stmt_bind_param($stmt, "i", $like_id);
        mysqli_stmt_execute($stmt);

        $_SESSION['like'] = 'You Unliked This Post';
        header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . $scroll_id);
        exit();
    } else {
        // Insert new like using prepared statement
        $query_insert = "INSERT INTO likes (scroll_id, tribesmen_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $query_insert);
        mysqli_stmt_bind_param($stmt, "ii", $scroll_id, $tribesmen_id);
        mysqli_stmt_execute($stmt);

        $_SESSION['like_success'] = 'You liked this post';
        header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . $scroll_id);
        exit();
    }

} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit();
}