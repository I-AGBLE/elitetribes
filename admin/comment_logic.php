<?php
require 'config/database.php';


// Validate GET parameter first
if (isset($_GET['id'])) {
    // Validate and sanitize scroll_id
    $scroll_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if (!$scroll_id || $scroll_id <= 0) {
        $_SESSION['comment'] = 'Invalid Post ID';
        header("Location: " . ROOT_URL . "admin/");
        exit;
    }


    $tribesmen_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
    if (!$tribesmen_id || $tribesmen_id <= 0) {
        $_SESSION['comment'] = 'Invalid User ID';
        header("Location: " . ROOT_URL . "signin.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     
        // Validate and sanitize inputs
        $user_comment = trim($_POST['user_comment'] ?? '');
        $confirm_human = trim($_POST['confirm_human'] ?? '');

        // Validate comment content
        if (empty($user_comment)) {
            $_SESSION['comment'] = 'Cannot Post Empty Comment!';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        }

        // Check for honeypot field
        if (!empty($confirm_human)) {
            $_SESSION['comment'] = 'Somethings Are For Humans Only!';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        }

        // Additional content validation
        if (strlen($user_comment) > 1000) {
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
                $_SESSION["comment_success"] = "You commented.";
            } else {
                $_SESSION["comment"] = "Unable To Comment!";
                error_log("Comment failed: " . mysqli_error($connection));
            }
        } else {
            $_SESSION["comment"] = "Database error";
            error_log("Prepare failed: " . mysqli_error($connection));
        }

        header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
        exit;
    }
} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}