<?php
// Initialize and sanitize the ID
$id = isset($id) ? intval($id) : 0;
$followers_count = 0;

// Use prepared statement to prevent SQL injection
$query = "SELECT COUNT(*) AS followers_count FROM followers WHERE followed = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $followers_count = intval($row['followers_count']);
    }
    
    mysqli_stmt_close($stmt);
}

// Only show verified badge if count exceeds threshold
$verification_threshold = 20;
?>

<?php if ($followers_count > $verification_threshold): ?>
    <div class="verified">
        <div class="verified_icon">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="verified_desc">
            <p>Verified</p>
        </div>
    </div>
<?php endif; ?>