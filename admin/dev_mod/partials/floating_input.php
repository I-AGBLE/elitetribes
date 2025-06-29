<?php



// CSRF protection: generate token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// get input from failed post, sanitize for output
$user_post = isset($_SESSION['add_post_data']['user_post']) ? htmlspecialchars($_SESSION['add_post_data']['user_post'], ENT_QUOTES, 'UTF-8') : null;
$confirm_human = isset($_SESSION['add_post_data']['confirm_human']) ? htmlspecialchars($_SESSION['add_post_data']['confirm_human'], ENT_QUOTES, 'UTF-8') : null;

// if all is fine
unset($_SESSION['add_post_data']);
?>

<form action="<?= ROOT_URL ?>admin/add_post_logic.php" enctype="multipart/form-data" method="POST">
  <!-- CSRF token for security -->
  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

  <div class="floating_input">
    <div class="floating_post_input" style="display: none;">
      <div class="post_field">
        <textarea name="user_post" placeholder="Share your thoughts here!" maxlength="10000"><?= $user_post ?></textarea>

        <div class="post_actions">

          <label for="image-upload" style="cursor: pointer;">
            <i class="fa-solid fa-image" style="font-size: 24px;"></i>
          </label>

          <input type="file" id="image-upload" name="images[]" accept="image/*" multiple style="display: none;" />

          <!-- Where the selected file names will be shown -->
          <div id="file-names-floating-input"></div>

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

<div class="floating_icons">
  <div class="open_floating_input">
    <i class="fa-solid fa-plus"></i>
  </div>

  <div class="close_floating_input" style="display: none;">
    <div class="close">
      <i class="fa-solid fa-minus"></i>
    </div>
  </div>
</div>
</div>