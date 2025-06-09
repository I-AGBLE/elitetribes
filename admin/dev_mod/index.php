<?php
include 'partials/header.php';



if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    // Redirect unauthorized access
    header('Location: ' . ROOT_URL);
    exit;
}

$id = (int) $_SESSION['user_id'];

// Check if user is admin
$stmt = $connection->prepare("SELECT is_admin FROM tribesmen WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $user = $result->fetch_assoc()) {
    if ((int)$user['is_admin'] !== 1) {
        // Not an admin – redirect to scrolls section
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    }
} else {
    // User not found – redirect
    header('Location: ' . ROOT_URL);
    exit;
}

$stmt->close();





// Sanitize and validate session user ID
$id = (int) $_SESSION['user_id'];

// Fetch all scrolls from the scrolls table (newest first)
$stmt = $connection->prepare("SELECT * FROM scrolls ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

$scrolls = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $scrolls[] = $row;
    }
}
$stmt->close();

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
                        <a href="index.php" style="color: var(--color_warning);">All Scrolls</a>
                        <a href="all_tribesmen.php">All Tribesmen</a>
                    </div>
                </div>
            </div>
        </div>



        <div class="search_box">
            <center>
                <input type="text" placeholder="Search Scrolls" id="search_box_for_open_scrolls">
            </center>
        </div>






        <div class="my_posts">
            <?php foreach ($scrolls as $scroll): ?>
                <div class="post">
                    <div class="user_details">
                        <?php
                        // Securely fetch user who created the scroll
                        $tribesmen_id = (int) $scroll['created_by'];
                        $tribesmen_stmt = $connection->prepare("SELECT id, username, avatar FROM tribesmen WHERE id = ?");
                        $tribesmen_stmt->bind_param("i", $tribesmen_id);
                        $tribesmen_stmt->execute();
                        $tribesmen_result = $tribesmen_stmt->get_result();
                        $tribesmen = $tribesmen_result->fetch_assoc();
                        $tribesmen_stmt->close();
                        ?>

                        <a href="<?= ROOT_URL ?>admin/profiles.php?id=<?= $tribesmen['id'] ?>">
                            <div class="user_profile_pic">
                                <img src="../../images/<?= htmlspecialchars($tribesmen['avatar']) ?>" alt="User's profile picture." />
                            </div>
                            <div class="user_name">
                                <h4><?= htmlspecialchars($tribesmen['username']) ?></h4>
                            </div>

                        </a>

                        <div class="user_details_post_time">
                            <div class="post_date">
                                <p><?= date("M d, Y", strtotime($scroll['created_at'])) ?></p>
                            </div>
                            <div class="post_time">
                                <p><?= date("H:i", strtotime($scroll['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="post_text">
                        <p>
                            <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $scroll['id'] ?>">
                                <?php
                                $text = nl2br(htmlspecialchars($scroll['user_post']));
                                $maxLength = 500;
                                echo strlen($text) > $maxLength
                                    ? substr($text, 0, $maxLength) . '<p>Read More...</p>'
                                    : $text;
                                ?>
                            </a>
                        </p>
                    </div>

                    <?php
                    $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
                    if (!empty($images)) :
                    ?>
                        <div class="post_images_container">
                            <div class="post_images">
                                <?php foreach ($images as $image): ?>
                                    <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $scroll['id'] ?>">
                                        <img src="../../images/<?= htmlspecialchars($image) ?>" alt="Post image.">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="post_reactions">
                        <?php include 'like_n_like_count.php'; ?>



                        <div class="post_reaction">
                            <?php
                            $scroll_id = (int) $scroll['id'];
                            $comment_count = 0;
                            $comment_stmt = $connection->prepare("SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = ?");
                            $comment_stmt->bind_param("i", $scroll_id);
                            $comment_stmt->execute();
                            $comment_result = $comment_stmt->get_result();
                            if ($comment_result && $row = $comment_result->fetch_assoc()) {
                                $comment_count = $row['comment_count'];
                            }
                            $comment_stmt->close();
                            ?>
                            <div class="post_reaction_icon" id="comment_icon">
                                <a href="<?= ROOT_URL ?>admin/post_preview.php?id=<?= $scroll['id'] ?>">
                                    <i class="fa-regular fa-comment" id="comment_icon"></i>
                                </a>
                                <p id="comment_count"><?= $comment_count ?></p>
                            </div>
                            <div class="post_reaction_desc">
                                <p>Comment</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


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