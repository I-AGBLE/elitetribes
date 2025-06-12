<?php
include 'partials/header.php';

// CSRF protection: generate token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// get inputs from failed registration, sanitize for output
$username = isset($_SESSION['signup_data']['username']) ? htmlspecialchars($_SESSION['signup_data']['username'], ENT_QUOTES, 'UTF-8') : null;
$telephone = isset($_SESSION['signup_data']['telephone']) ? htmlspecialchars($_SESSION['signup_data']['telephone'], ENT_QUOTES, 'UTF-8') : null;
$gender = isset($_SESSION['signup_data']['gender']) ? htmlspecialchars($_SESSION['signup_data']['gender'], ENT_QUOTES, 'UTF-8') : null;
$about = isset($_SESSION['signup_data']['about']) ? htmlspecialchars($_SESSION['signup_data']['about'], ENT_QUOTES, 'UTF-8') : null;
$email = isset($_SESSION['signup_data']['email']) ? htmlspecialchars($_SESSION['signup_data']['email'], ENT_QUOTES, 'UTF-8') : null;
$password = isset($_SESSION['signup_data']['password']) ? htmlspecialchars($_SESSION['signup_data']['password'], ENT_QUOTES, 'UTF-8') : null;
$confirm_password = isset($_SESSION['signup_data']['confirm_password']) ? htmlspecialchars($_SESSION['signup_data']['confirm_password'], ENT_QUOTES, 'UTF-8') : null;
$confirm_human = isset($_SESSION['signup_data']['confirm_human']) ? htmlspecialchars($_SESSION['signup_data']['confirm_human'], ENT_QUOTES, 'UTF-8') : null;

// if all is fine
unset($_SESSION['signup_data']);
?>

<main  id="public_main">

  <?php if (isset($_SESSION['signup'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= htmlspecialchars($_SESSION['signup'], ENT_QUOTES, 'UTF-8');
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

    <form action="<?= ROOT_URL ?>signup_logic.php" enctype="multipart/form-data" method="POST" autocomplete="off">
      <!-- CSRF token for security -->
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="standard_login">

        <input type="text" id="username" name="username" value="<?= $username ?>" placeholder="Username" maxlength="50" pattern="[A-Za-z0-9_ ]{3,50}"  autofocus>
        <input type="tel" name="telephone" value="<?= $telephone ?>" placeholder="Telephone" maxlength="20" pattern="[0-9+\-\s]{7,20}" >

        <select name="gender" >
          <option value="" disabled <?= $gender == '' ? 'selected' : '' ?>>Gender</option>
          <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Non-binary" <?= $gender == 'Non-binary' ? 'selected' : '' ?>>Non-binary</option>
          <option value="Prefer not to say" <?= $gender == 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
        </select>

        <textarea name="about" placeholder="Tell us about yourself." maxlength="500"><?= $about ?></textarea>

        <input type="email" name="email" value="<?= $email ?>" placeholder="Email" maxlength="100" >
        <input type="password" name="password" value="<?= $password ?>" placeholder="Password" minlength="8" maxlength="100"  autocomplete="new-password">
        <input type="password" name="confirm_password" value="<?= $confirm_password ?>" placeholder="Confirm Password" minlength="8" maxlength="100"  autocomplete="new-password">

        <label for="avatar">
          <i class="fa-solid fa-image"></i>
        </label>
        <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;" />

        <style>
          label i {
            font-size: 1.5rem;
            cursor: pointer;
          }

          label i:hover {
            color: var(--color_warning);
          }
        </style>

        <input type="text" name="confirm_human" class="confirm_human" value="<?= $confirm_human ?>" placeholder="confirm_human" maxlength="100" >
       
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