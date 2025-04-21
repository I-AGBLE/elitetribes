<?php
include 'partials/header.php';


// get inputs from failed registration
$username = $_SESSION['signup_data']['username'] ?? null;
$telephone = $_SESSION['signup_data']['telephone'] ?? null;;
$gender = $_SESSION['signup_data']['gender'] ?? null;;
$email = $_SESSION['signup_data']['email'] ?? null;;
$password = $_SESSION['signup_data']['password'] ?? null;;
$confirm_password = $_SESSION['signup_data']['confirm_password'] ?? null;;
$confirm_human = $_SESSION['signup_data']['confirm_human'] ?? null;;


// if all is fine
unset($_SESSION['signup_data']);

?>





 









<main>

<?php if (isset($_SESSION['signup'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['signup'];
        unset($_SESSION['signup']);
        ?>
      </p>
    </div>
  <?php endif ?>


  <div class="main_log">
    <div class="hero_section">
      <div class="hero_title">
        <h1>Sign Up To Get Your Voice Heard!</h1>
      </div>

      <div class="hero_sub">
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Iste est exercitationem placeat accusantium dolore molestiae distinctio quod cum eaque vitae.
        </p>
      </div>
    </div>




<form action="<?= ROOT_URL ?>signup_logic.php" enctype="multipart/form-data" method="POST">
<div class="standard_login">
    
    <input type="text" name="username" value="<?= $username ?>"  placeholder="Username" autofocus>
    <input type="tel" name="telephone" value="<?= $telephone ?>"   placeholder="Telephone">

    <select name="gender">
  <option value="" disabled <?= $gender == '' ? 'selected' : '' ?>>Gender</option>
  <option value="male" <?= $gender == 'male' ? 'selected' : '' ?>>Male</option>
  <option value="female" <?= $gender == 'female' ? 'selected' : '' ?>>Female</option>
  <option value="non-binary" <?= $gender == 'non-binary' ? 'selected' : '' ?>>Non-binary</option>
  <option value="prefer-not-to-say" <?= $gender == 'prefer-not-to-say' ? 'selected' : '' ?>>Prefer not to say</option>
</select>


    <input type="email" name="email" value="<?= $email ?>"  placeholder="Email">
    <input type="password" name="password" value="<?= $password ?>"   placeholder="Password">
    <input type="password" name="confirm_password" value="<?= $confirm_password ?>"  placeholder="Confirm Password">

    <label for="avatar">
          <i class="fa-solid fa-image"></i> </label>
        <input type="file" id="avatar" name="avatar" accept="image/*" multiple style="display: none;" />
        
    <style>
      label i {
        font-size: 1.5rem;
        cursor: pointer;
      }

      label i:hover {
        color: var(--color_warning);
      }
    </style>

    <input type="text" name="confirm_human" value="<?= $confirm_human ?>"  placeholder="confirm_human" >
    <input type="submit" name="submit" value="Register">
  </div>
</form>
 




    <div class="log_session">
      <div class="log_container">
        <div class="google">
          <a href="">
            <span><i class="fa-brands fa-google"></i></span>
            Sign up with Google
          </a>
        </div>

        <div class="apple">
          <a href="">
            <span><i class="fa-brands fa-apple"></i></span>
            Sign up with Apple
          </a>
        </div>

        <div class="loginorout">
          <p>Already have an account?</p>
          <a href="index.php">Login Now!</a>
        </div>
      </div>


    </div>



    <div class="extras">
      <p>
        Lorem ipsum dolor sit <a href="<?= ROOT_URL ?>about.php">About Us</a> amet consectetur adipisicing elit. Esse deleniti provident eveniet! <a href="<?= ROOT_URL ?>tnc.php">Terms And Conditions</a> Porro quasi omnis recusandae rem, unde ab ipsum.
      </p>
    </div>
  </div>
</main>






<?php
include 'partials/footer.php';
?>