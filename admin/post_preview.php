<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />


    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="theme-color" content="#111111"> <!-- Or your navbar color -->

    <meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">


    <title>From Void To Signal ...</title>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />

    <link rel="stylesheet" href="../css/styles.css" />

    <script src="../js/main.js" defer></script>
  </head>

  <body>
    <nav>
      <div class="user_details">
        <a href="user_profile.html#my_posts">
          <div class="user_profile_pic">
            <img
              src="../images/profile_pic.png"
              alt="User's profile picture."
            />
          </div>

          <div class="user_name">
            <h4>Khadi Khole</h4>
          </div>
        </a>
      </div>

      <div class="nav_logo">
        <a href="index.html"
          ><p>From</p>
          <h4>VoidTo<span>Signal</span></h4>
        </a>
      </div>

      <div class="nav_items">
        <ul>
          <li>
            <a href="index.html">Home</a>
          </li>

          <li>
            <a href="#">Contact Us</a>
          </li>
        </ul>

        <div class="nav_access">
          <ul>
            <li id="login">
              <a href="#">Login</a>
            </li>

            <li id="logout">
              <a href="../index.html">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>



    <main>
      <section class="dashboard">
        <div class="my_posts_contents">
          <div class="my_posts">
            <div class="post">
              <div class="user_details">
                <a href="user_profile.html#my_posts">
                  <div class="user_profile_pic">
                    <img
                      src="../images/profile_pic.png"
                      alt="User's profile picture."
                    />
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

          

              <div class="post_images_container">
                <div class="post_images">
                  <img src="../images/profile_pic.png" alt="Post's image." />
                  <img src="../images/pic.png" alt="Post's image." />
                  <img src="../images/pic1.png" alt="Post's image." />
                  <img src="../images/profile_pic.png" alt="Post's image." />
                  <img src="../images/pic.png" alt="Post's image." />
                </div>
              </div>

              <div class="post_text">
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam a dolorum amet totam explicabo dolor voluptatum iusto blanditiis consequuntur. Sint molestiae similique esse dolor neque corporis necessitatibus amet modi ipsam tempora eligendi consequuntur natus, quas, optio placeat? Asperiores facilis incidunt provident exercitationem impedit saepe quisquam aut similique excepturi at laboriosam neque, autem error pariatur itaque, dolores fuga minus sunt voluptatum.</p>
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
                    <a href="user_profile.html#my_posts">
                      <div class="user_profile_pic">
                        <img
                          src="../images/profile_pic.png"
                          alt="User's profile picture."
                        />
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
                      Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci pariatur error, hic aliquam deleniti consequuntur corporis amet quasi aut officia.
                    </p>
                  </div>
                </div>

             
              </div>              
            </div>

           
          </div>
        </div>
      </section>
    </main>



    <div class="floating_input">
      <div class="floating_post_input" style="display: none;">
        <div class="post_field">
            <textarea name="user_post" placeholder="Share your thoughts here!"></textarea>
          
            <div class="post_actions">
           
              <label for="image-upload" style="cursor: pointer;">
                <i class="fa-solid fa-image"></i> </label>
              <input type="file" id="image-upload" name="images[]" accept="image/*" multiple style="display: none;" />
              
              <input type="submit" name="Post" value="Post">
            </div>
        </div>
      </div>


      <div class="floating_icons">
        <div class="open_floating_input">
          <i class="fa-solid fa-plus"></i>
        </div>

        <div class="close_floating_input" style="display: none;">
          <div class="close">
            <i class="fa-solid fa-minus"></i>
          </div>
        </div>
      </div>
    </div>




    <section class="footer">
      <center>
        <footer>
          <a href="#">VoidToSignal&trade; v1.0 Copyright &copy2025</a>
        </footer>
      </center>
    </section>
  </body>
</html>
