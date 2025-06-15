<div class="post_reaction">

    <?php
    $comment_count = 0;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $scroll_id = (int) $_GET['id'];

        $stmt = mysqli_prepare($connection, "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $scroll_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && ($count_row = mysqli_fetch_assoc($result))) {
                $comment_count = (int) $count_row['comment_count'];
            }
            mysqli_stmt_close($stmt);
        }
    }
    ?>

    <div class="post_reaction_icon" id="comment_icon">
        <i class="fa-regular fa-comment" id="comment_icon"></i>
        <p id="comment_count"><?= htmlspecialchars($comment_count, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="post_reaction_desc">
        <p><?= htmlspecialchars('Comment', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</div>