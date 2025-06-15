<?php
require_once 'partials/header.php';


// Sanitize and validate session input
$user_post = '';
$confirm_human = '';
if (isset($_SESSION['add_post_data'])) {
  $user_post = isset($_SESSION['add_post_data']['user_post']) ?
    htmlspecialchars(trim($_SESSION['add_post_data']['user_post']), ENT_QUOTES, 'UTF-8') : '';
  $confirm_human = isset($_SESSION['add_post_data']['confirm_human']) ?
    htmlspecialchars(trim($_SESSION['add_post_data']['confirm_human']), ENT_QUOTES, 'UTF-8') : '';
  unset($_SESSION['add_post_data']);
}

// Fetching open scrolls
$open_scrolls = false;
$open_scrolls_query = "SELECT * FROM scrolls ORDER BY RAND()";
$stmt = $connection->prepare($open_scrolls_query);
if ($stmt) {
  $stmt->execute();
  $open_scrolls = $stmt->get_result();
  $stmt->close();
} else {
  http_response_code(500);
  exit('Database query error');
}

// CSRF token 
if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<main>
  <section class="main_left">
    <!--Update -->
  </section>



  <section class="main_content">
    <?php if (isset($_SESSION['signin_success'])) : ?>
      <div class="alert_message success" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['signin_success'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['signin_success']);
          ?>
        </p>
      </div>

    <?php elseif (isset($_SESSION['add_post'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['add_post'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['add_post']);
          ?>
        </p>
      </div>
      
    <?php elseif (isset($_SESSION['delete_profile'])) : ?>
      <div class="alert_message error" id="alert_message">
        <p>
          <?= htmlspecialchars($_SESSION['delete_profile'], ENT_QUOTES, 'UTF-8');
          unset($_SESSION['delete_profile']);
          ?>
        </p>
      </div>
    <?php endif ?>




    <form action="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/add_post_logic.php" enctype="multipart/form-data" method="POST" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
      <div class="post_input">
        <div class="post_field">
          <textarea name="user_post" placeholder="What's on your mind?"><?= $user_post ?></textarea>
          <div class="post_actions">
            <label for="image-upload" style="cursor: pointer;">
              <i class="fa-solid fa-image" style="font-size: 24px;"></i>
            </label>
            <input type="file" id="image-upload" name="images[]" accept="image/jpeg,image/png,image/gif,image/svg,image/jpg" multiple style="display: none;" />
            <div id="file-names"></div>
            <input type="text" name="confirm_human" class="confirm_human" value="<?= $confirm_human ?>" placeholder="confirm_human" maxlength="100">
            <input type="submit" name="submit" value="Post">
            <style>
              label i {
                font-size: 1.5rem;
                cursor: pointer;
              }

              label i:hover {
                color: var(--color_warning);
              }
            </style>
          </div>
        </div>
      </div>
    </form>



    <section class="dashboard">
      <?php
      include_once 'page_essentials/open_scrolls.php';
      ?>


      <div class="my_timeline" id="my_timeline" style="display: none;">
        <div class="my_dashboard">
          <div class="my_dashboard_title">
            <div class="dashboard_small_titles">
              <div class="my_posts_links">
                <a href="#open_scrolls_contents" id="open_scrolls">Open Scrolls</a>
                <a href="#my_timeline" id="timeline" style="color: var(--color_warning);">My Timeline</a>
              </div>
            </div>
          </div>
        </div>
        <?php
        include_once 'page_essentials/my_timeline.php';
        ?>
      </div>

    </section>
  </section>






  <section class="main_right">
    <!--Update -->
  </section>
</main>



<?php
require_once '../partials/footer.php';
?>