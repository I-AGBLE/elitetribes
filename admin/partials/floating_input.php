<?php
// Safely extract and sanitize session input
$user_post = isset($_SESSION['add_post_data']['user_post'])
  ? htmlspecialchars($_SESSION['add_post_data']['user_post'], ENT_QUOTES, 'UTF-8')
  : '';

$confirm_human = isset($_SESSION['add_post_data']['confirm_human'])
  ? htmlspecialchars($_SESSION['add_post_data']['confirm_human'], ENT_QUOTES, 'UTF-8')
  : '';

// Unset session data to avoid reuse or leaking
unset($_SESSION['add_post_data']);

// CSRF Protection: generate token if not present
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<form action="<?= htmlspecialchars(ROOT_URL . 'admin/add_post_logic.php', ENT_QUOTES, 'UTF-8') ?>" enctype="multipart/form-data" method="POST" autocomplete="off">
  <div class="floating_input">
    <div class="floating_post_input" style="display: none;">
      <div class="post_field">

        <!-- Escape any special characters in user input -->
        <textarea name="user_post" placeholder="What's on your mind?"  maxlength="10000"><?= $user_post ?></textarea>

        <div class="post_actions">

          <!-- Accessible image upload -->
          <label for="image-upload" style="cursor: pointer;">
            <i class="fa-solid fa-image" style="font-size: 24px;"></i>
          </label>

          <input
            type="file"
            id="image-upload"
            name="images[]"
            accept="image/png, image/jpeg, image/jpg,, image/webp, image/webp"
            multiple
            style="display: none;" />

          <!-- Area to show file names (handled via JS) -->
          <div id="file-names-floating-input"></div>

         
          <input
            type="text"
            name="confirm_human"
            class="confirm_human"
            value="<?= $confirm_human ?>"
            placeholder="confirm_human"
            autocomplete="off"
            style="display:none;">

          <!-- CSRF token (hidden) -->
          <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>" />

          <input
            type="submit"
            name="submit"
            value="Post">

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



<!-- Floating button icons (unchanged) -->
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