<?php
include 'partials/header.php';


// get input from failed post 
$user_post = $_SESSION['add_post_data']['user_post'] ?? null;
$confirm_human = $_SESSION['add_post_data']['confirm_human'] ?? null;;

// if all is fine
unset($_SESSION['add_post_data']);




// fetch data from scrolls for open_scrolls page 
$open_scrolls_query = "SELECT * FROM scrolls ORDER BY created_at DESC";
$open_scrolls = mysqli_query($connection, $open_scrolls_query);

?>





<main>
  <?php if (isset($_SESSION['signin_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['signin_success'];
        unset($_SESSION['signin_success']);
        ?>
      </p>
    </div>

  <?php elseif (isset($_SESSION['add_post'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['add_post'];
        unset($_SESSION['add_post']);
        ?>
      </p>
    </div>
  <?php endif ?>





  <form action="<?= ROOT_URL ?>admin/add_post_logic.php" enctype="multipart/form-data" method="POST">
    <div class="post_input">
      <div class="post_field">
        <textarea name="user_post" placeholder="Share your thoughts here!"><?= htmlspecialchars($user_post) ?></textarea>

        <div class="post_actions">

          <label for="image-upload" style="cursor: pointer;">
            <i class="fa-solid fa-image" style="font-size: 24px;"></i>
          </label>

          <input type="file" id="image-upload" name="images[]" accept="image/*" multiple style="display: none;" />

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
    include 'page_essentials/open_scrolls.php';
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
      include 'page_essentials/my_timeline.php';
      ?>


    </div>



  </section>




</main>


<?php
// include './partials/floating_input.php';


include '../partials/footer.php';
?>