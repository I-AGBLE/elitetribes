<?php
include 'partials/header.php';


// get input from failed post 
$user_post = $_SESSION['add_post_data']['user_post'] ?? null;
$confirm_human = $_SESSION['add_post_data']['confirm_human'] ?? null;;

// if all is fine
unset($_SESSION['add_post_data']);




// fetch data from scrolls for open_scrolls page 
$open_scrolls_query = "SELECT * FROM scrolls ORDER BY created_at DESC";
$open_scrolls = mysqli_query($connection, $open_scrolls_query);

?>





<main>
  <?php if (isset($_SESSION['signin_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['signin_success'];
        unset($_SESSION['signin_success']);
        ?>
      </p>
    </div>

  <?php elseif (isset($_SESSION['add_post'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['add_post'];
        unset($_SESSION['add_post']);
        ?>
      </p>
    </div>
  <?php endif ?>





  <form action="<?= ROOT_URL ?>admin/add_post_logic.php" enctype="multipart/form-data" method="POST">
    <div class="post_input">
      <div class="post_field">
        <textarea name="user_post" placeholder="Share your thoughts here!"><?= htmlspecialchars($user_post) ?></textarea>

        <div class="post_actions">

          <label for="image-upload">
            <i class="fa-solid fa-image"></i> </label>
          <input type="file" id="image-upload" name="images[]" accept="image/*" multiple style="display: none;" />

          <input type="text" name="confirm_human" value="<?= $confirm_human ?>" placeholder="confirm_human">

          <input type="submit" name="submit" value="Post">

          <style>
            label i {
              font-size: 1.5rem;
              cursor: pointer;
            }

            label i:hover {
              color: var(--color_warning);
            }
          </style>
        </div>
      </div>
    </div>
  </form>





  <section class="dashboard">


    <div class="open_scrolls_contents" id="open_scrolls_contents" style="display: block;">

      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#open_scrolls_contents" id="open_scrolls" style="color: var(--color_warning);">Open Scrolls</a>
              <a href="#my_timeline" id="timeline">My Timeline</a>
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

        <?php while ($scroll = mysqli_fetch_assoc($open_scrolls)): ?>
          <div class="post">
            <div class="user_details">

              <?php
              // fetch user details 

              $tribesmen_id = $scroll['created_by'];
              $tribesmen_query = "SELECT * FROM tribesmen WHERE id=$tribesmen_id";
              $tribesmen_result = mysqli_query($connection, $tribesmen_query);
              $tribesmen = mysqli_fetch_assoc($tribesmen_result);
              ?>
              <a href="user_profile.php">
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

                <?php if ($tribesmen['followers'] > 20): ?>
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

            <div class="post_text">
              <a href="post_preview.php">
                <p>
                  <?= substr($scroll['user_post'], 0, 300) . '...' ?>
                </p>
              </a>
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


              <div class="post_reaction">
                <a href="post_preview.php">
                  <div class="post_reaction_icon" id="comment_icon">
                    <i class="fa-regular fa-comment" id="comment_icon"></i>
                    <p id="comment_count">21</p>
                  </div>
                </a>
                <div class="post_reaction_desc">
                  <p>Comment</p>
                </div>
              </div>

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
          </div>
      </div>

    <?php endwhile ?>
    </div>










    <div class="my_timeline" id="my_timeline" style="display: none;">

      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#open_scrolls_contents" id="open_scrolls">Open Scrolls</a>
              <a href="#my_timeline" id="timeline" style="color: var(--color_warning);">My Timeline</a>
            </div>
          </div>
        </div>
      </div>

      <div class="search_box">
        <center>
          <input type="text" placeholder="Search Timeline" id="search_box_for_my_timeline">
        </center>
      </div>

      <div class="my_posts">
        <div class="post">
          <div class="user_details">
            <a href="user_profile.php">
              <div class="user_profile_pic">
                <img
                  src="../images/profile_pic.png"
                  alt="User's profile picture." />
              </div>

              <div class="user_name">
                <h4>Khadi Khole</h4>
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
                <p>Thurs 12th Dec, 2024</p>
              </div>
              <div class="post_time">
                <p>01:32pm</p>
              </div>
            </div>
          </div>

          <div class="post_text">
            <a href="post_preview.php">
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic debitis doloribus maiores blanditiis beatae nesciunt inventore vero voluptatum harum molestias dicta mollitia eveniet aspernatur voluptatibus quia porro possimus optio aliquid ullam neque obcaecati, fugit ipsum quidem nisi. Repudiandae quos sit illum facilis nihil blanditiis illo mollitia dicta? Officiis, aliquam quod!...
              </p>
            </a>
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

            <div class="post_reaction">
              <a href="post_preview.php">
                <div class="post_reaction_icon" id="comment_icon">
                  <i class="fa-regular fa-comment" id="comment_icon"></i>
                  <p id="comment_count">21</p>
                </div>
              </a>
              <div class="post_reaction_desc">
                <p>Comment</p>
              </div>
            </div>

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
        </div>



        <div class="post">
          <div class="user_details">
            <a href="user_profile.php">
              <div class="user_profile_pic">
                <img
                  src="../images/profile_pic.png"
                  alt="User's profile picture." />
              </div>

              <div class="user_name">
                <h4>Khadi Khole</h4>
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
                <p>Thurs 12th Dec, 2024</p>
              </div>
              <div class="post_time">
                <p>01:32pm</p>
              </div>
            </div>
          </div>

          <div class="post_text">
            <a href="post_preview.php">
              <p>
                Lorem ipsum dolor s!...
              </p>
            </a>
          </div>

          <div class="post_images_container">
            <div class="post_images">
              <img src="../images/profile_pic.png" alt="Post's image.">
              <img src="../images/pic.png" alt="Post's image.">
              <img src="../images/pic1.png" alt="Post's image.">
              <img src="../images/profile_pic.png" alt="Post's image.">
              <img src="../images/pic.png" alt="Post's image.">
            </div>
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


            <div class="post_reaction">
              <a href="post_preview.php">
                <div class="post_reaction_icon" id="comment_icon">
                  <i class="fa-regular fa-comment" id="comment_icon"></i>
                  <p id="comment_count">21</p>
                </div>
              </a>
              <div class="post_reaction_desc">
                <p>Comment</p>
              </div>
            </div>

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
        </div>



      </div>
    </div>
  </section>




</main>



<?php
include 'partials/floating_input.php';


include '../partials/footer.php';
?>