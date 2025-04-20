<?php
include 'partials/header.php';

?>















<main>
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





    <div class="standard_login">
    <input type="text" name="username" id="username" placeholder="Username" autofocus>
    <input type="tel" name="telephone" id="telephone" placeholder="Telephone">
    
    <select name="gender" id="gender">
  <option value="" disabled selected>Gender</option>
  <option value="male">Male</option>
  <option value="female">Female</option>
  <option value="non-binary">Non-binary</option>
  <option value="prefer-not-to-say">Prefer not to say</option>
</select>

    <input type="email" name="email" id="email" placeholder="Email">
      <input type="password" name="password" id="password" placeholder="Password">
      <input type="confirm_password" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
      <input type="text" name="confirm_human" id="confirm_human" placeholder="confirm_human" class="confirm_human">
      <input type="submit" name="submit" value="Login">
    </div>




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
        Lorem ipsum dolor sit <a href="about.php">About Us</a> amet consectetur adipisicing elit. Esse deleniti provident eveniet! <a href="tnc.php">Terms And Conditions</a> Porro quasi omnis recusandae rem, unde ab ipsum.
      </p>
    </div>
  </div>
</main>






<?php
include 'partials/footer.php';
?>