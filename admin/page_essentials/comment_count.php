<div class="post_reaction">

    <?php
    if (isset($_GET['id'])) {
        $scroll_id = $_GET['id'];
        $scroll_id = mysqli_real_escape_string($connection, $scroll_id);

        // Fetch comment count
        $count_query = "SELECT COUNT(*) AS comment_count FROM comments WHERE scroll_id = '$scroll_id'";
        $count_result = mysqli_query($connection, $count_query);

        $comment_count = 0;
        if ($count_result) {
            $count_row = mysqli_fetch_assoc($count_result);
            $comment_count = $count_row['comment_count'];
        }
    }
    ?>



    <div class="post_reaction_icon" id="comment_icon">
        <i class="fa-regular fa-comment"  id="comment_icon"></i>
        <p id="comment_count"><?= $comment_count ?></p>
    </div>
    <div class="post_reaction_desc">
        <p>Comment</p>
    </div>
</div>