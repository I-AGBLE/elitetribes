<?php
// Validate and sanitize user ID
$user_id = filter_var($_SESSION['user_id'] ?? null, FILTER_VALIDATE_INT);
if ($user_id === false || $user_id === null) {
  die("Invalid user ID");
}

// CSRF token generation (for any future forms)
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="search_box">
  <center>
    <input type="text" placeholder="Search Timeline" id="search_box"
      oninput="sanitizeSearchInput(this)">
  </center>
</div>

<?php
// Secure database query with prepared statement
$feed_query = "
    SELECT s.*, t.username, t.avatar,
           (SELECT COUNT(*) FROM followers WHERE followed = s.created_by) AS author_followers_count
    FROM scrolls AS s
    INNER JOIN tribesmen AS t ON t.id = s.created_by
    WHERE s.created_by IN (
        SELECT followed FROM followers WHERE follower = ?
    )
    ORDER BY s.created_at DESC
";

$stmt = mysqli_prepare($connection, $feed_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="my_posts">

  <?php while ($feed = mysqli_fetch_assoc($result)) :
    // Validate feed data
    $feed_id = filter_var($feed['id'], FILTER_VALIDATE_INT);
    if ($feed_id === false) continue;

    $creator_id = filter_var($feed['created_by'], FILTER_VALIDATE_INT);
    if ($creator_id === false) continue;
  ?>
    <div class="post">
      <div class="user_details">
        <a href="profiles.php?id=<?= urlencode($creator_id) ?>">
          <div class="user_profile_pic">
            <img src="../images/<?= htmlspecialchars(basename($feed['avatar']), ENT_QUOTES, 'UTF-8') ?>"
              alt="User's profile picture."
              onerror="this.src='../images/default_avatar.png'" />
          </div>

          <div class="user_name">
            <h4><?= htmlspecialchars($feed['username'], ENT_QUOTES, 'UTF-8') ?></h4>
          </div>
        </a>

        <div class="user_details_post_time">
          <div class="post_date">
            <p><?= htmlspecialchars(date("M d, Y", strtotime($feed['created_at'])), ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="post_time">
            <p><?= htmlspecialchars(date("H:i", strtotime($feed['created_at'])), ENT_QUOTES, 'UTF-8') ?></p>
          </div>
        </div>
      </div>

      <div class="post_text">
        <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($feed_id) ?>">
          <p>
            <?php
            $text = nl2br(htmlspecialchars($feed['user_post'], ENT_QUOTES, 'UTF-8'));
            $maxLength = 500;
            if (mb_strlen(strip_tags($feed['user_post'])) > $maxLength) {
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
      // Secure image handling
      $images = array_filter(array_map('trim', explode(',', $feed['images'])));
      $images = array_map(function ($img) {
        return htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8');
      }, $images);
      if (!empty($images)) :
      ?>
        <div class="post_images_container">
          <div class="post_images">
            <?php foreach ($images as $image) : ?>
              <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($feed_id) ?>">
                <img src="../images/<?= $image ?>" alt="Post's image."
                  onerror="this.style.display='none'">
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="post_reactions">
        <?php
        // Secure like check with prepared statement
        $liked = false;
        $like_count = 0;

        if (isset($_SESSION['user_id'])) {
          $tribesmen_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
          if ($tribesmen_id !== false) {
            $query_check = "SELECT 1 FROM likes WHERE scroll_id = ? AND tribesmen_id = ?";
            $stmt_check = mysqli_prepare($connection, $query_check);
            mysqli_stmt_bind_param($stmt_check, "ii", $feed_id, $tribesmen_id);
            mysqli_stmt_execute($stmt_check);
            $like_result = mysqli_stmt_get_result($stmt_check);

            if ($like_result && mysqli_num_rows($like_result) > 0) {
              $liked = true;
            }
            mysqli_stmt_close($stmt_check);
          }
        }

        // Secure like count with prepared statement
        $query_like_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = ?";
        $stmt_count = mysqli_prepare($connection, $query_like_count);
        mysqli_stmt_bind_param($stmt_count, "i", $feed_id);
        mysqli_stmt_execute($stmt_count);
        $result_count = mysqli_stmt_get_result($stmt_count);

        if ($row = mysqli_fetch_assoc($result_count)) {
          $like_count = (int)$row['total_likes'];
        }
        mysqli_stmt_close($stmt_count);
        ?>

        <div class="post_reaction">
          <div class="post_reaction_icon">
            <div class="like_icons">
              <div class="like_icon">
                <a href="like_logic.php?id=<?= urlencode($feed_id) ?>&csrf=<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                  <i class="fa-regular fa-heart <?= $liked ? 'liked' : 'default' ?>" id="like_icon"></i>
                </a>
              </div>
            </div>
            <p id="like_count"><?= htmlspecialchars($like_count, ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="post_reaction_desc">
            <p>Like</p>
          </div>
        </div>

        <div class="post_reaction">
          <?php
          // Secure comment count with prepared statement
          $comment_count = 0;

          $count_query = "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = ?";
          $stmt_comment = mysqli_prepare($connection, $count_query);
          mysqli_stmt_bind_param($stmt_comment, "i", $feed_id);
          mysqli_stmt_execute($stmt_comment);
          $count_result = mysqli_stmt_get_result($stmt_comment);

          if ($count_row = mysqli_fetch_assoc($count_result)) {
            $comment_count = (int)$count_row['comment_count'];
          }
          mysqli_stmt_close($stmt_comment);
          ?>
          <div class="post_reaction_icon" id="comment_icon">
            <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($feed_id) ?>">
              <i class="fa-regular fa-comment" id="comment_icon"></i>
            </a>
            <p id="comment_count"><?= htmlspecialchars($comment_count, ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="post_reaction_desc">
            <p>Comment</p>
          </div>
        </div>


        <?php if (isset($feed['flagged']) && $feed['flagged'] == 1): ?>
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
  <?php endwhile; ?>

  <?php mysqli_stmt_close($stmt); ?>
</div>

<div id="infinite-loader-timeline" class="infinite-loader" style="display:none;text-align:center;margin:1rem 0;">
  <span class="ripple-dot"></span>
  <span class="ripple-dot"></span>
  <span class="ripple-dot"></span>
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