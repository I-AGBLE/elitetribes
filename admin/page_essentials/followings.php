<div class="search_box">
    <center>
        <input type="text" placeholder="Search Following" id="my__following_box">
    </center>
</div>


<div class='followings'>

    <?php
    // Get the list of users this user is following along with their follower count
    $query = "
    SELECT t.id, t.username, t.avatar, t.id,
           (SELECT COUNT(*) FROM followers WHERE followed = t.id) AS followers_count
    FROM followers f
    JOIN tribesmen t ON f.followed = t.id
    WHERE f.follower = $id
";

    $result = mysqli_query($connection, $query);
    $followers_count = 0;

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $followers_count = $row['followers_count'];
    }
    ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="followings">
            <?php while ($row = mysqli_fetch_assoc($result)) :
                $profile_id = $row["id"];
                $username = htmlspecialchars($row['username']);
                $avatar = !empty($row['avatar']) ? $row['avatar'] : 'profile_pic.png';
                $followers_count = intval($row['followers_count']);

                // set default value for the followers count

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

                            <?php if ($followers_count >= 20): ?>
                                <div class="verified">
                                    <div class="verified_icon">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <div class="verified_desc">
                                        <p>Verified</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>This user is not following anyone yet.</p>
    <?php endif; ?>



</div>