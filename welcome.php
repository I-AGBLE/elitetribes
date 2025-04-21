<?php
include 'partials/header.php';

?>
















<main>

<?php if (isset($_SESSION['signup_success'])) : ?>
    <div class="alert_message success" id="alert_message">
      <p>
        <?= $_SESSION['signup_success'];
        unset($_SESSION['signup_success']);
        ?>
      </p>
    </div>
  <?php endif ?>

  <div class="main_log">
    <div class="hero_section">
      <div class="hero_title">
        <h1>You Are Welcome!</h1>
      </div>

      <div class="hero_sub">
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste est exercitationem placeat accusantium dolore molestiae distinctio quod cum eaque vitae.
        </p>
      </div>
    </div>


    <div class="standard_login">
      <input type="tel" name="telephone" id="telephone" placeholder="Telephone" autofocus>
      <input type="password" name="password" id="password" placeholder="Password">
      <input type="text" name="confirm_human" id="confirm_human" placeholder="confirm_human" class="confirm_human">
      <input type="submit" name="submit" value="Login">
    </div>





    <div class="log_session">
      <div class="log_container">
        <div class="google">
          <a href="admin/index.php">
            <span><i class="fa-brands fa-google"></i></span>
            Log in with Google
          </a>
        </div>

        <div class="apple">
          <a href="">
            <span><i class="fa-brands fa-apple"></i></span>
            Log in with Apple
          </a>
        </div>
      </div>


    </div>



    <div class="extras">
      <p>
        Lorem ipsum dolor sit <a href="<?= ROOT_URL ?>about.php">About Us</a> amet consectetur adipising elit. Esse deleniti provident eveniet! <a href="<?= ROOT_URL ?>tnc.php">Terms And Conditions</a> Porro quasi omnis recusandae rem, unde ab ipsum.
      </p>
    </div>
  </div>
</main>





<?php
include 'partials/footer.php';
?>