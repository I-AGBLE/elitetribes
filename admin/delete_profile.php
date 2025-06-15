<?php
require_once 'config/database.php';

if (isset($_GET['id'])) {
    // Sanitize and validate id
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    // Only proceed if $id is valid and user is authenticated
    if ($id && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
        // Fetch the user securely
        $query = "SELECT avatar FROM tribesmen WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if user exists
        if ($result && mysqli_num_rows($result) === 1) {
            $tribesmen = mysqli_fetch_assoc($result);

            $image_name = trim($tribesmen["avatar"]);
            $image_path = '../images/' . $image_name;
            // Only delete if not a default image and file exists
            if (
                $image_name &&
                is_file($image_path) &&
                !str_contains($image_name, 'default') &&
                is_writable($image_path)
            ) {
                @unlink($image_path);
            }

            // Delete the user record from the database securely
            $delete_profile_query = "DELETE FROM tribesmen WHERE id = ? LIMIT 1";
            $stmt_del = mysqli_prepare($connection, $delete_profile_query);
            mysqli_stmt_bind_param($stmt_del, "i", $id);
            $delete_profile_result = mysqli_stmt_execute($stmt_del);

            if ($delete_profile_result) {
                // Destroy session if user deleted their own account
                session_unset();
                session_destroy();
                session_start();
                $_SESSION["delete_profile_success"] = "Account Deleted Successfully.";
                header("Location: " . ROOT_URL);
                exit;
            } else {
                $_SESSION["delete_profile"] = "Database Operation Failed!.";
                header('Location: ' . ROOT_URL . 'admin/');
                exit;
            }
        } else {
            $_SESSION["delete_profile"] = "User not found!.";
            header('Location: ' . ROOT_URL . 'admin/');
            exit;
        }
    } else {
        $_SESSION["delete_profile"] = "Unauthorized or invalid user!";
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    }
} else {
    $_SESSION["delete_profile"] = "Operation Failed!";
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}