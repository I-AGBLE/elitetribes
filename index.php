<?php
include 'partials/header.php';


// get inputs from failed login
$telephone_or_username = $_SESSION['signin_data']['telephone_or_username'] ?? null;
$password = $_SESSION['signin_data']['password'] ?? null;;
$confirm_human = $_SESSION['signin_data']['confirm_human'] ?? null;;


?>
















<main>

<?php if (isset($_SESSION['signin'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['signin'];
        unset($_SESSION['signin']);
        ?>
      </p>
    </div>
  <?php endif ?>

  <div class="main_log">
    <div class="hero_section">
      <div class="hero_title">
        <h1>Let Us Hear From You!</h1>
      </div>

      <div class="hero_sub">
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste est exercitationem placeat accusantium dolore molestiae distinctio quod cum eaque vitae.
        </p>
      </div>
    </div>






    <form action="<?= ROOT_URL ?>index_logic.php" method="POST" >

    <div class="standard_login">
      <input type="text" name="telephone_or_username"  value="<?= $telephone_or_username ?>"   placeholder="Telephone or Username" autofocus>
      <input type="password" name="password"  value="<?= $password ?>"   placeholder="Password">
      <input type="text" name="confirm_human"  value="<?= $confirm_human ?>"  placeholder="confirm_human" class="confirm_human">
      <input type="submit" name="submit" value="Login">
    </div>

    </form>






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

        <div class="loginorout">
          <p>Don't have an account with us?</p>
          <a href="signup.php">Sign Up Today!</a>
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