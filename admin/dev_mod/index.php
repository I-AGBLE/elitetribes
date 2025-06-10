<?php
include 'partials/header.php';

// Validate session and authorization
if (!isset($_SESSION['user_id']) || !ctype_digit($_SESSION['user_id'])) {
    header('Location: ' . ROOT_URL);
    exit;
}

$id = (int)$_SESSION['user_id'];

// Check admin status with prepared statement
$stmt = $connection->prepare("SELECT is_admin FROM tribesmen WHERE id = ? LIMIT 1");
if (!$stmt) {
    // Log error and redirect
    error_log("Database error: " . $connection->error);
    header('Location: ' . ROOT_URL . 'error.php');
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $user = $result->fetch_assoc()) {
    if ((int)$user['is_admin'] !== 1) {
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    }
} else {
    header('Location: ' . ROOT_URL);
    exit;
}
$stmt->close();

// Fetch scrolls with prepared statement
$stmt = $connection->prepare("SELECT * FROM scrolls ORDER BY id DESC");
if (!$stmt) {
    error_log("Database error: " . $connection->error);
    die("Database error");
}

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
            <?php foreach ($scrolls as $scroll): 
                $scroll_id = (int)$scroll['id'];
                $created_by = (int)$scroll['created_by'];
            ?>
                <div class="post" id="scroll_<?= $scroll_id ?>">
                    <div class="user_details">
                        <?php
                        // Securely fetch user details
                        $tribesmen_stmt = $connection->prepare("SELECT id, username, avatar FROM tribesmen WHERE id = ? LIMIT 1");
                        $tribesmen_stmt->bind_param("i", $created_by);
                        $tribesmen_stmt->execute();
                        $tribesmen_result = $tribesmen_stmt->get_result();
                        $tribesmen = $tribesmen_result->fetch_assoc() ?? ['id' => 0, 'username' => 'Deleted User', 'avatar' => 'default.png'];
                        $tribesmen_stmt->close();
                        
                        $avatar_path = '../../images/' . htmlspecialchars(basename($tribesmen['avatar']));
                        ?>

                        <a href="<?= htmlspecialchars(ROOT_URL) ?>admin/profiles.php?id=<?= $tribesmen['id'] ?>">
                            <div class="user_profile_pic">
                                <img src="<?= $avatar_path ?>" 
                                     alt="<?= htmlspecialchars($tribesmen['username']) ?>'s profile picture" 
                                     onerror="this.src='../../images/default.png'">
                            </div>
                            <div class="user_name">
                                <h4><?= htmlspecialchars($tribesmen['username']) ?></h4>
                            </div>
                        </a>

                        <div class="user_details_post_time">
                            <div class="post_date">
                                <p><?= htmlspecialchars(date("M d, Y", strtotime($scroll['created_at']))) ?></p>
                            </div>
                            <div class="post_time">
                                <p><?= htmlspecialchars(date("H:i", strtotime($scroll['created_at']))) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="post_text">
                        <p>
                            <a href="<?= htmlspecialchars(ROOT_URL) ?>admin/post_preview.php?id=<?= $scroll_id ?>">
                                <?php
                                $text = htmlspecialchars($scroll['user_post']);
                                $text = nl2br($text);
                                $maxLength = 500;
                                echo strlen($text) > $maxLength 
                                    ? substr($text, 0, $maxLength) . '<p>Read More...</p>' 
                                    : $text;
                                ?>
                            </a>
                        </p>
                    </div>

                    <?php
                    // Secure image handling
                    $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
                    $valid_images = [];
                    
                    foreach ($images as $image) {
                        $clean_image = basename($image);
                        if (preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png|gif|webp)$/i', $clean_image)) {
                            $valid_images[] = $clean_image;
                        }
                    }
                    
                    if (!empty($valid_images)) :
                    ?>
                        <div class="post_images_container">
                            <div class="post_images">
                                <?php foreach ($valid_images as $image): ?>
                                    <a href="<?= htmlspecialchars(ROOT_URL) ?>admin/post_preview.php?id=<?= $scroll_id ?>">
                                        <img src="../../images/<?= htmlspecialchars($image) ?>" 
                                             alt="Post image" 
                                             onerror="this.style.display='none'">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="post_reactions">
                        <?php include 'like_n_like_count.php'; ?>

                        <div class="post_reaction">
                            <?php
                            $comment_stmt = $connection->prepare("SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = ?");
                            $comment_stmt->bind_param("i", $scroll_id);
                            $comment_stmt->execute();
                            $comment_result = $comment_stmt->get_result();
                            $comment_count = 0;
                            
                            if ($comment_result && $row = $comment_result->fetch_assoc()) {
                                $comment_count = (int)$row['comment_count'];
                            }
                            $comment_stmt->close();
                            ?>
                            <div class="post_reaction_icon" id="comment_icon_<?= $scroll_id ?>">
                                <a href="<?= htmlspecialchars(ROOT_URL) ?>admin/post_preview.php?id=<?= $scroll_id ?>">
                                    <i class="fa-regular fa-comment"></i>
                                </a>
                                <p id="comment_count_<?= $scroll_id ?>"><?= $comment_count ?></p>
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

