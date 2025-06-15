<?php
require_once '../config/database.php';


if (isset($_POST['submit'])) {
    // CSRF Protection
    if (
        empty($_POST['csrf_token']) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $_SESSION['edit_profile'] = 'Invalid session!';
        header('Location: ' . ROOT_URL . 'admin/user_profile.php');
        exit();
    }

    // Sanitize and validate user input
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $previous_avatar = filter_var($_POST['previous_avatar'], FILTER_SANITIZE_SPECIAL_CHARS);
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $telephone = trim(filter_var($_POST['telephone'], FILTER_SANITIZE_NUMBER_INT));
    $gender = filter_var($_POST['gender'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $about = trim(filter_var($_POST['about'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $avatar = $_FILES['avatar'];
    $confirm_human = isset($_POST['confirm_human']) ? filter_var($_POST['confirm_human'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';

    // Validate input lengths and patterns
    if (!$id || $id <= 0) {
        $_SESSION['edit_profile'] = 'Invalid user ID!';
    } elseif (empty($username) || strlen($username) > 30) {
        $_SESSION['edit_profile'] = 'Username must be between 1-30 characters!';
    } elseif (empty($telephone) || !preg_match('/^[0-9]{10,15}$/', $telephone)) {
        $_SESSION['edit_profile'] = 'Enter a valid telephone number (10-15 digits)!';
    } elseif (empty($gender) || !in_array($gender, ['Male', 'Female', 'Non-binary', 'Prefer not to say'])) {
        $_SESSION['edit_profile'] = 'Select a valid gender!';
    } elseif (empty($about) || strlen($about) > 500) {
        $_SESSION['edit_profile'] = 'About section must be 1-500 characters!';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $_SESSION['edit_profile'] = 'Enter a valid email (max 100 characters)!';
    } elseif (!empty($confirm_human)) {
        $_SESSION['edit_profile'] = 'Invalid form submission!';
    } else {
        // Check if username already exists
        $check_username_query = "SELECT 1 FROM tribesmen WHERE username = ? AND id != ? LIMIT 1";
        $stmt = mysqli_prepare($connection, $check_username_query);
        mysqli_stmt_bind_param($stmt, "si", $username, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $_SESSION['edit_profile'] = 'Username Unavailable!';
        }
        mysqli_stmt_close($stmt);

        // Check if telephone already exists 
        if (empty($_SESSION['edit_profile'])) {
            $check_telephone_query = "SELECT 1 FROM tribesmen WHERE telephone = ? AND id != ? LIMIT 1";
            $stmt = mysqli_prepare($connection, $check_telephone_query);
            mysqli_stmt_bind_param($stmt, "si", $telephone, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $_SESSION['edit_profile'] = 'Telephone Number Unavailable!';
            }
            mysqli_stmt_close($stmt);
        }
    }

    // If no errors, proceed with avatar and database update
    if (empty($_SESSION['edit_profile'])) {
        $avatar_to_insert = $previous_avatar;

        // If new avatar was uploaded
        if (!empty($avatar['name']) && $avatar['error'] === UPLOAD_ERR_OK) {
            $previous_avatar_path = '../images/' . basename($previous_avatar);

            // Delete previous avatar if it exists and isn't a default
            if (
                file_exists($previous_avatar_path) &&
                !str_contains($previous_avatar, 'default') &&
                is_writable($previous_avatar_path)
            ) {
                @unlink($previous_avatar_path);
            }

            $time = time();
            $avatar_name = $time . '_' . basename($avatar['name']);
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = '../images/' . $avatar_name;

            $allowed_files = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];
            $extension = strtolower(pathinfo($avatar_name, PATHINFO_EXTENSION));

            if (in_array($extension, $allowed_files)) {
                // Verify image is actually an image
                $image_info = @getimagesize($avatar_tmp_name);
                if ($image_info === false) {
                    $_SESSION['edit_profile'] = 'Uploaded file is not a valid image!';
                } elseif ($avatar['size'] < 5000000) { // 5MB limit
                    // Create directory if it doesn't exist
                    if (!file_exists('../images')) {
                        mkdir('../images', 0755, true);
                    }

                    // Secure file upload
                    if (move_uploaded_file($avatar_tmp_name, $avatar_destination_path)) {
                        $avatar_to_insert = $avatar_name;
                    } else {
                        $_SESSION['edit_profile'] = 'Failed to upload image!';
                    }
                } else {
                    $_SESSION['edit_profile'] = 'Image should be less than 5MB!';
                }
            } else {
                $_SESSION['edit_profile'] = 'Only JPG, JPEG, Webp, SVG or PNG files are allowed!';
            }
        } elseif (!empty($avatar['name']) && $avatar['error'] !== UPLOAD_ERR_NO_FILE) {
            $_SESSION['edit_profile'] = 'Error uploading image!';
        }

        // Proceed with update if no errors
        if (empty($_SESSION['edit_profile'])) {
            $update_tribesmen_query = "UPDATE tribesmen SET 
                username = ?, 
                telephone = ?, 
                gender = ?, 
                about = ?, 
                email = ?, 
                avatar = ? 
                WHERE id = ? 
                LIMIT 1";

            $stmt = mysqli_prepare($connection, $update_tribesmen_query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssssssi", $username, $telephone, $gender, $about, $email, $avatar_to_insert, $id);
                $update_tribesmen_result = mysqli_stmt_execute($stmt);

                if ($update_tribesmen_result) {
                    $_SESSION["edit_profile_success"] = "Profile Update Successful.";
                    mysqli_stmt_close($stmt);
                    header("Location: " . ROOT_URL . 'admin/user_profile.php');
                    exit();
                } else {
                    $_SESSION['edit_profile'] = 'Database update failed!';
                    mysqli_stmt_close($stmt);
                    header("Location: " . ROOT_URL . 'admin/user_profile.php');
                }
            } else {
                $_SESSION['edit_profile'] = 'Database error!';
                header("Location: " . ROOT_URL . 'admin/user_profile.php');
            }
        }
    }

    // If there's an error, redirect back
    if (!empty($_SESSION['edit_profile'])) {
        header('Location: ' . ROOT_URL . 'admin/edit_profile.php?id=' . urlencode($id));
        exit();
    }
} else {
    header('Location: ' . ROOT_URL . 'admin/user_profile.php');
    exit();
}
