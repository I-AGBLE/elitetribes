<?php

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once 'partials/header.php';

// Verify user is logged in (if required)
if (empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
  $_SESSION['comment'] = "You must be logged in to view this page";
  header('Location: ' . htmlspecialchars(ROOT_URL . 'signin.php', ENT_QUOTES, 'UTF-8'));
  exit;
}

// fetch scroll if id is inclusive in link
if (isset($_GET['id'])) {
  // sanitize id
  $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
  if (!$id || $id <= 0) {
    header('Location: ' . htmlspecialchars(ROOT_URL . 'admin/', ENT_QUOTES, 'UTF-8'));
    exit;
  }

  // fetch scroll
  $query = "SELECT * FROM scrolls WHERE id=?";
  $stmt = mysqli_prepare($connection, $query);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $scroll = mysqli_fetch_assoc($result);

  // verify scroll exists
  if (!$scroll) {
    header('Location: ' . htmlspecialchars(ROOT_URL . 'admin/', ENT_QUOTES, 'UTF-8'));
    exit;
  }
} else {
  header('Location: ' . htmlspecialchars(ROOT_URL . 'admin/', ENT_QUOTES, 'UTF-8'));
  exit;
}

// fetch user details using prepared statement
$tribesmen_id = (int)$scroll['created_by'];
$tribesmen_query = "SELECT * FROM tribesmen WHERE id=?";
$stmt = mysqli_prepare($connection, $tribesmen_query);
mysqli_stmt_bind_param($stmt, "i", $tribesmen_id);
mysqli_stmt_execute($stmt);
$tribesmen_result = mysqli_stmt_get_result($stmt);
$tribesmen = mysqli_fetch_assoc($tribesmen_result);

// verify tribesmen exists
if (!$tribesmen) {
  header('Location: ' . htmlspecialchars(ROOT_URL . 'admin/', ENT_QUOTES, 'UTF-8'));
  exit;
}




$scroll_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($scroll_id > 0) {
    $cookie_name = 'viewed_scroll_' . $scroll_id;
    $view_expiry = 12 * 60 * 60; // 12 hours

    if (
        (!isset($_COOKIE[$cookie_name])) &&
        (!isset($_SESSION['viewed_scrolls']) || !in_array($scroll_id, $_SESSION['viewed_scrolls']))
    ) {
        $update_views = "UPDATE scrolls SET views = views + 1 WHERE id = ?";
        $stmt = mysqli_prepare($connection, $update_views);
        mysqli_stmt_bind_param($stmt, "i", $scroll_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['viewed_scrolls'][] = $scroll_id;
    }
}


?>



<main>
  <section class="main_left">
    <!--Update -->
  </section>

  <section class="main_content">
    <?php if (isset($_SESSION['comment'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['comment'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['comment']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['comment_success'])) : ?>
      <div class="alert_message success" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['comment_success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['comment_success']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['delete_comment'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['delete_comment'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['delete_comment']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['delete_comment_success'])) : ?>
      <div class="alert_message success" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['delete_comment_success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['delete_comment_success']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['like'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['like'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['like']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['like_success'])) : ?>
      <div class="alert_message success" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['like_success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['like_success']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['share'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['share'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['share']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['share_success'])) : ?>
      <div class="alert_message success" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['share_success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['share_success']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['repost'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['repost'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['repost']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['repost_success'])) : ?>
      <div class="alert_message success" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['repost_success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['repost_success']);
          ?>
        </p>
      </div>
    <?php endif ?>





    <section class="dashboard">
      <div class="my_posts_contents">
        <div class="my_posts">
          <div class="post">
            <div class="user_details">
              <a href="profiles.php?id=<?= htmlspecialchars($tribesmen['id'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="user_profile_pic">
                  <?php
                  $avatarPath = '../images/' . basename($tribesmen['avatar']);
                  if (file_exists($avatarPath)) {
                  ?>
                    <img src="<?= htmlspecialchars($avatarPath, ENT_QUOTES, 'UTF-8') ?>" alt="User's profile picture." />
                  <?php } else { ?>
                    <img src="../images/default-avatar.jpg" alt="Default profile picture." />
                  <?php } ?>
                </div>

                <div class="user_name">
                  <h4><?= htmlspecialchars($tribesmen['username'], ENT_QUOTES, 'UTF-8') ?></h4>
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

            <?php
            $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
            if (!empty($images)) :
            ?>
              <div class="post_images_container">
                <div class="post_images">
                  <?php foreach ($images as $image) :
                    $imagePath = '../images/' . basename($image);
                    if (file_exists($imagePath)) {
                  ?>
                      <img src="<?= htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') ?>" alt="Post's image.">
                  <?php
                    }
                  endforeach; ?>
                </div>
              </div>
            <?php endif; ?>


            <div class="post_text" id="post_text_link">
              <p>
                <?= nl2br($scroll['user_post']) ?>
              </p>
            </div>



            <div class="post_reactions">
              <div class="post_reaction">
                <div class="post_reaction_icon">
                  <div class="like_icons">
                    <?php
                    // Initialize
                    $liked = false;
                    $like_count = 0;
                    $scroll_id = $scroll['id'];

                    if (isset($_SESSION['user_id'])) {
                      $tribesmen_id = $_SESSION['user_id'];

                      // Check if this user has already liked the post
                      $query_check = "SELECT * FROM likes WHERE scroll_id = ? AND tribesmen_id = ?";
                      $stmt = mysqli_prepare($connection, $query_check);
                      mysqli_stmt_bind_param($stmt, "ii", $scroll_id, $tribesmen_id);
                      mysqli_stmt_execute($stmt);
                      $result = mysqli_stmt_get_result($stmt);
                      if (mysqli_num_rows($result) > 0) {
                        $liked = true;
                      }
                    }

                    // Sum of likes for scroll
                    $query_like_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = ?";
                    $stmt = mysqli_prepare($connection, $query_like_count);
                    mysqli_stmt_bind_param($stmt, "i", $scroll_id);
                    mysqli_stmt_execute($stmt);
                    $result_count = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($result_count)) {
                      $like_count = $row['total_likes'];
                    }
                    ?>

                    <div class="like_icon">
                      <a href="like_logic.php?id=<?= htmlspecialchars($scroll['id'], ENT_QUOTES, 'UTF-8') ?>">
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

              <?php
              include 'page_essentials/comment_count.php';
              ?>



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

            <form action="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/comment_logic.php?id=<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" method="post">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
              <div class="comment_input">
                <div class="comment_field">
                  <textarea name="user_comment" placeholder="Share your comment on this post..."></textarea>
                  <input type="text" name="confirm_human" placeholder="confirm_human" class="confirm_human" style="display: none;">
                  <input type="submit" name="Comment" value="Comment">
                </div>
              </div>
            </form>

            <?php
            if (isset($scroll_id)) {
              $query = "SELECT c.*, t.id AS author_id, t.username AS author_name, t.avatar AS author_avatar, t.is_admin AS is_admin
                      FROM comments c
                      JOIN tribesmen t ON c.tribesmen_id = t.id
                      WHERE c.scroll_id = ?
                      ORDER BY c.created_at DESC";

              $stmt = mysqli_prepare($connection, $query);
              mysqli_stmt_bind_param($stmt, "i", $scroll_id);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);

              if ($result && mysqli_num_rows($result) > 0) :
                while ($comment = mysqli_fetch_assoc($result)) :
                  $comment_date = htmlspecialchars(date('M d, y', strtotime($comment['created_at'])), ENT_QUOTES, 'UTF-8');
                  $comment_time = htmlspecialchars(date('H:i', strtotime($comment['created_at'])), ENT_QUOTES, 'UTF-8');
            ?>


                  <div class="comment_section">
                    <div class="comment">
                      <div class="user_details">
                        <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/profiles.php?id=<?= htmlspecialchars($comment['author_id'], ENT_QUOTES, 'UTF-8') ?>">
                          <div class="user_profile_pic">
                            <img src="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>images/<?= htmlspecialchars($comment['author_avatar'], ENT_QUOTES, 'UTF-8') ?>" alt="User's profile picture." />
                          </div>

                          <div class="user_name">
                            <h4><?= $comment['author_name']  ?></h4>
                          </div>

                          <?php if (isset($comment['is_admin']) && $comment['is_admin'] == 1): ?>
                            <div class="admin_flag">
                              <img src="../images/admin_flag.gif" alt="Admin Flag" />
                            </div>
                          <?php endif; ?>
                        </a>

                        <div class="user_details_post_time">
                          <div class="post_date">
                            <p><?= htmlspecialchars($comment_date, ENT_QUOTES, 'UTF-8') ?></p>
                          </div>
                          <div class="post_time">
                            <p><?= htmlspecialchars($comment_time, ENT_QUOTES, 'UTF-8') ?></p>
                          </div>
                        </div>
                      </div>

                      <div class="comment_text">
                        <p><?= nl2br($comment['user_comment']) ?></p>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['tribesmen_id']): ?>
                          <div class="post_reaction">
                            <div class="post_reaction_icon">
                              <a href="delete_comment_logic.php?id=<?= htmlspecialchars($comment['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <i class="fa-solid fa-trash" id="delete_icon"></i>
                              </a>
                            </div>
                            <div class="post_reaction_desc">
                              <p>Delete</p>
                            </div>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

            <?php
                endwhile;
              else :
                echo "<p>No comments yet.</p>";
              endif;
            } else {
              echo "<p>Invalid scroll ID.</p>";
            }
            ?>
          </div>
        </div>
      </div>
    </section>
  </section>

  <section class="main_right">
    <!--Update -->
  </section>
</main>

<?php
require_once 'partials/floating_input.php';
require_once '../partials/footer.php';
?>