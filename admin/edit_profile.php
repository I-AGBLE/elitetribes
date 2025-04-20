<?php
include 'partials/header.php';
?>











<main>


  <div class="alert_message error" id="alert_message">
    <p>
      Update Failed!
    </p>
  </div>



  <div class="main_log">

    <div class="editing_profile">
      <div class="editing_title">
        <h2>Editing Profile For Good.</h2>
      </div>

      <div class="editing_sub">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore magni consectetur tempora vel aspernatur illum!</p>
      </div>
    </div>


    <div class="post_field">

      <input type="text" name="" id="" placeholder="Username" autofocus>

      <textarea name="" id="" placeholder="About me."></textarea>

      <input type="file" name="">
    </div>

    <input type="submit" name="Post" value="Update">


  </div>
</main>









<?php
include '../partials/footer.php';
?>