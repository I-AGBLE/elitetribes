<?php
// Start session and include configuration
require_once 'partials/header.php';

// Validate user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ROOT_URL . "auth/login.php");
    exit();
}

// Check if a user ID is passed in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id === false) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

// Fetch user details using prepared statement
$query = "SELECT * FROM tribesmen WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

$user_detail = mysqli_fetch_assoc($result); // ✅ FIXED HERE
mysqli_stmt_close($stmt);



// Fetch user's posts with prepared statement
$query = "SELECT * FROM scrolls WHERE created_by = ? ORDER BY id DESC";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$scrolls = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// Check follow status
$loggedInUserId = $_SESSION['user_id'];
$profileUserId = $id;

// After fetching $user_detail
$isBlocked = isset($user_detail['blocked']) && $user_detail['blocked'] == 1;

// Prepared statement for follow check
$checkFollowQuery = "SELECT * FROM followers WHERE follower = ? AND followed = ?";
$stmt = mysqli_prepare($connection, $checkFollowQuery);
mysqli_stmt_bind_param($stmt, "ii", $loggedInUserId, $profileUserId);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$isFollowing = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

// Count followers with prepared statement
$countFollowersQuery = "SELECT COUNT(*) AS total_followers FROM followers WHERE followed = ?";
$stmt = mysqli_prepare($connection, $countFollowersQuery);
mysqli_stmt_bind_param($stmt, "i", $profileUserId);
mysqli_stmt_execute($stmt);
$countFollowersResult = mysqli_stmt_get_result($stmt);
$followersData = mysqli_fetch_assoc($countFollowersResult);
$followersCount = $followersData['total_followers'];
mysqli_stmt_close($stmt);

// Get following count with prepared statement
$followingQuery = "SELECT COUNT(*) AS total_following FROM followers WHERE follower = ?";
$stmt = mysqli_prepare($connection, $followingQuery);
mysqli_stmt_bind_param($stmt, "i", $profileUserId);
mysqli_stmt_execute($stmt);
$followingResult = mysqli_stmt_get_result($stmt);
$followingData = mysqli_fetch_assoc($followingResult);
$followingCount = $followingData['total_following'];
mysqli_stmt_close($stmt);
?>

<main>
    <section class="main_left">
        <!-- Left sidebar content -->
    </section>

    <section class="main_content">
        <div class="my_dashboard" id="dashboard">
            <div class="my_dashboard_title">
                <div class="dashboard_small_titles">
                    <div class="my_posts_links">
                        <a href="index.php">All Scrolls</a>
                        <a href="all_tribesmen.php">All Tribesmen</a>
                        <a href="#" style="color: var(--color_warning);">User Details</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="user_section">
            <div class="user_information">
                <div class="user_picture">
                    <?php if (!empty($user_detail['avatar'])): ?>
                        <img src="<?= htmlspecialchars(ROOT_URL . 'images/' . $user_detail['avatar']) ?>" 
                             alt="<?= htmlspecialchars($user_detail['username']) ?>'s profile picture" />
                    <?php else: ?>
                        <img src="<?= htmlspecialchars(ROOT_URL . 'images/default-avatar.png') ?>" 
                             alt="Default profile picture" />
                    <?php endif; ?>
                </div>

                <div class="user_info">
                    <div class="name">
                        <h3><?= $user_detail['username']?></h3>
                        <?php if ($user_detail['is_admin']): ?>
                            <span class="admin-badge">Admin</span>
                        <?php endif; ?>
                    </div>

                    <div class="about">
                        <p><?= !empty($user_detail['about']) ? nl2br($user_detail['about']) : 'This user hasn\'t written anything about themselves yet.' ?></p>
                    </div>

                    <div class="user_meta">
                        <?php if (!empty($user_detail['email'])): ?>
                            <p>
                                E-mail:
                                <span>
                                    <a href="mailto:<?= htmlspecialchars($user_detail['email']) ?>">
                                        <?= $user_detail['email'] ?>
                                    </a>
                                </span>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($user_detail['telephone'])): ?>
                            <p>
                                Telephone:
                                <span>
                                    <a href="tel:<?= htmlspecialchars($user_detail['telephone']) ?>">
                                        <?= $user_detail['telephone'] ?>
                                    </a>
                                </span>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($user_detail['gender'])): ?>
                            <p>Gender: <span><?= htmlspecialchars($user_detail['gender']) ?></span></p>
                        <?php endif; ?>

                        <p>
                            Registration Date:
                            <span>
                                <?= date("F j, Y \a\\t H:i ", strtotime($user_detail['created_at'])) ?>
                            </span>
                        </p>

                        
                        <p>Followers: <span><?= htmlspecialchars($followersCount) ?></span></p>
                        <p>Following: <span><?= htmlspecialchars($followingCount) ?></span></p>
                        <p>Posts: <span><?= mysqli_num_rows($scrolls) ?></span></p>

                    </div>

                    <div class="user_action_buttons">
                        <?php if ($loggedInUserId != $profileUserId): ?>
                            <div class="follow">
                                <?php if ($isFollowing): ?>
                                    <a href="../follow_logic.php?id=<?= urlencode($profileUserId) ?>" 
                                       id="warning_btn" 
                                       class="follow-btn"
                                       data-user-id="<?= htmlspecialchars($profileUserId) ?>">
                                        Following
                                    </a>
                                <?php else: ?>
                                    <a href="../follow_logic.php?id=<?= urlencode($profileUserId) ?>" 
                                       id="default_btn" 
                                       class="follow-btn"
                                       data-user-id="<?= htmlspecialchars($profileUserId) ?>">
                                        Follow
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <a href="edit_profile.php" id="default_btn">Edit Profile</a>
                        <?php endif; ?>


                          <div class="follow">
                                <?php if ($isBlocked): ?>
                                    <a href="block_logic.php?id=<?= urlencode($profileUserId) ?>" 
                                       id="danger_btn" 
                                       class="follow-btn"
                                       data-user-id="<?= htmlspecialchars($profileUserId) ?>">
                                        Blocked
                                    </a>
                                <?php else: ?>
                                    <a href="block_logic.php?id=<?= urlencode($profileUserId) ?>" 
                                       id="default_btn" 
                                       class="follow-btn"
                                       data-user-id="<?= htmlspecialchars($profileUserId) ?>">
                                        Block
                                    </a>
                                <?php endif; ?>
                            </div>
                    </div>
                </div>
            </div>
            

        </div>
    </section>

    <section class="main_right">
        <!-- Right sidebar content -->
    </section>
</main>

<?php
include 'partials/floating_input.php';
include '../../partials/footer.php';
?>