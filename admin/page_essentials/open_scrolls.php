<?php
// CSRF token generation (for any future forms)
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>




<div class="open_scrolls_contents" id="open_scrolls_contents" style="display: block;">
    <div class="my_dashboard">
        <div class="my_dashboard_title">
            <div class="dashboard_small_titles">
                <div class="my_posts_links">
                    <a href="#open_scrolls_contents" id="open_scrolls" style="color: var(--color_warning);">Open Scrolls</a>
                    <a href="#my_timeline" id="timeline">My Timeline</a>
                </div>
            </div>
        </div>
    </div>



    <?php if (isset($open_scrolls) && mysqli_num_rows($open_scrolls) > 0) : ?>
        <div class="search_box">
            <center>
                <input type="text" placeholder="Search Scrolls" id="search_box"
                    oninput="sanitizeSearchInput(this)">
            </center>
        </div>


        <div class="my_posts">
            <?php while ($scroll = mysqli_fetch_assoc($open_scrolls)):
                // Validate scroll data
                $scroll_id = filter_var($scroll['id'], FILTER_VALIDATE_INT);
                if ($scroll_id === false) continue;

                $tribesmen_id = filter_var($scroll['created_by'], FILTER_VALIDATE_INT);
                if ($tribesmen_id === false) continue;
            ?>

                <div class="post">
                    <div class="user_details">
                        <?php
                        // Securely fetch user details with prepared statement
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
                                    <?= $tribesmen['username'] ?>
                                </h4>
                            </div>

                            <?php if (isset($tribesmen['is_admin']) && $tribesmen['is_admin'] == 1): ?>
                                <div class="admin_flag">
                                    <img src="../images/admin_flag.gif" alt="Admin Flag" />
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
                                $text = nl2br($scroll['user_post']);
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
                    //  post image handling
                    $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
                    $images = array_map(function ($img) {
                        return htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8');
                    }, $images);
                    if (!empty($images)) :
                    ?>

                        <div class="post_images_container ">
                            <div class="post_images">
                                <?php foreach ($images as $image) : ?>
                                    <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>">
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
                            // comment count query
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
                                <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                    <i class="fa-regular fa-comment" id="comment_icon"></i>
                                </a>
                                <p id="comment_count"><?= htmlspecialchars($comment_count, ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Comment</p>
                            </div>
                        </div>





                        <div class="post_reaction">
                            <div class="post_reaction_icon" id="comment_icon">
                                <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                    <i class="fa-regular fa-eye" id="view_icon"></i>
                                </a>
                                <p id="view_count"><?= htmlspecialchars($scroll['views'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Views</p>
                            </div>
                        </div>




                        <?php if (isset($scroll['flagged']) && $scroll['flagged'] == 1): ?>
                            <div class="post_reaction">
                                <div class="post_reaction_icon" id="comment_icon">
                                    <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                        <img src="../images/flagged.gif" alt="Flagged Post" />
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



        <div id="infinite-loader-open" class="infinite-loader" style="display:none;text-align:center;margin:1rem 0;">
            <span class="ripple-dot"></span>
            <span class="ripple-dot"></span>
            <span class="ripple-dot"></span>
        </div>



    <?php else : ?>
        <h3>Be the first to share a post for the elite<span>Tribes</span></h3>
    <?php endif ?>
</div>

<script>
    // Client-side input sanitization
    function sanitizeSearchInput(input) {
        // Remove potentially harmful characters
        input.value = input.value.replace(/[<>"'`\\]/g, '');

        // Limit length if needed
        if (input.value.length > 100) {
            input.value = input.value.substring(0, 100);
        }
    }
</script>