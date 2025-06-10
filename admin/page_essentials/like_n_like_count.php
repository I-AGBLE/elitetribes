<div class="post_reaction">
    <div class="post_reaction_icon">
        <div class="like_icons">
            <?php
            // Initialize
            $liked = false;
            $like_count = 0;
            $scroll_id = isset($scroll['id']) ? intval($scroll['id']) : 0;

            if ($scroll_id > 0 && isset($_SESSION['user_id'])) {
                $tribesmen_id = intval($_SESSION['user_id']);

                // Check if this user has already liked the post
                $query_check = "SELECT * FROM likes WHERE scroll_id = ? AND tribesmen_id = ?";
                $stmt = mysqli_prepare($connection, $query_check);
                mysqli_stmt_bind_param($stmt, "ii", $scroll_id, $tribesmen_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    $liked = true;
                }
                mysqli_stmt_close($stmt);

                // Sum of likes for scroll
                $query_like_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = ?";
                $stmt_count = mysqli_prepare($connection, $query_like_count);
                mysqli_stmt_bind_param($stmt_count, "i", $scroll_id);
                mysqli_stmt_execute($stmt_count);
                $result_count = mysqli_stmt_get_result($stmt_count);
                
                if ($row = mysqli_fetch_assoc($result_count)) {
                    $like_count = $row['total_likes'];
                }
                mysqli_stmt_close($stmt_count);
            }
            ?>

            <div class="like_icon">
                <?php if ($scroll_id > 0 && isset($_SESSION['user_id'])): ?>
                    <a href="like_logic.php?id=<?= htmlspecialchars($scroll_id, ENT_QUOTES) ?>&csrf_token=<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>" onclick="saveScroll()">
                        <i class="fa-regular fa-heart <?= $liked ? 'liked' : 'default' ?>" id="like_icon"></i>
                    </a>
                <?php else: ?>
                    <i class="fa-regular fa-heart default" id="like_icon"></i>
                <?php endif; ?>
            </div>

            <script>
                function saveScroll() {
                    const scrollPosition = window.scrollY;
                    if (typeof scrollPosition === 'number' && !isNaN(scrollPosition)) {
                        sessionStorage.setItem('scrollTop', scrollPosition);
                    }
                    return true;
                }

                window.addEventListener('load', function() {
                    const scroll = sessionStorage.getItem('scrollTop');
                    if (scroll !== null && !isNaN(parseInt(scroll))) {
                        window.scrollTo(0, parseInt(scroll));
                        sessionStorage.removeItem('scrollTop');
                    }
                });
            </script>
        </div>
        <p id="like_count"><?= htmlspecialchars($like_count, ENT_QUOTES) ?></p>
    </div>
    <div class="post_reaction_desc">
        <p>Like</p>
    </div>
</div>