<div class="post_reaction">
  <div class="post_reaction_icon">
    <div class="like_icons">
      <?php
      // Initialize with default values
      $liked = false;
      $like_count = 0;
      $scroll_id = isset($scroll['id']) ? intval($scroll['id']) : 0;

      if (isset($_SESSION['user_id']) && $scroll_id > 0) {
          $tribesmen_id = intval($_SESSION['user_id']);
          
          // Check if user already liked - using prepared statement
          $query_check = "SELECT 1 FROM likes WHERE scroll_id = ? AND tribesmen_id = ? LIMIT 1";
          $stmt = mysqli_prepare($connection, $query_check);
          
          if ($stmt) {
              mysqli_stmt_bind_param($stmt, "ii", $scroll_id, $tribesmen_id);
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              
              if (mysqli_num_rows($result) > 0) {
                  $liked = true;
              }
              mysqli_stmt_close($stmt);
          }
          
          // Get like count - using prepared statement
          $query_count = "SELECT COUNT(*) AS total_likes FROM likes WHERE scroll_id = ?";
          $stmt_count = mysqli_prepare($connection, $query_count);
          
          if ($stmt_count) {
              mysqli_stmt_bind_param($stmt_count, "i", $scroll_id);
              mysqli_stmt_execute($stmt_count);
              $result_count = mysqli_stmt_get_result($stmt_count);
              
              if ($row = mysqli_fetch_assoc($result_count)) {
                  $like_count = intval($row['total_likes']);
              }
              mysqli_stmt_close($stmt_count);
          }
      }
      
      // Generate CSRF token if not exists
      if (!isset($_SESSION['csrf_token'])) {
          $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
      ?>

      <div class="like_icon">
        <?php if ($scroll_id > 0 && isset($_SESSION['user_id'])): ?>
          <a href="../like_logic.php?id=<?= $scroll_id ?>&csrf=<?= $_SESSION['csrf_token'] ?>" onclick="saveScroll(event)">
            <i class="fa-regular fa-heart <?= $liked ? 'liked' : 'default' ?>" id="like_icon_<?= $scroll_id ?>"></i>
          </a>
        <?php else: ?>
          <i class="fa-regular fa-heart default"></i>
        <?php endif; ?>
      </div>

      <script>
        function saveScroll(e) {
          // Store scroll position
          sessionStorage.setItem('scrollTop', window.scrollY);
          
          // For non-JS users, prevent default only if JS is enabled
          e.preventDefault();
          // Consider adding AJAX call here instead of link navigation
          window.location.href = e.target.closest('a').href;
        }

        document.addEventListener('DOMContentLoaded', function() {
          // Restore scroll position
          const scroll = sessionStorage.getItem('scrollTop');
          if (scroll !== null && !isNaN(scroll)) {
            window.scrollTo(0, parseInt(scroll));
            sessionStorage.removeItem('scrollTop');
          }
          
          // Consider adding AJAX like functionality here
        });
      </script>
    </div>
    <p id="like_count_<?= $scroll_id ?>"><?= htmlspecialchars($like_count, ENT_QUOTES, 'UTF-8') ?></p>
  </div>

  
  <div class="post_reaction_desc">
    <p>Like</p>
  </div>
</div>