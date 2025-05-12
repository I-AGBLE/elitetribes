<?php
include 'partials/header.php';



// fetch user details 
if (isset($_SESSION['user_id'])) {
  $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT * FROM tribesmen WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $user_detail = mysqli_fetch_assoc($result);
}

// fetch user posts
$current_user_id = $_SESSION['user_id'];
$query  = "SELECT * FROM scrolls  WHERE scrolls.created_by=$current_user_id ORDER BY scrolls.id DESC";
$scrolls = mysqli_query($connection, $query);


?>




<main>

  <?php if (isset($_SESSION['add_post_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['add_post_success'];
        unset($_SESSION['add_post_success']);
        ?>
      </p>
    </div>


  <?php elseif (isset($_SESSION['edit_profile_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['edit_profile_success'];
        unset($_SESSION['edit_profile_success']);
        ?>
      </p>
    </div>

  <?php elseif (isset($_SESSION['delete_scroll_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['delete_scroll_success'];
        unset($_SESSION['delete_scroll_success']);
        ?>
      </p>
    </div>
  <?php endif ?>


  <?php
$user_id = $_SESSION['user_id'];

$query = "SELECT COUNT(*) AS follower_count FROM followers WHERE followed = $user_id";
$result = mysqli_query($connection, $query);
$follower_count = 0;

if ($result && $row = mysqli_fetch_assoc($result)) {
    $follower_count = $row['follower_count'];
}

?>





  <div class="user_section">
    <div class="user_information">
      <div class="user_picture">
        <img src="../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />
      </div>

      <div class="user_info">
        <div class="name">
          <h3><?= $user_detail['username'] ?></h3>


          <?php if ($followers_count >= 20): ?>
            <div class="verified">
              <div class="verified_icon">
                <i class="fa-solid fa-check"></i>
              </div>
              <div class="verified_desc">
                <p>Verified</p>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="about">
          <p><?= $user_detail['about'] ?></p>
        </div>



        <div class="followers_and_posts">
          <p>Followers: <span><?= $followers_count ?? 0 ?></span></p>

        </div>




        <div class="user_action_buttons">
          <div class="post_reaction">
            <div class="post_reaction_icon">
              <a href="<?= ROOT_URL ?>admin/edit_profile.php?id=<?= $user_detail['id'] ?> ">
                <i class="fa-solid fa-pen" id="edit_icon"></i>
              </a>
            </div>
            <div class="post_reaction_desc">
              <p>Edit</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <section class="dashboard">


    <?php
    include 'page_essentials/my_posts.php';
    ?>










<div class="feed" id="feed" style="display: none;">
      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#my_posts" id="my_posts">My Posts</a>
              <a href="#feed" id="my_feed" style="color: var(--color_warning);">My Timeline</a>
              <a href="#following" id="my_following">Following</a>
            </div>
          </div>
        </div>
      </div>



      <?php 
    include'page_essentials/my_timeline.php';
      ?>


    </div>






    <div class="following" id="following" style="display: none;">
      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#my_posts" id="my_posts">My Posts</a>
              <a href="#feed" id="my_feed">My Timeline</a>
              <a href="#following" id="my_following" style="color: var(--color_warning);">Following</a>
            </div>
          </div>
        </div>
      </div>

      <?php
      include 'page_essentials/followings.php';
      ?>


    </div>
  </section>




</main>





<?php
include 'partials/floating_input.php';


include '../partials/footer.php';
?>