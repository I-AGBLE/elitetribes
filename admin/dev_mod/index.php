<?php
include 'partials/header.php';

// Validate session and authorization
if (!isset($_SESSION['user_id']) || !ctype_digit($_SESSION['user_id'])) {
    header('Location: ' . ROOT_URL);
    exit;
}

$id = (int)$_SESSION['user_id'];

// Check admin status  
$stmt = $connection->prepare("SELECT is_admin FROM tribesmen WHERE id = ? LIMIT 1");
if (!$stmt) {
    // Log error and redirect
    error_log("Database error: " . $connection->error);
    header('Location: ' . ROOT_URL . 'admin/');
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

// Fetch scrolls 
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
        <div class="my_posts_contents" id="my_posts_contents" style="display: block;">
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
                    <input type="text" id="search_box" placeholder="Search Posts" oninput="sanitizeSearchInput(this)">
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
                            $tribesmen_stmt = $connection->prepare("SELECT id, username, avatar, is_admin FROM tribesmen WHERE id = ? LIMIT 1");
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
                                    <h4><?= $tribesmen['username'] ?></h4>
                                </div>


                                <?php if (isset($tribesmen['is_admin']) && $tribesmen['is_admin'] == 1): ?>
                                    <div class="admin_flag">
                                        <img src="../../images/admin_flag.gif" alt="Admin Flag" />
                                    </div>
                                <?php endif; ?>
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
                            <a href="<?= htmlspecialchars(ROOT_URL) ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>" style="text-decoration: none; color: inherit;">
                                <p style="margin-bottom: 0;">
                                    <?php
                                    $text = nl2br($scroll['user_post']);
                                    $maxLength = 500;
                                    if (strlen(strip_tags($scroll['user_post'])) > $maxLength) {
                                        echo substr($text, 0, $maxLength);
                                        echo ' <span class="hyperlink" style="margin-top: -.5rem"><br>Read More...</span>';
                                    } else {
                                        echo $text;
                                    }
                                    ?>
                                </p>
                            </a>
                        </div>

                        <?php
                        // Secure image handling
                        $images = array_filter(array_map('trim', explode(',', $scroll['images'])));
                        $images = array_map(function ($img) {
                            return htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8');
                        }, $images);
                        if (!empty($images)) :
                        ?>
                            <div class="post_images_container ">
                                <div class="post_images">
                                    <?php foreach ($images as $image) : ?>
                                        <a href="<?= htmlspecialchars(ROOT_URL, ENT_QUOTES, 'UTF-8') ?>admin/post_preview.php?id=<?= urlencode($scroll_id) ?>">
                                            <img src="../../images/<?= $image ?>" alt="Post's image."
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


                            <div class="follow" id="flagged_btn">
                                <?php
                                // After fetching $user_details, check if the user is flagged
                                $scroll_id = (int)$scroll['id'];
                                $isFlagged = isset($scroll['flagged']) && $scroll['flagged'] == 1;
                                ?>

                                <?php if ($isFlagged): ?>
                                    <a href="flagged_logic.php?id=<?= urlencode($scroll_id) ?>"
                                        id="danger_btn"
                                        class="follow-btn"
                                        data-user-id="<?= htmlspecialchars($scroll_id) ?>">
                                        Flagged
                                    </a>
                                <?php else: ?>
                                    <a href="flagged_logic.php?id=<?= urlencode($scroll_id) ?>"
                                        id="default_btn"
                                        class="follow-btn"
                                        data-user-id="<?= htmlspecialchars($scroll_id) ?>">
                                        Flag
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <div id="infinite-loader-timeline" class="infinite-loader" style="display:none;text-align:center;margin:1rem 0;">
                <span class="ripple-dot"></span>
                <span class="ripple-dot"></span>
                <span class="ripple-dot"></span>
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