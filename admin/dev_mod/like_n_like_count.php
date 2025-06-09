<div class="post_reaction">
  <div class="post_reaction_icon">
    <div class="like_icons">
      <?php
      // Initialize
      $liked = false;
      $like_count = 0;

      if (isset($_SESSION['user_id'])) {
        $tribesmen_id = $_SESSION['user_id'];
        $scroll_id = $scroll['id'];

        // Check if this user has already liked the post
        $query_check = "SELECT * FROM likes WHERE scroll_id = $scroll_id AND tribesmen_id = $tribesmen_id";
        $result = mysqli_query($connection, $query_check);
        if (mysqli_num_rows($result) > 0) {
          $liked = true;
        }
      }

      // Sum of likes for scroll
      $query_like_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = $scroll_id";
      $result_count = mysqli_query($connection, $query_like_count);
      if ($row = mysqli_fetch_assoc($result_count)) {
        $like_count = $row['total_likes'];
      }
      ?>

      <div class="like_icon">
        <a href="../like_logic.php?id=<?= $scroll['id'] ?>" onclick="saveScroll()">
          <i class="fa-regular fa-heart <?= $liked ? 'liked' : 'default' ?>"  id="like_icon"></i>
        </a>
      </div>


      <script>
        function saveScroll() {
          sessionStorage.setItem('scrollTop', window.scrollY);
        }

        window.addEventListener('load', function() {
          const scroll = sessionStorage.getItem('scrollTop');
          if (scroll !== null) {
            window.scrollTo(0, parseInt(scroll));
            sessionStorage.removeItem('scrollTop');
          }
        });
      </script>




    </div>
    <p id="like_count"><?= $like_count ?></p>
  </div>
  <div class="post_reaction_desc">
    <p>Like</p>
  </div>
</div>