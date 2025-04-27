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

            <div class="post_reaction">
              <div class="post_reaction_icon" id="comment_icon">
                <i class="fa-regular fa-comment"></i>
                <p id="comment_count">21</p>
              </div>
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



          <div class="comment_input">
            <div class="comment_field">
              <textarea name="user_comment" placeholder="Share your thoughts here!"></textarea>
              <input type="submit" name="Comment" value="Comment">
            </div>
          </div>



          <div class="comment_section">
            <div class="comment">
              <div class="user_details">
                <a href="user_profile.php#my_posts">
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

              <div class="comment_text">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Id adipisci aut doloremque.
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci pariatur success, hic aliquam deleniti consequuntur corporis amet quasi aut officia.
                </p>
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