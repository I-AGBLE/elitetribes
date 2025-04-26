<?php

// get input from failed post 
$user_post = $_SESSION['add_post_data']['user_post'] ?? null;
$confirm_human = $_SESSION['add_post_data']['confirm_human'] ?? null;;


// if all is fine
unset($_SESSION['add_post_data']);



?>



<form action="<?= ROOT_URL ?>admin/add_post_logic.php" enctype="multipart/form-data" method="POST">



  <div class="floating_input">
    <div class="floating_post_input" style="display: none;">
      <div class="post_field">
        <textarea name="user_post" placeholder="Share your thoughts here!"><?= htmlspecialchars($user_post) ?></textarea>

        <div class="post_actions">

          <label for="image-upload">
            <i class="fa-solid fa-image"></i> </label>
          <input type="file" id="image-upload" name="images[]" accept="image/*" multiple style="display: none;" />

          <input type="text" name="confirm_human" value="<?= $confirm_human ?>" placeholder="confirm_human">

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