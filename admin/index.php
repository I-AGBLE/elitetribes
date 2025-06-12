<?php


include 'partials/header.php';

// Validate and sanitize input from failed post 
$user_post = isset($_SESSION['add_post_data']['user_post']) ? 
    htmlspecialchars($_SESSION['add_post_data']['user_post'], ENT_QUOTES, 'UTF-8') : 
    null;

$confirm_human = isset($_SESSION['add_post_data']['confirm_human']) ? 
    htmlspecialchars($_SESSION['add_post_data']['confirm_human'], ENT_QUOTES, 'UTF-8') : 
    null;

// Clear the session data
unset($_SESSION['add_post_data']);

// Database connection should be established with proper error handling
if (!isset($connection) || !($connection instanceof mysqli)) {
    die("Database connection error");
}

// Prepare statement for fetching scrolls
$open_scrolls_query = "SELECT * FROM scrolls ORDER BY created_at DESC";
$open_scrolls = mysqli_query($connection, $open_scrolls_query);

// Verify query was successful
if (!$open_scrolls) {
    die("Database query error: " . mysqli_error($connection));
}

// CSRF token generation for forms
if (!isset($_SESSION['csrf_token'])) {
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








  <form action="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/add_post_logic.php" enctype="multipart/form-data" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <div class="post_input">
      <div class="post_field">
        <textarea name="user_post" placeholder="Share your thoughts here!"><?= $user_post ?></textarea>

        <div class="post_actions">
          <label for="image-upload" style="cursor: pointer;">
            <i class="fa-solid fa-image" style="font-size: 24px;"></i>
          </label>

          <input type="file" id="image-upload" name="images[]" accept="image/jpeg,image/png,image/gif" multiple style="display: none;" />

          <!-- Where the selected file names will be shown -->
          <div id="file-names"></div>

          <input type="text" name="confirm_human" class="confirm_human" value="<?= $confirm_human ?>" placeholder="confirm_human">

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
    // Use include_once to prevent multiple inclusions
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
include '../partials/footer.php';
?>