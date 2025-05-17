<?php
require 'config/database.php';



if (isset($_GET['id'])) {
    $repost_id = intval($_GET['id']); // Sanitize input
    $created_by = $_SESSION['user_id'];

    // Check if the scroll exists
    $query_check = "SELECT * FROM scrolls WHERE id = $repost_id";
    $result_check = mysqli_query($connection, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        $scroll = mysqli_fetch_assoc($result_check); // fetch scroll details

        // Check if the user has already reposted this scroll
        $query_repost_check = "SELECT * FROM reposts WHERE repost_id = $repost_id AND created_by = $created_by";
        $result_repost_check = mysqli_query($connection, $query_repost_check);

        if (mysqli_num_rows($result_repost_check) == 0) {
            // User has not reposted — insert repost with scroll data
            $user_post = mysqli_real_escape_string($connection, $scroll['user_post']);
            $images = mysqli_real_escape_string($connection, $scroll['images']);
            $original_creator = $scroll['created_by'];
            $created_at = $scroll['created_at'];

            $query_insert = "
                INSERT INTO reposts 
                (repost_id, user_post, images, original_creator, created_by, created_at) 
                VALUES 
                ($repost_id, '$user_post', '$images', $original_creator, $created_by, '$created_at')
            ";

            if (mysqli_query($connection, $query_insert)) {
                $_SESSION['repost_success'] = "Post successfully reposted.";
                 header("Location: post_preview.php?id=$repost_id");
                exit();
            } else {
                $_SESSION['repost'] = "Failed to repost. Try again.";
                   header("Location: post_preview.php?id=$repost_id");
                exit();
            }
        } else {
            $_SESSION['repost'] = "You have already reposted this scroll.";
               header("Location: post_preview.php?id=$repost_id");
                exit();
        }
    } else {
        $_SESSION['repost'] = "This scroll does not exist.";
    }

    // Redirect back or to another page if needed
    header("Location: post_preview.php?id=$repost_id");
    exit();

}  else {
header('Location: ' . ROOT_URL . 'admin/');
exit();
}
