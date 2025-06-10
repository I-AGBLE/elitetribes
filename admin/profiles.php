<?php
include 'partials/header.php';


// Sanitize and validate the ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

  // Prepare and execute statement to fetch user details
  $stmt = $connection->prepare("SELECT * FROM tribesmen WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user_detail = $result->fetch_assoc();
  $stmt->close();

  if (!$user_detail) {
    // Redirect if user does not exist
    header("Location: " . ROOT_URL . "index.php");
    exit();
  }




  // Prepare and execute statement to fetch user posts
  $stmt = $connection->prepare("SELECT * FROM scrolls WHERE created_by = ? ORDER BY id DESC");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $scrolls = $stmt->get_result();
  $stmt->close();

  // Get logged-in user ID
  $loggedInUserId = $_SESSION['user_id'] ?? 0;
  if (!is_numeric($loggedInUserId)) {
    $loggedInUserId = 0;
  }

  // Check follow status 
  $stmt = $connection->prepare("SELECT 1 FROM followers WHERE follower = ? AND followed = ?");
  $stmt->bind_param("ii", $loggedInUserId, $id);
  $stmt->execute();
  $stmt->store_result();
  $isFollowing = $stmt->num_rows > 0;
  $stmt->close();

  // Count followers 
  $stmt = $connection->prepare("SELECT COUNT(*) AS total_followers FROM followers WHERE followed = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $followersData = $stmt->get_result()->fetch_assoc();
  $followersCount = $followersData['total_followers'] ?? 0;
  $stmt->close();


} else {
  // Redirect if ID is invalid or not set
  header("Location: " . ROOT_URL . "index.php");
  exit();
}
?>

<main>
  <?php if (isset($_SESSION['add_post_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p><?= $_SESSION['add_post_success'];
          unset($_SESSION['add_post_success']); ?></p>
    </div>

  <?php elseif (isset($_SESSION['edit_profile_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p><?= $_SESSION['edit_profile_success'];
          unset($_SESSION['edit_profile_success']); ?></p>
    </div>

  <?php elseif (isset($_SESSION['follow'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p><?= $_SESSION['follow'];
          unset($_SESSION['follow']); ?></p>
    </div>

  <?php elseif (isset($_SESSION['follow_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p><?= $_SESSION['follow_success'];
          unset($_SESSION['follow_success']); ?></p>
    </div>
  <?php endif ?>






  <section class="main_left">
    <!--Update -->
  </section>









  <section class="main_content">

    <div class="user_section">
      <div class="user_information">
        <div class="user_picture">
          <img src="../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />
        </div>

        <div class="user_info">
          <div class="name">
            <h3><?= $user_detail['username'] ?></h3>

            
          </div>

          <div class="about">
            <p><?= nl2br(htmlspecialchars($user_detail['about'])) ?></p>
          </div>

          <div class="followers_and_posts">
            <p>Followers: <span><?= (int) $followersCount ?></span></p>
          </div>

          <div class="user_action_buttons">
            <div class="follow">
              <?php if ($isFollowing): ?>
                <a href="follow_logic.php?id=<?= (int) $id ?>" id="danger_btn">Following</a>
              <?php else: ?>
                <a href="follow_logic.php?id=<?= (int) $id ?>" id="default_btn">Follow</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="dashboard">
      <?php include 'page_essentials/profile_scrolls.php'; ?>

      <div class="feed" id="feed" style="display: none;"></div>

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
        <?php include 'page_essentials/followings.php'; ?>
      </div>
    </section>
  </section>



  <section class="main_right">
    <!--Update -->
  </section>
</main>





<?php
include 'partials/floating_input.php';
include '../partials/footer.php';
?>