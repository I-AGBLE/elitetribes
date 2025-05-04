<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $scroll_id = $_GET['id'];
    $tribesmen_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_comment = $_POST['user_comment'];
        $confirm_human = $_POST['confirm_human'];

        // Basic sanitization (still vulnerable, just an example)
        $scroll_id = mysqli_real_escape_string($connection, $scroll_id);
        $tribesmen_id = mysqli_real_escape_string($connection, $tribesmen_id);
        $user_comment = mysqli_real_escape_string($connection, $user_comment);
        $confirm_human = filter_var($confirm_human, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$scroll_id) {
            $_SESSION['comment'] = 'No Post ID';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        } elseif (!$tribesmen_id) {
            $_SESSION['comment'] = 'No Post Post ID';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        } elseif (!$user_comment) {
            $_SESSION['comment'] = 'Cannot Post Empty Comment!';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        } elseif (!empty($confirm_human)) {
            $_SESSION['comment'] = 'Somethings Are For Humans Only!';
            header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
            exit;
        } else {
            $query = "INSERT INTO comments (scroll_id, tribesmen_id, user_comment) 
                      VALUES ('$scroll_id', '$tribesmen_id', '$user_comment')";

            $result = mysqli_query($connection, $query);

            if ($result) {
                $_SESSION["comment_success"] = "You commented.";
                header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
                exit;
            } else {
                $_SESSION["comment"] = "Unable To Comment!";
                header("Location: " . ROOT_URL . "admin/post_preview.php?id=" . $scroll_id);
                exit;
            }
        }
    }
} else {
    header('Location: ' . ROOT_URL . 'admin/');
    exit;
}
