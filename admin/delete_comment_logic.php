<?php
require 'config/database.php'; // Adjust this to your actual database config path

if (isset($_GET['id'])) {
    // Step 1: Sanitize comment ID
    $comment_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Step 2: Fetch scroll_id from the comments table
    $query = "SELECT scroll_id FROM comments WHERE id = $comment_id LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $comment = mysqli_fetch_assoc($result);
        $scroll_id = $comment['scroll_id']; // This is the post ID we want

       // Delete the comment
        $delete_query = "DELETE FROM comments WHERE id = $comment_id LIMIT 1";
        $delete_result = mysqli_query($connection, $delete_query);

        // Handle result and redirect
        if (!mysqli_errno($connection)) {
            $_SESSION["delete_comment_success"] = "Comment Deleted Successfully.";
        } else {
            $_SESSION["delete_comment"] = "Unable to delete comment.";
        }

        // Redirect to the post preview page using the scroll_id
        header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . $scroll_id);
        exit;
    } else {
        // Comment not found or error
        $_SESSION["delete_comment"] = "Comment not found.";
        header('Location: ' . ROOT_URL . 'admin/manage_comments.php'); // fallback
        exit;
    }
}
