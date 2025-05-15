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



// display the follow or unfollow button based on the follow state in the db
$loggedInUserId = $_SESSION['user_id']; // follower
$profileUserId = $id; // followed, assuming this is already set

// Check if user is already following
$checkFollowQuery = "SELECT * FROM followers WHERE follower = $loggedInUserId AND followed = $profileUserId";
$checkFollowResult = mysqli_query($connection, $checkFollowQuery);
$isFollowing = mysqli_num_rows($checkFollowResult) > 0;



// Count followers of the current user
$countFollowersQuery = "SELECT COUNT(*) AS total_followers FROM followers WHERE followed = $profileUserId";
$countFollowersResult = mysqli_query($connection, $countFollowersQuery);
$followersData = mysqli_fetch_assoc($countFollowersResult);
$followersCount = $followersData['total_followers'];


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

  <?php elseif (isset($_SESSION['follow'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['follow'];
        unset($_SESSION['follow']);
        ?>
      </p>
    </div>

  <?php elseif (isset($_SESSION['follow_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['follow_success'];
        unset($_SESSION['follow_success']);
        ?>
      </p>
    </div>
  <?php endif ?>




  <?php
  // Get the list of users this user is following along with their follower count
  $query = "
    SELECT t.id, t.username, t.avatar, t.id,
           (SELECT COUNT(*) FROM followers WHERE followed = t.id) AS followers_count
    FROM followers f
    JOIN tribesmen t ON f.followed = t.id
    WHERE f.follower = $id
";

  $result = mysqli_query($connection, $query);

  $followers_count = 0;

  while ($row = mysqli_fetch_assoc($result)) {
    $followers_count = $row["followers_count"];
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
          <p>Followers: <span><?= $followersCount ?></span></p>
        </div>





        <div class="user_action_buttons">
          <div class="follow">
            <?php if ($isFollowing): ?>
              <!-- User is following, show "Following" button -->
              <a href="follow_logic.php?id=<?= $profileUserId ?>" id="danger_btn">Following</a>
            <?php else: ?>
              <!-- User is not following, show "Follow" button -->
              <a href="follow_logic.php?id=<?= $profileUserId ?>" id="default_btn">Follow</a>
            <?php endif; ?>
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
              <a href="#my_posts" id="my_posts"> Posts</a>
              <a href="#feed" id="my_feed" style="display: none;">My Timeline</a>
              <a href="#following" id="my_following" style="color: var(--color_warning);">Following</a>
            </div>
          </div>
        </div>
      </div>



      <?php
      include 'page_essentials/followings.php';
      ?>

  </section>




</main>




<?php
include 'partials/floating_input.php';


include '../partials/footer.php';
?>