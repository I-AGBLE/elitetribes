<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    // sanitize id 
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Only proceed if $id is valid
    if ($id) {
        // Fetch the user
        $query = "SELECT * FROM tribesmen WHERE id=$id";
        $result = mysqli_query($connection, $query);

        // Check if user exists
        if ($result && mysqli_num_rows($result) == 1) {
            $tribesmen = mysqli_fetch_assoc($result);

            $image_name = trim($tribesmen["avatar"]);
            $image_path = '../images/' . $image_name;
            if ($image_name && is_file($image_path)) {
                unlink($image_path);  // Delete the image from the server
            }

            // Delete the user record from the database
            $delete_profile_query = "DELETE FROM tribesmen WHERE id=$id LIMIT 1";
            $delete_profile_result = mysqli_query($connection, $delete_profile_query);

if ($delete_profile_result) {
    $_SESSION["delete_profile_success"] = "Account Deleted Successfully.";
    header("location: " . ROOT_URL );
    exit;
} else {
    $_SESSION["delete_profile"] = "Failed to delete user from database. Error: " . mysqli_error($connection);
    header('location: ' . ROOT_URL . 'admin/');
    exit;
}
        } else {
            $_SESSION["delete_profile"] = "User not found.";
            header('location: ' . ROOT_URL . 'admin/');
            exit;
        }
    }
    
    else {
        $_SESSION["delete_profile"] = "Invalid User!.";
        header('location: ' . ROOT_URL . 'admin/');
        exit;
    }


} else {
    $_SESSION["delete_profile"] = "Operation Failed!.";
    header('location: ' . ROOT_URL . 'admin/');
    exit;
}