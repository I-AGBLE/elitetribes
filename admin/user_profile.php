<?php
include 'partials/header.php';

if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
  // Redirect unauthorized access
  header('Location: ' . ROOT_URL . 'login.php');
  exit;
}

// Sanitize and validate session user ID
$id = (int) $_SESSION['user_id'];

//  fetch user details
$stmt = $connection->prepare("SELECT * FROM tribesmen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user_detail = $result->fetch_assoc();
$stmt->close();

// Fetch user posts securely
$stmt = $connection->prepare("SELECT * FROM scrolls WHERE created_by = ? ORDER BY id DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$scrolls = $stmt->get_result();
$stmt->close();
?>

<main>

  <!-- notifications  -->
  <?php if (!empty($_SESSION['add_post_success'])): ?>
    <div class="alert_message success" id="alert_message">
      <p><?= htmlspecialchars($_SESSION['add_post_success']);
          unset($_SESSION['add_post_success']); ?></p>
    </div>

  <?php elseif (!empty($_SESSION['edit_profile_success'])): ?>
    <div class="alert_message success" id="alert_message">
      <p><?= htmlspecialchars($_SESSION['edit_profile_success']);
          unset($_SESSION['edit_profile_success']); ?></p>
    </div>

  <?php elseif (!empty($_SESSION['delete_scroll_success'])): ?>
    <div class="alert_message success" id="alert_message">
      <p><?= htmlspecialchars($_SESSION['delete_scroll_success']);
          unset($_SESSION['delete_scroll_success']); ?></p>
    </div>
  <?php endif; ?>





  <section class="main_left">
    <!--Update -->
  </section>




  <section class="main_content">
    <?php
    //  fetch follower count
    $stmt = $connection->prepare("SELECT COUNT(*) AS follower_count FROM followers WHERE followed = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $follower_count = $row['follower_count'] ?? 0;
    $stmt->close();
    ?>



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
            <p><?= $user_detail['about'] ?></p>
          </div>

          <div class="followers_and_posts">
            <p>Followers: <span><?= (int) $follower_count ?></span></p>
          </div>


          <div class="user_action_buttons">
            <div class="post_reaction">
              <div class="post_reaction_icon">
                <a href="<?= ROOT_URL ?>admin/edit_profile.php?id=<?= (int) $user_detail['id'] ?>">
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
      <?php include 'page_essentials/my_posts.php'; ?>

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
        <?php include 'page_essentials/my_timeline.php'; ?>
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