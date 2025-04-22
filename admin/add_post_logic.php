<?php
require 'config/database.php';



// if submit button is clicked
if (isset($_POST['submit'])) {

    // get user id 
    $created_by = $_SESSION['user_id'];

    // sanitize post input
    $user_post = filter_var($_POST['user_post'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $images = $_FILES['images'];
    $confirm_human = filter_var($_POST['confirm_human'], FILTER_SANITIZE_SPECIAL_CHARS);


    // Validate post input
if (empty($user_post) && empty(array_filter($images['name']))) {
    $_SESSION['add_post'] = 'Cannot Share Empty Post!';
} elseif (!empty($confirm_human)) {
    $_SESSION['add_post'] = 'Somethings Are For Humans Only!';
} else {
    $time = time();
    $uploaded_images = [];

    foreach ($images['name'] as $key => $name) {
        // Skip empty image slots
        if (empty($name)) continue;

        $image_name = $time . '_' . basename($name);
        $image_tmp_name = $images['tmp_name'][$key];
        $image_size = $images['size'][$key];
        $image_destination_path = '../images/' . $image_name;

        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($extension, $allowed_files)) {
            if ($image_size < 5000000) {
                if (move_uploaded_file($image_tmp_name, $image_destination_path)) {
                    $uploaded_images[] = $image_name;
                }
            } else {
                $_SESSION['add_post'] = 'File size should be less than 5MB!';
                break;
            }
        } else {
            $_SESSION['add_post'] = 'Image must be in jpg, png, or jpeg format!';
            break;
        }
    }
}


    // redirect to index if error 
    if (isset($_SESSION['add_post'])) {
        $_SESSION['add_post_data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/');
        die();
    } else {
        $query = "INSERT INTO scrolls (`user_post`, `images`, `created_by`) VALUES ('$user_post', '$image_name', $created_by)";

        $result = mysqli_query($connection, $query);
        if (!mysqli_errno($connection)) {
            $_SESSION["add_post_success"] = "New Scroll Added.";
            header("location: " . ROOT_URL . "admin/user_profile.php#my_posts");
            die();
        }
    }
} else {
    header('location: ' . ROOT_URL . 'admin/');
}
