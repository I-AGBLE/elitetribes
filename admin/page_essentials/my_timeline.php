<div class="search_box">
  <center>
    <input type="text" placeholder="Search Timeline" id="my_feed_search_box">
  </center>
</div>

<?php
$user_id = (int) $_SESSION['user_id'];

$feed_query = "
    SELECT s.*, t.username, t.avatar,
           (SELECT COUNT(*) FROM followers WHERE followed = s.created_by) AS author_followers_count
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
            <img src="../images/<?= htmlspecialchars($feed['avatar']) ?>" alt="User's profile picture." />
          </div>

          <div class="user_name">
            <h4><?= htmlspecialchars($feed['username']) ?></h4>
          </div>
        </a>

        <div class="user_details_post_time">
          <div class="post_date">
            <p><?= date("M d, Y", strtotime($feed['created_at'])) ?></p>
          </div>
          <div class="post_time">
            <p><?= date("H:i", strtotime($feed['created_at'])) ?></p>
          </div>
        </div>
      </div>

      <div class="post_text">
        <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $feed['id'] ?>">

          <?php
          $text = nl2br($feed['user_post']);
          $maxLength = 500;
          if (strlen($text) > $maxLength) {
            echo substr($text, 0, $maxLength) . '<p>Read More...</p>';
          } else {
            echo $text;
          }
          ?>
        </a>
      </div>

      <?php
      $images = array_filter(array_map('trim', explode(',', $feed['images'])));
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
        <?php
        $liked = false;
        $like_count = 0;
        $feed_id = $feed['id'];

        if (isset($_SESSION['user_id'])) {
          $tribesmen_id = $_SESSION['user_id'];

          $query_check = "SELECT * FROM likes WHERE scroll_id = $feed_id AND tribesmen_id = $tribesmen_id";
          $like_result = mysqli_query($connection, $query_check);
          if (mysqli_num_rows($like_result) > 0) {
            $liked = true;
          }
        }

        $query_like_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = $feed_id";
        $result_count = mysqli_query($connection, $query_like_count);
        if ($row = mysqli_fetch_assoc($result_count)) {
          $like_count = $row['total_likes'];
        }
        ?>

        <div class="post_reaction">
          <div class="post_reaction_icon">
            <div class="like_icons">
              <div class="like_icon">
                <a href="like_logic.php?id=<?= $feed_id ?>" onclick="saveScroll()">
                  <i class="fa-regular fa-heart <?= $liked ? 'liked' : 'default' ?>" id="like_icon"></i>
                </a>
              </div>
            </div>
            <p id="like_count"><?= $like_count ?></p>
          </div>
          <div class="post_reaction_desc">
            <p>Like</p>
          </div>
        </div>

        <div class="post_reaction">
          <?php
          $comment_count = 0;
          $scroll_id = mysqli_real_escape_string($connection, $feed_id);

          $count_query = "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = '$scroll_id'";
          $count_result = mysqli_query($connection, $count_query);

          if ($count_row = mysqli_fetch_assoc($count_result)) {
            $comment_count = $count_row['comment_count'];
          }
          ?>
          <div class="post_reaction_icon" id="comment_icon">
            <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $feed['id'] ?>">
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
  <?php endwhile; ?>
</div>