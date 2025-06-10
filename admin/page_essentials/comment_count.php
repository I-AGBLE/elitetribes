<div class="post_reaction">

    <?php
    $comment_count = 0; // ✅ Always define it

    if (isset($_GET['id'])) {
        $scroll_id = (int) $_GET['id']; // ✅ Typecast to prevent injection

        // ✅ Use prepared statement for security
        $stmt = mysqli_prepare($connection, "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $scroll_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $count_row = mysqli_fetch_assoc($result);
            $comment_count = (int) $count_row['comment_count'];
        }
    }
    ?>

    <div class="post_reaction_icon" id="comment_icon">
        <i class="fa-regular fa-comment" id="comment_icon"></i>
        <p id="comment_count"><?= htmlspecialchars($comment_count) ?></p> <!-- ✅ Escape output -->
    </div>
    <div class="post_reaction_desc">
        <p>Comment</p>
    </div>
</div>
