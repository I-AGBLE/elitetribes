<?php
require 'config/database.php';

if (isset($_POST['submit'])) {

    //sanitize user input 
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_avatar = filter_var($_POST['previous_avatar'], FILTER_SANITIZE_SPECIAL_CHARS);

    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
    $telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
    $about = filter_var($_POST['about'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $avatar = $_FILES['avatar'];
    $confirm_human = filter_var($_POST['confirm_human'], FILTER_SANITIZE_SPECIAL_CHARS);


    // validate user input 
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
    } elseif (!empty($confirm_human)) {
        $_SESSION['edit_profile'] = 'Somethings Are For Humans Only!';
    } else {

     // check if password & confirm_password match
        if ($avatar['name']) {

            $previous_avatar_path = '../images/' . $previous_avatar;

            if ($previous_avatar_path) {
                unlink($previous_avatar_path);
            }

            // update avatar name for insertion 
            $time = time();
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = '../images/' . $avatar_name;

            // check file for image 
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $avatar_name);
            $extension = end($extension);

            // if extension is allowed 
            if (in_array($extension, $allowed_files)) {
                // restrict avatar size
                if ($avatar['size'] < 5000000) {
                    // upload avatar
                    move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                } else {
                    // file size more then 5mb
                    $_SESSION['edit_profile'] = 'Image Should Be Less Than 5mb!';
                }
            } else {
                // image format not allowed
                $_SESSION['edit_profile'] = 'Image Should Either Be a JPG, JPEG, Or PNG File!';
            }


        }

    

        // if registration failed, redirect to edit_profile page 
        if (isset($_SESSION['edit_profile'])) {
            header('location: ' . ROOT_URL . 'admin/edit_profile.php');
            die();
        } else {

            // keep old avatar if new one isn't inserted
            $avatar_to_insert = $avatar_name ?? $previous_avatar;

            // insert data into db
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


            // if all is fine 
            if (!mysqli_errno($connection)) {
                // redirect to login page with success message
                $_SESSION["edit_profile_success"] = "Profile Update Successful.";
                header("location: " . ROOT_URL . 'admin/user_profile.php');
                die();
            }            
           

        }
    } 
    
} else {
    header('location: ' . ROOT_URL . 'admin/user_profile.php');
    die();
}
