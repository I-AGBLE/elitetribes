<?php
require 'config/database.php';


if (isset($_GET['id'])) {
    session_start(); // Ensure session is started

    $follower = $_SESSION['user_id']; // The one doing the following
    $followed = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // The one being followed

    // Prevent self-following
    if ($follower == $followed) {
        header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
        $_SESSION['follow'] = 'You Cannot Follow Yourself!';
        exit();
    }

    // Check if already following
    $check_query = "SELECT * FROM followers WHERE follower=$follower AND followed=$followed";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Already following, so unfollow
        $delete_query = "DELETE FROM followers WHERE follower=$follower AND followed=$followed";
        mysqli_query($connection, $delete_query);
        $_SESSION['follow'] = 'You Have Unfollowed!';
    } else {
        // Not following yet, so follow
        $insert_query = "INSERT INTO followers (follower, followed) VALUES ($follower, $followed)";
        mysqli_query($connection, $insert_query);
        $_SESSION['follow_success'] = 'You Have Followed Successfully.';

    }

    // Redirect back to profile or scrolls page
    header('Location: ' . ROOT_URL . 'admin/profiles.php?id=' . $followed . '#my_posts');
    exit();

} else {
    header('location: ' . ROOT_URL . 'admin/');
    exit();
}











