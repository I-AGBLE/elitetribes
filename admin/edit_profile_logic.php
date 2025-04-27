<?php
require 'config/database.php';

if (isset($_POST['submit'])) {

    // Sanitize user input
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_avatar = filter_var($_POST['previous_avatar'], FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
    $telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
    $about = filter_var($_POST['about'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $avatar = $_FILES['avatar'];
    $confirm_human = filter_var($_POST['confirm_human'], FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate user input
    if (!$username) {
        $_SESSION['edit_profile'] = 'Enter Username!';
    } else if (!$telephone) {
        $_SESSION['edit_profile'] = 'Enter Your Telephone Number!';
    } else if (!$gender) {
        $_SESSION['edit_profile'] = 'Select Gender!';
    } else if (!$about) {
        $_SESSION['edit_profile'] = 'Tell Us About Yourself!';
    } else if (!$email) {
        $_SESSION['edit_profile'] = 'Enter Valid Email!';
    } // Check if username already exists for another user
    $check_username_query = "SELECT * FROM tribesmen WHERE username = '$username' AND id != $id LIMIT 1";
    $check_username_result = mysqli_query($connection, $check_username_query);
    if (mysqli_num_rows($check_username_result) > 0) {
        $_SESSION['edit_profile'] = 'Username Unavailable!';
    }

    // Check if telephone already exists for another user
    $check_telephone_query = "SELECT * FROM tribesmen WHERE telephone = '$telephone' AND id != $id LIMIT 1";
    $check_telephone_result = mysqli_query($connection, $check_telephone_query);
    if (mysqli_num_rows($check_telephone_result) > 0) {
        $_SESSION['edit_profile'] = 'Telephone Number Unavailable!';
    } elseif (!empty($confirm_human)) {
        $_SESSION['edit_profile'] = 'Somethings Are For Humans Only!';
    } else {
        // Default to previous avatar
        $avatar_to_insert = $previous_avatar;

        // If new avatar was uploaded
        if ($avatar['name']) {
            $previous_avatar_path = '../images/' . $previous_avatar;

            if (file_exists($previous_avatar_path)) {
                unlink($previous_avatar_path);
            }

            $time = time();
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = '../images/' . $avatar_name;

            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $avatar_name);
            $extension = end($extension);

            if (in_array($extension, $allowed_files)) {
                if ($avatar['size'] < 5000000) {
                    move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    $avatar_to_insert = $avatar_name; // ✅ set the new avatar only if uploaded
                } else {
                    $_SESSION['edit_profile'] = 'Image Should Be Less Than 5mb!';
                }
            } else {
                $_SESSION['edit_profile'] = 'Image Should Either Be a JPG, JPEG, Or PNG File!';
            }
        }

        // Proceed with update if no session error
        if (!isset($_SESSION['edit_profile'])) {
            $update_tribesmen_query = "UPDATE tribesmen SET 
                username = '$username', 
                telephone = '$telephone', 
                gender = '$gender', 
                about = '$about', 
                email = '$email', 
                avatar = '$avatar_to_insert' 
                WHERE id = $id 
                LIMIT 1";

            $update_tribesmen_result = mysqli_query($connection, $update_tribesmen_query);

            if (!mysqli_errno($connection)) {
                $_SESSION["edit_profile_success"] = "Profile Update Successful.";
                header("location: " . ROOT_URL . 'admin/user_profile.php');
                die();
            }
        }
    }

    // If there's an error, redirect back
    if (isset($_SESSION['edit_profile'])) {
        header('Location: ' . ROOT_URL . 'admin/edit_profile.php?id=' . $id);
        die();
    }
} else {
    header('location: ' . ROOT_URL . 'admin/user_profile.php');
    die();
}
