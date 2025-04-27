<?php
include 'partials/header.php';

// Check if a user ID is passed in the URL
if (isset($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

  // Fetch user details
  $query = "SELECT * FROM tribesmen WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $user_detail = mysqli_fetch_assoc($result);

  // Fetch user's posts
  $query = "SELECT * FROM scrolls WHERE created_by=$id ORDER BY id DESC";
  $scrolls = mysqli_query($connection, $query);
} else {
  // If no user ID is provided, redirect or show error
  header("Location: " . ROOT_URL . "index.php");
  exit();
}
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
  <?php endif ?>


  <div class="user_section">
    <div class="user_information">
      <div class="user_picture">
        <img src="../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />
      </div>

      <div class="user_info">
        <div class="name">
          <h3><?= $user_detail['username'] ?></h3>


          <?php if ($user_detail['followers'] > 20): ?>
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
          <p>Followers: <span> <?= $user_detail['followers'] ?></span></p>
        </div>

        <div class="user_action_buttons">
          <div class="follow">
            <a href="#" id="default_btn">Follow</a>
            <a href="#" id="danger_btn" style="display: none;">Following</a>
          </div>
        </div>
      </div>
    </div>
  </div>


  <section class="dashboard">


    <?php
    include 'page_essentials/profile_scrolls.php';
    ?>






    <div class="feed" id="feed" style="display: none;">
 
    </div>






    <div class="following" id="following" style="display: none;">
      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#my_posts" id="my_posts">My Posts</a>
              <a href="#feed" id="my_feed" style="display: none;">My Timeline</a>
              <a href="#following" id="my_following" style="color: var(--color_warning);">Following</a>
            </div>
          </div>
        </div>
      </div>


      <div class="search_box">
        <center>
          <input type="text" placeholder="Search Following" id="my__following_box">
        </center>
      </div>



      <div class="followings">
        <div class="post">
          <div class="user_details">
            <a href="">
              <div class="user_profile_pic">
                <img
                  src="../images/profile_pic.png"
                  alt="User's profile picture." />
              </div>

              <div class="user_name">
                <h4>Khadi Khole</h4>
              </div>

              <div class="verified">
                <div class="verified_icon">
                  <i class="fa-solid fa-check"></i>
                </div>
                <div class="verified_desc">
                  <p>Verified</p>
                </div>
              </div>
            </a>





          </div>

        </div>



      </div>


    </div>
  </section>




</main>





<?php
include 'partials/floating_input.php';


include '../partials/footer.php';
?>