<?php
include 'partials/header.php';


// fetch scroll if id is inclusive in link
if (isset($_GET['id'])) {

  // sanitize id
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

  // fetch scroll
  $query = "SELECT * FROM scrolls WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $scroll =  mysqli_fetch_assoc($result);
} else {
  header('location: ' . ROOT_URL . 'admin/');
  die();
}



// fetch user details 
$tribesmen_id = $scroll['created_by'];
$tribesmen_query = "SELECT * FROM tribesmen WHERE id=$tribesmen_id";
$tribesmen_result = mysqli_query($connection, $tribesmen_query);
$tribesmen = mysqli_fetch_assoc($tribesmen_result);


?>






<main>

  <?php if (isset($_SESSION['comment'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['comment'];
        unset($_SESSION['comment']);
        ?>
      </p>
    </div>

  <?php elseif (isset($_SESSION['comment_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['comment_success'];
        unset($_SESSION['comment_success']);
        ?>
      </p>
    </div>


    <?php elseif (isset($_SESSION['delete_comment'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['delete_comment'];
        unset($_SESSION['delete_comment']);
        ?>
      </p>
    </div>

    <?php elseif (isset($_SESSION['delete_comment_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['delete_comment_success'];
        unset($_SESSION['delete_comment_success']);
        ?>
      </p>
    </div>
  <?php endif ?>





  <section class="dashboard">
    <div class="my_posts_contents">
      <div class="my_posts">
        <div class="post">
          <div class="user_details">
            <a href="profiles.php?id=<?= $tribesmen['id'] ?>">
              <div class="user_profile_pic">
                <img
                  src="../images/<?= htmlspecialchars($tribesmen['avatar']) ?>"
                  alt="User's profile picture." />
              </div>

              <div class="user_name">
                <h4>
                  <?= $tribesmen['username'] ?>
                </h4>
              </div>

              <?php
              // Get the list of users this user is following along with their follower count
              $query = "
    SELECT t.id, t.username, t.avatar, t.id,
           (SELECT COUNT(*) FROM followers WHERE followed = t.id) AS followers_count
    FROM followers f
    JOIN tribesmen t ON f.followed = t.id
    WHERE f.follower = $id
";

              $result = mysqli_query($connection, $query);

              $followers_count = 0;

              while ($row = mysqli_fetch_assoc($result)) {
                $followers_count = $row["followers_count"];
              }
              ?>


              <?php if ($followers_count >= 20): ?>
                <div class="verified">
                  <div class="verified_icon">
                    <i class="fa-solid fa-check"></i>
                  </div>
                  <div class="verified_desc">
                    <p>Verified</p>
                  </div>
                </div>
              <?php endif; ?>
            </a>

            <div class="user_details_post_time">
              <div class="post_date">
                <p>
                  <?= date("M d, Y", strtotime($scroll['created_at'])) ?>

                </p>
              </div>
              <div class="post_time">
                <p>
                  <?= date("H:i", strtotime($scroll['created_at'])) ?>
                </p>
              </div>
            </div>
          </div>



          <?php
          $images = array_filter(array_map('trim', explode(',', $scroll['images']))); // Remove empty/whitespace-only values
          if (!empty($images)) :
          ?>
            <div class="post_images_container">
              <div class="post_images">
                <?php foreach ($images as $image) : ?>
                  <img src="../images/<?= htmlspecialchars($image) ?>" alt="Post's image.">
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <div class="post_text">
            <p>
              <?= $scroll['user_post'] ?>

            </p>
          </div>

          <div class="post_reactions">
            <div class="post_reaction">
              <div class="post_reaction_icon">
                <div class="like_icons">
                  <div class="like_icon">
                    <i class="fa-regular fa-heart"></i>
                  </div>

                  <div class="like_icon_is_clicked">
                    <i class="fa-regular fa-heart"></i>
                  </div>
                </div>

                <p id="like_count">102</p>
              </div>

              <div class="post_reaction_desc">
                <p>Like</p>
              </div>
            </div>


            <?php
            include 'page_essentials/comment_count.php';
            ?>

            <div class="post_reaction">
              <div class="post_reaction_icon">
                <i class="fa-solid fa-retweet" id="repost_icon"></i>
                <p id="repost_count">98</p>
              </div>
              <div class="post_reaction_desc">
                <p>Repost</p>
              </div>
            </div>

            <div class="post_reaction">
              <div class="post_reaction_icon">
                <i class="fa-solid fa-share" id="share_icon"></i>
                <p id="share_count">12</p>
              </div>
              <div class="post_reaction_desc">
                <p>Share</p>
              </div>
            </div>

          </div>



          <form action="<?= ROOT_URL ?>admin/comment_logic.php?id=<?= $id ?>" method="post">
            <div class="comment_input">
              <div class="comment_field">
                <textarea name="user_comment" placeholder="Share your thoughts on this!"></textarea>
                <input type="text" name="confirm_human" placeholder="confirm_human" class="confirm_human">
                <input type="submit" name="Comment" value="Comment">
              </div>
            </div>
          </form>








          <?php

          if (isset($_GET['id'])) {
            $scroll_id = $_GET['id'];
            $scroll_id = mysqli_real_escape_string($connection, $scroll_id);


            $query = "
            SELECT c.*, t.id AS author_id, t.username AS author_name, t.avatar AS author_avatar
            FROM comments c
            JOIN tribesmen t ON c.tribesmen_id = t.id
            WHERE c.scroll_id = '$scroll_id'
            ORDER BY c.created_at DESC
        ";

            $result = mysqli_query($connection, $query);

            if ($result && mysqli_num_rows($result) > 0) :
              while ($comment = mysqli_fetch_assoc($result)) :
                $comment_date = date('D jS M, Y', strtotime($comment['created_at']));
                $comment_time = date('h:ia', strtotime($comment['created_at']));

                // Render individual comments below as usual
          ?>



                <div class="comment_section">
                  <div class="comment">
                    <div class="user_details">
                      <a href="<?= ROOT_URL ?>admin/profiles.php?id=<?= $comment['author_id'] ?>">

                        <div class="user_profile_pic">
                          <img
                            src="<?= ROOT_URL . 'images/' . $comment['author_avatar'] ?>"
                            alt="User's profile picture." />
                        </div>

                        <div class="user_name">
                          <h4><?= htmlspecialchars($comment['author_name']) ?></h4>

                        </div>

                        <div class="verified">
                          <div class="verified_icon">
                            <i class="fa-solid fa-check"></i>
                          </div>
                          <div class="verified_desc">
                            <p>Verified</p>
                          </div>
                        </div>
                      </a>

                      <div class="user_details_post_time">
                        <div class="post_date">
                          <p><?= $comment_date ?></p>
                        </div>
                        <div class="post_time">
                          <p><?= $comment_time ?></p>
                        </div>
                      </div>
                    </div>

                    <div class="comment_text">
                      <p><?= nl2br(htmlspecialchars($comment['user_comment'])) ?></p>


                      <?php if ($_SESSION['user_id'] == $comment['tribesmen_id']): ?>
                        <div class="post_reaction">
                          <div class="post_reaction_icon">
                            <a href="delete_comment_logic.php?id=<?= $comment['id'] ?>">
                              <i class="fa-solid fa-trash" id="delete_icon"></i>
                            </a>
                          </div>
                          <div class="post_reaction_desc">
                            <p>Delete</p>
                          </div>
                        </div>
                      <?php endif; ?>


                    </div>
                  </div>
                </div>


          <?php
              endwhile;
            else :
              echo "<p>No comments yet.</p>";
            endif;
          } else {
            echo "<p>Invalid scroll ID.</p>";
          }
          ?>

        </div>


      </div>
    </div>
  </section>
</main>



<?php
include 'partials/floating_input.php';


include '../partials/footer.php';
?>