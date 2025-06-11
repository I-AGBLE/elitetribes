<div class="search_box">
    <center>
        <input type="text" placeholder="Search Following" id="search_following">
    </center>
</div>

<div class='followings'>

<?php
// sanitize id
$id = isset($id) ? (int)$id : 0;



$stmt = mysqli_prepare($connection, "
    SELECT t.id, t.username, t.avatar, 
           (SELECT COUNT(*) FROM followers WHERE followed = t.id) AS followers_count
    FROM followers f
    JOIN tribesmen t ON f.followed = t.id
    WHERE f.follower = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<?php if (mysqli_num_rows($result) > 0): ?>
    <div class="followings">
        <?php while ($followers_row = mysqli_fetch_assoc($result)) :
            $profile_id = (int)$followers_row["id"];
            $username = htmlspecialchars($followers_row['username']);
            $avatar = htmlspecialchars(!empty($followers_row['avatar']) ? $followers_row['avatar'] : 'profile_pic.png');
            $followers_count = (int)$followers_row['followers_count'];
        ?>
            <div class="post">
                <div class="user_details">
                    <a href="<?= ROOT_URL ?>admin/profiles.php?id=<?= $profile_id ?>">
                        <div class="user_profile_pic">
                            <img src="../images/<?= $avatar ?>" alt="<?= $username ?>'s profile picture." />
                        </div>
                        <div class="username">
                            <h4><?= $username ?></h4>
                        </div>

                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>



    
<?php else: ?>
    <p>This user follows no one.</p>
<?php endif; ?>

</div>

