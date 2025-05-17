<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $scroll_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);   
    $tribesmen_id = $_SESSION['user_id'];
    

    // Check if a like entry exists for this scroll_id and tribesmen_id
    $query_check = "SELECT * FROM likes WHERE scroll_id = $scroll_id AND tribesmen_id = $tribesmen_id";
    $result = mysqli_query($connection, $query_check);

    if (mysqli_num_rows($result) > 0) {
        // Entry exists – delete the like (user is unliking)
        $row = mysqli_fetch_assoc($result);
        $like_id = $row['id'];

        // Delete the like from the database
        $query_delete = "DELETE FROM likes WHERE id = $like_id";
        mysqli_query($connection, $query_delete);

        // Set session message for unliking
        $_SESSION['like'] = 'You Unliked This Post';
          header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . $scroll_id);
        exit();

    } else {
        // No existing entry – insert new like
        $query_insert = "INSERT INTO likes (scroll_id, tribesmen_id) 
                         VALUES ($scroll_id, $tribesmen_id)";
        mysqli_query($connection, $query_insert);

        // Set session message for liking
        $_SESSION['like_success'] = 'You liked this post';
          header('Location: ' . ROOT_URL . 'admin/post_preview.php?id=' . $scroll_id);
        exit();

    }

    // Redirect back to the referring page or a fallback
    $redirect_url = $_SERVER['HTTP_REFERER'] ?? ROOT_URL . 'index.php';
    header("Location: $redirect_url");
    exit;

} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}

