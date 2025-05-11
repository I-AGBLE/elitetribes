<?php
$query = "
SELECT COUNT(*) AS followers_count 
FROM followers 
WHERE followed = $id
";

$result = mysqli_query($connection, $query);
$followers_count = 0;

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $followers_count = $row['followers_count'];
}
?>


<?php if ($followers_count > 1): ?>
    <div class="verified">
        <div class="verified_icon">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="verified_desc">
            <p>Verified</p>
        </div>
    </div>
<?php endif; ?>
