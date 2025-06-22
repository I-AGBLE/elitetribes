<?php
// Validate the current user
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header('Location: ' . htmlspecialchars(ROOT_URL . 'signin.php', ENT_QUOTES, 'UTF-8'));
    exit;
}
$current_user_id = (int)$_SESSION['user_id'];
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

    <?php if (isset($scrolls) && mysqli_num_rows($scrolls) > 0) : ?>

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

                        <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/profiles.php?id=<?= urlencode($tribesmen['id']) ?>">
                            <div class="user_profile_pic">
                                <img
                                    src="../images/<?= htmlspecialchars(basename($tribesmen['avatar']), ENT_QUOTES, 'UTF-8') ?>"
                                    alt="User's profile picture."
                                    onerror="this.src='../images/default_avatar.png'" />
                            </div>

                            <div class="user_name">
                                <h4>
                                    <?= htmlspecialchars($tribesmen['username'], ENT_QUOTES, 'UTF-8') ?>
                                </h4>
                            </div>

                                <?php if (isset($tribesmen['is_admin']) && $tribesmen['is_admin'] == 1): ?>
                                <div class="admin_flag">
                                    <video autoplay muted loop playsinline>
                                        <source src="../images/admin_flag.webm" type="video/webm">
                                    </video>
                                </div>
                            <?php endif; ?>
                         
                        </a>

                        <div class="user_details_post_time">
                            <div class="post_date">
                                <p>
                                    <?= htmlspecialchars(date("M d, Y", strtotime($scroll['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </div>
                            <div class="post_time">
                                <p>
                                    <?= htmlspecialchars(date("H:i", strtotime($scroll['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="post_text">
                        <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>" style="text-decoration: none; color: inherit;">
                            <p style="margin-bottom: 0;">
                                <?php
                                $text = nl2br(htmlspecialchars($scroll['user_post'], ENT_QUOTES, 'UTF-8'));
                                $maxLength = 500;
                                if (mb_strlen(strip_tags($scroll['user_post'])) > $maxLength) {
                                    echo mb_substr($text, 0, $maxLength);
                                    echo ' <span class="hyperlink" style="margin-top: -.5rem"><br>Read More...</span>';
                                } else {
                                    echo $text;
                                }
                                ?>
                            </p>
                        </a>
                    </div>

                    <?php
                    $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
                    $images = array_map(function($img) {
                        return htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8');
                    }, $images);
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
                                <p id="comment_count"><?= htmlspecialchars($comment_count, ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Comment</p>
                            </div>
                        </div>


                        

                         <?php if (isset($scroll['flagged']) && $scroll['flagged'] == 1): ?>
                            <div class="post_reaction">
                                <div class="post_reaction_icon" id="comment_icon">
                                    <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                        <video autoplay muted loop playsinline>
                                            <source src="../images/flag.webm" type="video/webm">
                                        </video>
                                    </a>
                                </div>

                                <div class="post_reaction_desc" id="flagged_post_desc">
                                    <p>Flagged Post</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile ?>

        </div>

    <?php else : ?>
        <h3>Looks like this user hasn’t posted yet!</h3>
    <?php endif ?>
</div>

<div id="infinite-loader-timeline" class="infinite-loader" style="display:none;text-align:center;margin:1rem 0;">
  <span class="ripple-dot"></span>
  <span class="ripple-dot"></span>
  <span class="ripple-dot"></span>
</div>