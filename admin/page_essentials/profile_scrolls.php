<?php

// Validate the current user 
$current_user_id = $_SESSION['user_id'];
?>

<div class="my_posts_contents" id="my_posts_contents" style="display: block;">

    <div class="my_dashboard">
        <div class="my_dashboard_title">
            <div class="dashboard_small_titles">
                <div class="my_posts_links">
                    <a href="#my_posts" id="my_posts" style="color: var(--color_warning);">Posts</a>
                    <a href="#following" id="my_following">Following</a>
                </div>
            </div>
        </div>
    </div>

    <?php if (mysqli_num_rows($scrolls) > 0) : ?>

        <div class="search_box">
            <center>
                <input type="text" id="search_box" placeholder="Search Posts" oninput="sanitizeSearchInput(this)">
            </center>
        </div>



        <div class="my_posts">

            <?php while ($scroll = mysqli_fetch_assoc($scrolls)) :
                // Validate scroll data before use
                $scroll_id = filter_var($scroll['id'], FILTER_VALIDATE_INT);
                if ($scroll_id === false) continue;

                $tribesmen_id = filter_var($scroll['created_by'], FILTER_VALIDATE_INT);
                if ($tribesmen_id === false) continue;
            ?>

                <div class="post">
                    <div class="user_details">
                        <?php
                        // Securely fetch user details using prepared statements
                        $tribesmen_query = "SELECT * FROM tribesmen WHERE id=?";
                        $stmt = mysqli_prepare($connection, $tribesmen_query);
                        mysqli_stmt_bind_param($stmt, "i", $tribesmen_id);
                        mysqli_stmt_execute($stmt);
                        $tribesmen_result = mysqli_stmt_get_result($stmt);
                        $tribesmen = mysqli_fetch_assoc($tribesmen_result);
                        mysqli_stmt_close($stmt);

                        if (!$tribesmen) continue; // Skip if user not found
                        ?>

                        <a href="<?= htmlspecialchars(ROOT_URL) ?>admin/profiles.php?id=<?= urlencode($tribesmen['id']) ?>">
                            <div class="user_profile_pic">
                                <img
                                    src="../images/<?= htmlspecialchars(basename($tribesmen['avatar'])) ?>"
                                    alt="User's profile picture."
                                    onerror="this.src='../images/default_avatar.png'" />
                            </div>

                            <div class="user_name">
                                <h4>
                                    <?= $tribesmen['username'] ?>
                                </h4>
                            </div>

                            <?php
                            // Securely count followers using prepared statement
                            $query = "SELECT COUNT(*) AS followers_count FROM followers WHERE followed = ?";
                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, "i", $tribesmen_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $followers_count = 0;

                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $followers_count = (int)$row['followers_count'];
                            }
                            mysqli_stmt_close($stmt);
                            ?>


                        </a>

                        <div class="user_details_post_time">
                            <div class="post_date">
                                <p>
                                    <?= htmlspecialchars(date("M d, Y", strtotime($scroll['created_at']))) ?>
                                </p>
                            </div>
                            <div class="post_time">
                                <p>
                                    <?= htmlspecialchars(date("H:i", strtotime($scroll['created_at']))) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="post_text">
                        <p>
                            <a href="post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                <?php
                                $text = nl2br($scroll['user_post']);
                                $maxLength = 500;
                                if (strlen($text) > $maxLength) {
                                    echo substr($text, 0, $maxLength) . '<p>Read More...</p>';
                                } else {
                                    echo $text;
                                }
                                ?>
                            </a>
                        </p>
                    </div>

                    <?php
                    $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
                    $images = array_map('htmlspecialchars', array_map('basename', $images));
                    if (!empty($images)) :
                    ?>
                        <div class="post_images_container">
                            <div class="post_images">
                                <?php foreach ($images as $image) : ?>
                                    <a href="post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                        <img src="../images/<?= $image ?>" alt="Post's image."
                                            onerror="this.style.display='none'">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="post_reactions">
                        <?php
                        include 'page_essentials/like_n_like_count.php';
                        ?>

                        <div class="post_reaction">
                            <?php
                            // Securely count comments using prepared statement
                            $count_query = "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = ?";
                            $stmt = mysqli_prepare($connection, $count_query);
                            mysqli_stmt_bind_param($stmt, "i", $scroll_id);
                            mysqli_stmt_execute($stmt);
                            $count_result = mysqli_stmt_get_result($stmt);
                            $comment_count = 0;

                            if ($count_result) {
                                $count_row = mysqli_fetch_assoc($count_result);
                                $comment_count = (int)$count_row['comment_count'];
                            }
                            mysqli_stmt_close($stmt);
                            ?>
                            <div class="post_reaction_icon" id="comment_icon">
                                <a href="post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                    <i class="fa-regular fa-comment" id="comment_icon"></i>
                                </a>
                                <p id="comment_count"><?= $comment_count ?></p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Comment</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile ?>

        </div>

    <?php else : ?>
        <h3>This user has no post!</h3>
    <?php endif ?>
</div>