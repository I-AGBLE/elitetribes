<div class="post_reaction">
    <div class="post_reaction_icon">
        <div class="like_icons">
            <?php
            // Start session securely if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Initialize variables
            $liked = false;
            $like_count = 0;
            $scroll_id = isset($scroll['id']) ? intval($scroll['id']) : 0;

            // Generate CSRF token if not set
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            $csrf_token = $_SESSION['csrf_token'];

            if ($scroll_id > 0 && isset($_SESSION['user_id'])) {
                $tribesmen_id = intval($_SESSION['user_id']);

                // Check if this user has already liked the post
                $query_check = "SELECT 1 FROM likes WHERE scroll_id = ? AND tribesmen_id = ? LIMIT 1";
                if ($stmt = mysqli_prepare($connection, $query_check)) {
                    mysqli_stmt_bind_param($stmt, "ii", $scroll_id, $tribesmen_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $liked = true;
                    }
                    mysqli_stmt_close($stmt);
                }

                // Get total like count for this scroll
                $query_like_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = ?";
                if ($stmt_count = mysqli_prepare($connection, $query_like_count)) {
                    mysqli_stmt_bind_param($stmt_count, "i", $scroll_id);
                    mysqli_stmt_execute($stmt_count);
                    $result_count = mysqli_stmt_get_result($stmt_count);
                    if ($row = mysqli_fetch_assoc($result_count)) {
                        $like_count = (int)$row['total_likes'];
                    }
                    mysqli_stmt_close($stmt_count);
                }
            }
            ?>

            <div class="like_icon">
                <?php if ($scroll_id > 0 && isset($_SESSION['user_id'])): ?>
                    <a href="like_logic.php?id=<?= htmlspecialchars($scroll_id, ENT_QUOTES, 'UTF-8') ?>&csrf_token=<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>" onclick="saveScroll()">
                        <i class="fa-regular fa-heart <?= $liked ? 'liked' : 'default' ?>" id="like_icon"></i>
                    </a>
                <?php else: ?>
                    <i class="fa-regular fa-heart default" id="like_icon"></i>
                <?php endif; ?>
            </div>
        </div>
        <p id="like_count"><?= htmlspecialchars($like_count, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="post_reaction_desc">
        <p>Like</p>
    </div>
</div>
