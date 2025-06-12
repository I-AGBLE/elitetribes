<?php
require 'config/database.php';


// if submit button is clicked
if (isset($_POST['submit'])) {

    //sanitize user input 
    $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
    $telephone = filter_var($_POST['telephone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
    $about = filter_var($_POST['about'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];
    $confirm_human = filter_var($_POST['confirm_human'], FILTER_SANITIZE_SPECIAL_CHARS);

    // validate user input 
    if (!$username) {
        $_SESSION['signup'] = 'Enter Username!';
    } else if (!$telephone) {
        $_SESSION['signup'] = 'Enter Your Telephone Number!';
    } else if (!$gender) {
        $_SESSION['signup'] = 'Select Gender!';
    } else if (!$about) {
        $_SESSION['signup'] = 'Tell Us About Yourself!';
    } else if (!$email) {
        $_SESSION['signup'] = 'Enter Valid Email!';
    } else if (strlen($password) < 4 || strlen($confirm_password) < 4) {
        $_SESSION['signup'] = 'Password Should Be More Than 4 Characters!';
    } elseif (!empty($confirm_human)) {
        $_SESSION['signup'] = 'Somethings Are For Humans Only!';
    } else if (!$avatar['name']) {
        $_SESSION['signup'] = 'Select Profile Picture!';
    } else {
        // check if password & confirm_password match
        if ($password != $confirm_password) {
            $_SESSION['signup'] = 'Passwords Do Not Match!';
        } else {
            // hash password 
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // check if username or telephone exists
            $tribesmen_check_query = "SELECT * FROM tribesmen WHERE username='$username' OR telephone='$telephone'";
            $tribesmen_check_result = mysqli_query($connection, $tribesmen_check_query);

            // Check availability of username or telephone
            if (mysqli_num_rows($tribesmen_check_result) > 0) {
                $_SESSION["signup"] = "Username or Telephone Unavailable!";
            } else {

                // update avatar name for insertion 
                $time = time();
                $avatar_name = $time . $avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path = 'images/' . $avatar_name;

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
                        $_SESSION['signup'] = 'Image Should Be Less Than 5mb!';
                    }
                } else {
                    // image format not allowed
                    $_SESSION['signup'] = 'Image Should Either Be a JPG, JPEG, Or PNG File!';
                }
            }
        }
    }

    // if registration failed, redirect to signup page 
    if (isset($_SESSION['signup'])) {

        // pass data back to signup form if registration fails 
        $_SESSION['signup_data'] = $_POST;
        header('location: ' . ROOT_URL . 'signup.php');
        die();
    } else {
        // insert data into db
        $insert_tribesmen_query = "INSERT INTO tribesmen (username, telephone,
        gender, about, email, password,  avatar, is_admin)
        VALUES('$username', '$telephone', '$gender', '$about', '$email', '$hashed_password', 
        '$avatar_name', 0)";

        $insert_tribesmen_result = mysqli_query($connection, $insert_tribesmen_query);

        // if all is fine 
        if (!mysqli_errno($connection)) {
            // redirect to login page with success message
            $_SESSION["signup_success"] = "Registration Successful. Login!";
            header("location: " . ROOT_URL );
            die();
        }
    }
} else {
    // keep user on sign up page 
    header('location: ' . ROOT_URL . 'signup.php');
    die();
}
