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


    <?php if (mysqli_num_rows($open_scrolls) > 0) : ?>

        <div class="search_box">
            <center>
                <input type="text" placeholder="Search Scrolls" id="search_box_for_open_scrolls">
            </center>
        </div>






        <div class="my_posts">

            <?php while ($scroll = mysqli_fetch_assoc($open_scrolls)): ?>

                <div class="post">
                    <div class="user_details">

                        <?php
                        // fetch user details 
                        $tribesmen_id = $scroll['created_by'];
                        $tribesmen_query = "SELECT * FROM tribesmen WHERE id=$tribesmen_id";
                        $tribesmen_result = mysqli_query($connection, $tribesmen_query);
                        $tribesmen = mysqli_fetch_assoc($tribesmen_result);
                        ?>
                        <a href="<?= ROOT_URL ?>admin/profiles.php?id=<?= $tribesmen['id'] ?>">
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

                           
                    <?php 
                        include 'followers_count.php';
                    ?>
                    
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
                            <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $scroll['id'] ?>">

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
                                    <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $scroll['id'] ?>">

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
                            <div class="post_reaction_icon">
                            <i class="fa-regular fa-comment" id="comment_icon"></i>
                                <p id="comment_count">98</p>
                            </div>
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
                    </div>
                </div>
            <?php endwhile ?>


        </div>



    <?php else : ?>
        <h3>Be first to post a scroll on eliteTribe.</h3>
    <?php endif ?>

</div>