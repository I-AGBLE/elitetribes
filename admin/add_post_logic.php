<?php
require_once 'config/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Only process if submit button is clicked
if (isset($_POST['submit'])) {
    // CSRF protection
    if (
        !isset($_POST['csrf_token']) ||
        !isset($_SESSION['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        $_SESSION['add_post'] = 'Invalid  token.';
        header("Location: " . ROOT_URL . "admin/");
        exit;
    }

    // Validate user session
    if (empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
        $_SESSION['add_post'] = 'You must be logged in to post';
        header("Location: " . ROOT_URL);
        exit;
    }

    $created_by = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
    if (!$created_by || $created_by <= 0) {
        $_SESSION['add_post'] = 'Invalid user session';
        header("Location: " . ROOT_URL . "admin/");
        exit;
    }

    $user_post = trim($_POST['user_post'] ?? '');
    $confirm_human = trim($_POST['confirm_human'] ?? '');

    // Basic content validation
    $user_post = filter_var($user_post, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (mb_strlen($user_post) > 5000) {
        $_SESSION['add_post'] = 'Post content is too long (max 5000 characters)';
        header("Location: " . ROOT_URL . "admin/");
        exit;
    }

    // Validate post input
    if (empty($user_post) && empty($_FILES['images']['name'][0])) {
        $_SESSION['add_post'] = 'Cannot Share Empty Post!';
    } elseif (!empty($confirm_human)) {
        $_SESSION['add_post'] = 'Operation Failed!';
    } else {
        $time = time();
        $uploaded_images = [];
        $max_file_size = 5 * 1024 * 1024; // 5MB
        $allowed_extensions = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'];	
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg', 'image/webp', 'image/jpg'];

        // Process uploaded files if any
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $name) {
                if (empty($name)) continue;

                $image_tmp_name = $_FILES['images']['tmp_name'][$key];
                $image_size = $_FILES['images']['size'][$key];
                $image_error = $_FILES['images']['error'][$key];
                $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                // Error check
                if ($image_error !== UPLOAD_ERR_OK) {
                    $_SESSION['add_post'] = 'File upload error';
                    break;
                }

                // Extension check
                if (!in_array($extension, $allowed_extensions)) {
                    $_SESSION['add_post'] = 'Image must be in jpg, png, webp, svg, or jpeg format!';
                    break;
                }

                // Size check
                if ($image_size > $max_file_size) {
                    $_SESSION['add_post'] = 'File size should be less than 5MB!';
                    break;
                }

                // MIME type check
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $image_tmp_name);
                finfo_close($finfo);

                if (!in_array($mime_type, $allowed_mimes)) {
                    $_SESSION['add_post'] = 'Invalid file type!';
                    break;
                }

                // Check if file is a real image
                $image_info = @getimagesize($image_tmp_name);
                if ($image_info === false) {
                    $_SESSION['add_post'] = 'Uploaded file is not a valid image!';
                    break;
                }

                // Generate unique filename
                $image_name = $time . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $image_destination_path = dirname(__DIR__) . '/images/' . $image_name;

                if (move_uploaded_file($image_tmp_name, $image_destination_path)) {
                    $uploaded_images[] = $image_name;
                } else {
                    $_SESSION['add_post'] = 'Failed to upload image';
                    break;
                }
            }
        }

        // Only proceed if no errors occurred during file upload
        if (!isset($_SESSION['add_post'])) {
            // Use prepared statements to prevent SQL injection
            if (!empty($uploaded_images)) {
                $image_names = implode(',', $uploaded_images);

                $query = "INSERT INTO scrolls (`user_post`, `images`, `created_by`) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "ssi", $user_post, $image_names, $created_by);
            } else {
                $query = "INSERT INTO scrolls (`user_post`, `created_by`) VALUES (?, ?)";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "si", $user_post, $created_by);
            }

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION["add_post_success"] = "Post Added Successfully.";
                header("Location: " . ROOT_URL . "admin/user_profile.php#my_posts");
                exit;
            } else {
                $_SESSION["add_post"] = "Scroll Upload Failed!";
                error_log("Database error: " . mysqli_error($connection));
            }

            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_SESSION["add_post"])) {
        header("Location: " . ROOT_URL . "admin/");
        exit;
    }
} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}