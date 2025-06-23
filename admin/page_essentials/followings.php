<div class="search_box">
    <center>
        <input type="text" placeholder="Search Following" id="search_following">
    </center>
</div>

<div class='followings'>

    <?php
    // Secure session start if needed
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Sanitize and validate $id
    $id = isset($id) ? (int)$id : 0;

    // Prepare and execute the query securely
    $stmt = mysqli_prepare($connection, "
    SELECT t.id, t.username, t.avatar, t.is_admin,
           (SELECT COUNT(*) FROM followers WHERE followed = t.id) AS followers_count
    FROM followers f
    JOIN tribesmen t ON f.followed = t.id
    WHERE f.follower = ?
");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = false;
    }
    ?>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="followings">
            <?php while ($followers_row = mysqli_fetch_assoc($result)) :
                $profile_id = (int)$followers_row["id"];
                $username = htmlspecialchars($followers_row['username'], ENT_QUOTES, 'UTF-8');
                $avatar = !empty($followers_row['avatar']) ? htmlspecialchars($followers_row['avatar'], ENT_QUOTES, 'UTF-8') : 'profile_pic.png';
                $followers_count = (int)$followers_row['followers_count'];
            ?>
                <div class="post">
                    <div class="user_details">
                        <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/profiles.php?id=<?= $profile_id ?>">
                            <div class="user_profile_pic">
                                <img src="../images/<?= $avatar ?>" alt="<?= $username ?>'s profile picture." />
                            </div>
                            <div class="username">
                                <h4><?= $username ?></h4>
                            </div>


                            <?php if (isset($followers_row['is_admin']) && $followers_row['is_admin'] == 1): ?>
                                    <div class="admin_flag">
                                        <img src="../images/admin_flag.gif" alt="Admin Flag" />
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>This user follows no one.</p>
    <?php endif; ?>

</div>