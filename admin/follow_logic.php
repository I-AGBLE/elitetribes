<?php
require_once 'config/database.php';

// Verify user is logged in
if (empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    $_SESSION['follow'] = 'You must be logged in to follow someone!';
    header('Location: ' . ROOT_URL);
    exit();
}

if (isset($_GET['id'])) {
    // Validate and sanitize inputs
    $follower = (int)$_SESSION['user_id'];
    $followed = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    // Validate the ID
    if ($followed === false || $followed <= 0) {
        $_SESSION['follow'] = 'Invalid user ID!';
        header('Location: ' . ROOT_URL . 'admin/');
        exit();
    }

    // Prevent self-following
    if ($follower === $followed) {
        $_SESSION['follow'] = 'You Cannot Follow Yourself!';
        header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
        exit();
    }

    // Check if already following using prepared statement
    $check_query = "SELECT 1 FROM followers WHERE follower=? AND followed=? LIMIT 1";
    $stmt = mysqli_prepare($connection, $check_query);
    if (!$stmt) {
        $_SESSION['follow'] = 'Unable To Perform Database Action.';
        header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $follower, $followed);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Already following, so unfollow
        mysqli_stmt_close($stmt);
        $delete_query = "DELETE FROM followers WHERE follower=? AND followed=?";
        $stmt = mysqli_prepare($connection, $delete_query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $follower, $followed);
            mysqli_stmt_execute($stmt);
            $_SESSION['follow'] = 'User Unfollowed!';
        } else {
            $_SESSION['follow'] = 'Unable To Perform Database Action.';
        }
    } else {
        // Not following yet, so follow
        mysqli_stmt_close($stmt);
        $insert_query = "INSERT INTO followers (follower, followed) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $insert_query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $follower, $followed);
            mysqli_stmt_execute($stmt);
            $_SESSION['follow_success'] = 'User Followed Successfully.';
        } else {
            $_SESSION['follow'] = 'Unable To Perform Database Action.';
        }
    }
    if ($stmt) {
        mysqli_stmt_close($stmt);
    }

    // Redirect back to profile page
    header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
    exit();
} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit();
}
