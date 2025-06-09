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


    <section class="main_left">
        <!--Update -->
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
                    <img src="../../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />
                </div>

                <div class="user_info">
                    <div class="name">
                        <h3><?= $user_detail['username'] ?></h3>
                    </div>



                    <div class="about">
                        <p><?= $user_detail['about'] ?></p>
                    </div>


                    <div class="user_meta">
                        <p>
                            E-mail:
                            <span>
                                <a href="mailto:<?= htmlspecialchars($user_detail['email']) ?>">
                                    <?= htmlspecialchars($user_detail['email']) ?>
                                </a>
                            </span>
                        </p>

                        <p>
                            Telephone:
                            <span>
                                <a href="tel:<?= htmlspecialchars($user_detail['telephone']) ?>">
                                    <?= htmlspecialchars($user_detail['telephone']) ?>
                                </a>
                            </span>
                        </p>
                        <p>Gender: <span><?= $user_detail['gender'] ?></span></p>
                        <p>Admin Status: <span><?= $user_detail['is_admin'] ?></span></p>

                        <p>
                            Registration Date:
                            <span>
                                <?= date("F j, Y \a\\t h:i A", strtotime($user_detail['created_at'])) ?>
                            </span>
                        </p>

                    </div>


                    <div class="followers_and_posts">
                        <p>Followers: <span><?= $followersCount ?></span></p>
                    </div>





                    <div class="user_action_buttons">
                        <div class="follow">
                            <?php if ($isFollowing): ?>
                                <!-- User is following, show "Following" button -->
                                <a href="../follow_logic.php?id=<?= $profileUserId ?>" id="danger_btn">Following</a>
                            <?php else: ?>
                                <!-- User is not following, show "Follow" button -->
                                <a href="../follow_logic.php?id=<?= $profileUserId ?>" id="default_btn">Follow</a>
                            <?php endif; ?>
                        </div>
                    </div>



                </div>
            </div>
        </div>




    </section>


    <section class="main_right">
        <!--Update -->
    </section>


</main>




<?php
include 'partials/floating_input.php';


include '../../partials/footer.php';
?>