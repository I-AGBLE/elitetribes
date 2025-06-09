<?php
require 'config/database.php';


// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['follow'] = 'You must be logged in to follow someone!';
    header('Location: ' . ROOT_URL . 'signin.php');
    exit();
}

if (isset($_GET['id'])) {
    // Validate and sanitize inputs
    $follower = $_SESSION['user_id']; // Already from session
    $followed = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    // Validate the ID
    if ($followed === false || $followed <= 0) {
        $_SESSION['follow'] = 'Invalid user ID!';
        header('Location: ' . ROOT_URL . 'admin/');
        exit();
    }

    // Prevent self-following
    if ($follower == $followed) {
        header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
        $_SESSION['follow'] = 'You Cannot Follow Yourself!';
        exit();
    }

    // Check if already following using prepared statement
    $check_query = "SELECT * FROM followers WHERE follower=? AND followed=?";
    $stmt = mysqli_prepare($connection, $check_query);
    mysqli_stmt_bind_param($stmt, "ii", $follower, $followed);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Already following, so unfollow
        $delete_query = "DELETE FROM followers WHERE follower=? AND followed=?";
        $stmt = mysqli_prepare($connection, $delete_query);
        mysqli_stmt_bind_param($stmt, "ii", $follower, $followed);
        mysqli_stmt_execute($stmt);
        $_SESSION['follow'] = 'You Have Unfollowed!';
    } else {
        // Not following yet, so follow
        $insert_query = "INSERT INTO followers (follower, followed) VALUES (?, ?)";
        $stmt = mysqli_prepare($connection, $insert_query);
        mysqli_stmt_bind_param($stmt, "ii", $follower, $followed);
        mysqli_stmt_execute($stmt);
        $_SESSION['follow_success'] = 'You Have Followed Successfully.';
    }

    // Redirect back to profile page
    header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
    exit();

} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit();
}