<div class="my_posts_contents" id="my_posts_contents" style="display: block;">

    <div class="my_dashboard">
        <div class="my_dashboard_title">
            <div class="dashboard_small_titles">
                <div class="my_posts_links">
                    <a href="#my_posts" id="my_posts" style="color: var(--color_warning);">My Posts</a>
                    <a href="#feed" id="my_feed">My Timeline</a>
                    <a href="#following" id="my_following">Following</a>
                </div>
            </div>
        </div>
    </div>

    <?php if (mysqli_num_rows($scrolls) > 0) : ?>

        <div class="search_box">
            <center>
                <input type="text" placeholder="Search Posts" id="my_post_search_box">
            </center>
        </div>

        <div class="my_posts">

            <?php while ($scroll = mysqli_fetch_assoc($scrolls)) : ?>

                <div class="post">
                    <div class="user_details">

                        <?php
                        // fetch user details 

                        $tribesmen_id = $scroll['created_by'];
                        $tribesmen_query = "SELECT * FROM tribesmen WHERE id=$tribesmen_id";
                        $tribesmen_result = mysqli_query($connection, $tribesmen_query);
                        $tribesmen = mysqli_fetch_assoc($tribesmen_result);
                        ?>

                        <a href="user_profile.php?id=<?= $tribesmen['id'] ?>">
                            <div class="user_profile_pic">
                                <img
                                    src="../images/<?= htmlspecialchars($tribesmen['avatar']) ?>"
                                    alt="User's profile picture." />
                            </div>

                            <div class="user_name">
                                <h4>
                                    <?= $tribesmen['username'] ?>
                                </h4>
                            </div>

                            <?php if ($tribesmen['followers'] > 20): ?>
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

                        <div class="user_details_post_time">
                            <div class="post_date">
                                <p>
                                    <?= date("M d, Y", strtotime($scroll['created_at'])) ?>

                                </p>
                            </div>
                            <div class="post_time">
                                <p>
                                    <?= date("H:i", strtotime($scroll['created_at'])) ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="post_text">
                            <p>
                        <a href="post_preview.php?id=<?= $scroll['id'] ?>">

                        <?php
                                $text = $scroll['user_post'];
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
                    $images = array_filter(array_map('trim', explode(',', $scroll['images']))); // Remove empty/whitespace-only values
                    if (!empty($images)) :
                    ?>
                        <div class="post_images_container">
                            <div class="post_images">
                                <?php foreach ($images as $image) : ?>
                        <a href="post_preview.php?id=<?= $scroll['id'] ?>">

                                    <img src="../images/<?= htmlspecialchars($image) ?>" alt="Post's image.">
                        </a>
                                
                                    <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="post_reactions">
                        <div class="post_reaction">
                            <div class="post_reaction_icon">
                                <div class="like_icons">
                                    <div class="like_icon">
                                        <i class="fa-regular fa-heart"></i>
                                    </div>

                                    <div class="like_icon_is_clicked">
                                        <i class="fa-regular fa-heart"></i>
                                    </div>
                                </div>

                                <p id="like_count">102</p>
                            </div>

                            <div class="post_reaction_desc">
                                <p>Like</p>
                            </div>
                        </div>


                        <div class="post_reaction">
                            <a href="post_preview.php">
                                <div class="post_reaction_icon" id="comment_icon">
                                    <i class="fa-regular fa-comment" id="comment_icon"></i>
                                    <p id="comment_count">21</p>
                                </div>
                            </a>
                            <div class="post_reaction_desc">
                                <p>Comment</p>
                            </div>
                        </div>

                        <div class="post_reaction">
                            <div class="post_reaction_icon">
                                <i class="fa-solid fa-retweet" id="repost_icon"></i>
                                <p id="repost_count">98</p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Repost</p>
                            </div>
                        </div>

                        <div class="post_reaction">
                            <div class="post_reaction_icon">
                                <i class="fa-solid fa-share" id="share_icon"></i>
                                <p id="share_count">12</p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Share</p>
                            </div>
                        </div>

                        <div class="post_reaction">
                            <div class="post_reaction_icon">
                                <a href="delete_post_logic.php?id=<?= $scroll['id'] ?>">
                                    <i class="fa-solid fa-trash" id="delete_icon"></i>
                                </a>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Delete</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile ?>

        </div>

    <?php else : ?>
        <h3>You got no post yet!</h3>
    <?php endif ?>
</div>