<?php
// Initialize and sanitize the ID
$id = isset($id) ? (int)$id : 0;
$followers_count = 0;

// Use prepared statement to prevent SQL injection
$query = "SELECT COUNT(*) AS followers_count FROM followers WHERE followed = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && ($row = mysqli_fetch_assoc($result))) {
        $followers_count = (int)$row['followers_count'];
    }

    mysqli_stmt_close($stmt);
}

