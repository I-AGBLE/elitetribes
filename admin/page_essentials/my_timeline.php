





<div class="search_box">
        <center>
          <input type="text" placeholder="Search Timeline" id="my_feed_search_box">
        </center>
      </div>


      <?php


      $user_id = (int) $_SESSION['user_id'];

      $feed_query = "SELECT s.*, t.username, t.avatar, 
                      (SELECT COUNT(*) 
                       FROM followers 
                       WHERE followed = s.created_by) AS author_followers_count
               FROM scrolls AS s
               INNER JOIN followers AS f ON s.created_by = f.followed
               INNER JOIN tribesmen AS t ON t.id = s.created_by
               WHERE f.follower = $user_id
               ORDER BY s.created_at DESC";
      $result = mysqli_query($connection, $feed_query);

      ?>
      <div class="my_posts">

        <?php while ($feed = mysqli_fetch_assoc($result)) : ?>

          <div class="post">


            <div class="user_details">
              <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $feed['id'] ?>">
                <div class="user_profile_pic">
                  <img
                    src="../images/<?= htmlspecialchars($feed['avatar']) ?>"
                    alt="User's profile picture." />
                </div>

                <div class="user_name">
                  <h4><?= $feed['username'] ?></h4>
                </div>

                <?php 
                        include 'followers_count.php';
                    ?>
              </a>

              <div class="user_details_post_time">
                <div class="post_date">
                  <p>
                    <?= date("M d, Y", strtotime($feed['created_at'])) ?>

                  </p>
                </div>
                <div class="post_time">
                  <p>
                    <?= date("H:i", strtotime($feed['created_at'])) ?>

                  </p>
                </div>
              </div>
            </div>

            <div class="post_text">
              <a href="post_preview.php">
                <p>
                  <?= $feed['user_post'] ?>
                </p>
              </a>
            </div>


            <?php
            $images = array_filter(array_map('trim', explode(',', $feed['images']))); // Remove empty/whitespace-only values
            if (!empty($images)) :
            ?>
              <div class="post_images_container">
                <div class="post_images">
                  <?php foreach ($images as $image) : ?>
                    <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $feed['id'] ?>">

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
            </div>
          </div>

        <?php endwhile; ?>
      </div>