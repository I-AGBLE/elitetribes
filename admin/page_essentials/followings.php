<div class="search_box">
    <center>
        <input type="text" placeholder="Search Following" id="my_following_box">
    </center>
</div>


<div class='followings'>


<?php

$query = "
    SELECT t.id, t.username, t.avatar, 
           (SELECT COUNT(*) FROM followers WHERE followed = t.id) AS followers_count
    FROM followers f
        JOIN tribesmen t ON f.followed = t.id
    WHERE f.follower = $id
";

$result = mysqli_query($connection, $query);
?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="followings">
            <?php while ($followers_row = mysqli_fetch_assoc($result)) :
                $profile_id = $followers_row["id"];
                $username = htmlspecialchars($followers_row['username']);
                $avatar = !empty($followers_row['avatar']) ? $followers_row['avatar'] : 'profile_pic.png';
                $followers_count = intval($followers_row['followers_count']);
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