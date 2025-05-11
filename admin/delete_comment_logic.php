<?php 
require 'config/database.php';


if (isset($_GET['id'])) {
    // sanitize id 
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);


    $query = "SELECT * FROM comments WHERE id=$id";
    $result = mysqli_query($connection, $query);

    // check comment availability
  if (mysqli_num_rows($result) == 1) {
    $comment = mysqli_fetch_assoc($result);


    $delete_comment_query = "DELETE FROM comments WHERE id=$id LIMIT 1";
    $delete_comment_result = mysqli_query($connection, $delete_comment_query);

    if (!mysqli_errno($connection)) {
        $_SESSION["delete_comment_success"] = "Comment Deleted Successfully.";
        header('location: ' . ROOT_URL . 'admin/post_preview.php?id' . $id);
        die();
    } else {
        $_SESSION["delete_comment"] = "Unable To Delete Comment!";
        header('location: ' . ROOT_URL . 'admin/post_preview.php?id' . $id);
        die();
        
    }
  }



} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}