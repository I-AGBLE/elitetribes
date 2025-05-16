<div class="search_box">
  <center>
    <input type="text" placeholder="Search Timeline" id="my_feed_search_box">
  </center>
</div>


<?php
$user_id = (int) $_SESSION['user_id'];

$feed_query = "
    SELECT s.*, t.username, t.avatar,
           (SELECT COUNT(*) 
            FROM followers 
            WHERE followed = s.created_by) AS author_followers_count
    FROM scrolls AS s
    INNER JOIN tribesmen AS t ON t.id = s.created_by
    WHERE s.created_by IN (
        SELECT followed FROM followers WHERE follower = $user_id
    )
    ORDER BY s.created_at DESC
";

$result = mysqli_query($connection, $feed_query);
?>


<div class="my_posts">



  <?php while ($feed = mysqli_fetch_assoc($result)) : ?>

    <div class="post">



      <div class="user_details">
      <a href="profiles.php?id=<?= $feed['created_by'] ?>">
          <div class="user_profile_pic">
            <img
              src="../images/<?= htmlspecialchars($feed['avatar']) ?>"
              alt="User's profile picture." />
          </div>

          <div class="user_name">
            <h4><?= $feed['username'] ?></h4>
          </div>

          <?php
          // include 'followers_count.php';
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
        <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $feed['id'] ?>">
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

            <p id="like_count">10ddd2</p>
          </div>

          <div class="post_reaction_desc">
            <p>Like</p>
          </div>
        </div>



        <div class="post_reaction">
          <?php
          // Assuming $feed['id'] is defined and $connection is your DB connection
          $comment_count = 0;

          if (isset($feed['id'])) {
            $scroll_id = mysqli_real_escape_string($connection, $feed['id']);

            // Count comments linked to this feed post
            $count_query = "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = '$scroll_id'";
            $count_result = mysqli_query($connection, $count_query);

            if ($count_result) {
              $count_row = mysqli_fetch_assoc($count_result);
              $comment_count = $count_row['comment_count'];
            }
          }
          ?>
            <div class="post_reaction_icon" id="comment_icon">
              <i class="fa-regular fa-comment" id="comment_icon"></i>
              <p id="comment_count"><?= $comment_count ?></p>
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

    
      </div>
    </div>

  <?php endwhile; ?>
</div>