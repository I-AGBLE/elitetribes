<?php
require '../config/database.php';



if (isset($_GET['id'])) {
    $scroll_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);


$tribesmen_id = (int) $_SESSION['user_id'];

// Optional: check if scroll exists (basic safety)
$scroll_check = mysqli_query($connection, "SELECT id FROM scrolls WHERE id = $scroll_id LIMIT 1");
$user_check = mysqli_query($connection, "SELECT id FROM tribesmen WHERE id = $tribesmen_id LIMIT 1");

if (mysqli_num_rows($scroll_check) === 0 || mysqli_num_rows($user_check) === 0) {
    http_response_code(404);
    exit("Scroll or user not found.");
}

// Insert into post_shares table
$insert = mysqli_query($connection, "
    INSERT INTO scroll_shares (scroll_id, tribesmen_id) 
    VALUES ($scroll_id, $tribesmen_id)
");

if (!$insert) {
    $_SESSION['share'] = 'Unable to share scroll!';
    header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
    exit;   
} else {
    $_SESSION["share_success"] = "You Shared This Scroll.";
    header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
    
    exit;
}




} else {
    header('location: ' . ROOT_URL . 'admin/');
    exit();
}