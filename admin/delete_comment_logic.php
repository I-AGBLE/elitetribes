<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    // Step 1: Validate comment ID
    $comment_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$comment_id) {
        $_SESSION["delete_comment"] = "Invalid comment ID.";
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    }

    // Step 2: Fetch scroll_id from the comments table using prepared statement
    $stmt = $connection->prepare("SELECT scroll_id FROM comments WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $comment = $result->fetch_assoc();
        $scroll_id = $comment['scroll_id'];
        $stmt->close();

        // Step 3: Delete the comment securely
        $del_stmt = $connection->prepare("DELETE FROM comments WHERE id = ? LIMIT 1");
        $del_stmt->bind_param("i", $comment_id);
        $del_stmt->execute();

        if ($del_stmt->affected_rows > 0) {
            $_SESSION["delete_comment_success"] = "Comment Deleted Successfully.";
        } else {
            $_SESSION["delete_comment"] = "Unable to delete comment.";
        }

        $del_stmt->close();

        // Redirect to the post preview page
        header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . $scroll_id);
        exit;
    } else {
        $_SESSION["delete_comment"] = "Comment not found.";
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    }
} else {
    $_SESSION["delete_comment"] = "No comment ID provided.";
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}
