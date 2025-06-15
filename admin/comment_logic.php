<?php
require_once '../config/database.php';


// Validate GET parameter first
if (isset($_GET['id'])) {
    // Validate and sanitize scroll_id
    $scroll_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if (!$scroll_id || $scroll_id <= 0) {
        $_SESSION['comment'] = 'Invalid Post ID';
        header("Location: " . ROOT_URL . "admin/");
        exit;
    }

    // Check authentication
    if (empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
        $_SESSION['comment'] = 'You must be logged in to comment.';
        header("Location: " . ROOT_URL);
        exit;
    }

    $tribesmen_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
    if (!$tribesmen_id || $tribesmen_id <= 0) {
        $_SESSION['comment'] = 'Invalid User ID';
        header("Location: " . ROOT_URL);
        exit;
    }

    // Only allow POST for comment submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // CSRF token check
        if (
            !isset($_POST['csrf_token']) ||
            !isset($_SESSION['csrf_token']) ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token']
        ) {
            $_SESSION['comment'] = 'Invalid CSRF token.';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        }

        // Validate and sanitize inputs
        $user_comment = trim($_POST['user_comment'] ?? '');
        $confirm_human = trim($_POST['confirm_human'] ?? '');

        // Validate comment content
        if (empty($user_comment)) {
            $_SESSION['comment'] = 'Cannot Post Empty Comment!';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        }

      
        if (!empty($confirm_human)) {
            $_SESSION['comment'] = 'Operation Failed!';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        }

        // Additional content validation
        if (mb_strlen($user_comment) > 1000) {
            $_SESSION['comment'] = 'Comment is too long (max 1000 characters)';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        }

        // Prepare statement to prevent SQL injection
        $query = "INSERT INTO comments (scroll_id, tribesmen_id, user_comment) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iis", $scroll_id, $tribesmen_id, $user_comment);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($result) {
                $_SESSION["comment_success"] = "Comment Shared Successfully.";
            } else {
                $_SESSION["comment"] = "Unable To Comment!";
             
            }
        } else {
            $_SESSION["comment"] = "Database error";
          
        }

        header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
        exit;
    } else {
        // If not POST, redirect
        header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
        exit;
    }
} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}