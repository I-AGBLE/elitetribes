<?php
include 'partials/header.php';



// fetch user details 
if (isset($_SESSION['user_id'])) {
  $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT * FROM tribesmen WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $user_detail = mysqli_fetch_assoc($result);
}

?>





<main>

  <div class="alert_message success" id="alert_message">
    <p>
      Update Successful.
    </p>
  </div>


  <div class="user_section">
    <div class="user_information">
      <div class="user_picture">
        <img src="../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />
      </div>

      <div class="user_info">
        <div class="name">
          <h3><?= $user_detail['username'] ?></h3>

          
          <?php if ($user_detail['followers'] > 20): ?>
            <div class="verified">
              <div class="verified_icon">
                <i class="fa-solid fa-check"></i>
              </div>
              <div class="verified_desc">
                <p>Verified</p>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="about">
          <p><?= $user_detail['about'] ?></p>
        </div>

        <div class="followers_and_posts">
          <p>Followers: <span> <?= $user_detail['followers'] ?></span></p>
        </div>

        <div class="user_action_buttons">
          <div class="follow">
            <a href="#" id="default_btn">Follow</a>
            <a href="#" id="danger_btn" style="display: none;">Following</a>
          </div>

          <div class="post_reaction">
            <div class="post_reaction_icon">
              <a href="edit_profile.php">
                <i class="fa-solid fa-pen" id="edit_icon"></i>
              </a>
            </div>
            <div class="post_reaction_desc">
              <p>Edit</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <section class="dashboard">



    <div class="my_posts_contents" id="my_posts_contents" style="display: block;">

      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#my_posts" id="my_posts" style="color: var(--color_warning);">My Posts</a>
              <a href="#feed" id="my_feed">My Timeline</a>
              <a href="#following" id="my_following">Following</a>
            </div>
          </div>
        </div>
      </div>


      <div class="search_box">
        <center>
          <input type="text" placeholder="Search Posts" id="my_post_search_box">
        </center>
      </div>

      <div class="my_posts">
        <div class="post">
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






    <div class="feed" id="feed" style="display: none;">
      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#my_posts" id="my_posts">My Posts</a>
              <a href="#feed" id="my_feed" style="color: var(--color_warning);">My Timeline</a>
              <a href="#following" id="my_following">Following</a>
            </div>
          </div>
        </div>
      </div>


      <div class="search_box">
        <center>
          <input type="text" placeholder="Search Timeline" id="my_feed_search_box">
        </center>
      </div>

      <div class="my_posts">
        <div class="post">
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
            <a href="#">
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






    <div class="following" id="following" style="display: none;">
      <div class="my_dashboard">
        <div class="my_dashboard_title">
          <div class="dashboard_small_titles">
            <div class="my_posts_links">
              <a href="#my_posts" id="my_posts">My Posts</a>
              <a href="#feed" id="my_feed">My Timeline</a>
              <a href="#following" id="my_following" style="color: var(--color_warning);">Following</a>
            </div>
          </div>
        </div>
      </div>


      <div class="search_box">
        <center>
          <input type="text" placeholder="Search Following" id="my__following_box">
        </center>
      </div>



      <div class="followings">
        <div class="post">
          <div class="user_details">
            <a href="">
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