<?php
include 'partials/header.php';


// fetch user detail 

if (isset($_GET['id'])) {
  //  sanitize id 
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT * FROM tribesmen WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $tribesmen = mysqli_fetch_assoc($result);


  $about = $tribesmen['about'];
  $gender = $tribesmen['gender'];
  $confirm_human = $tribesmen['confirm_human'];


} else {
  header("location: . 'admin/' ");
  die();
}




?>











<main>


<?php if (isset($_SESSION['edit_profile'])) : ?>
    <div class="alert_message error" id="alert_message">
      <p>
        <?= $_SESSION['edit_profile'];
        unset($_SESSION['edit_profile']);
        ?>
      </p>
    </div>
  <?php endif ?>



  <div class="main_log">

    <div class="editing_profile">
      <div class="editing_title">
        <h2>Editing Profile For Good.</h2>
      </div>

      <div class="editing_sub">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore magni consectetur tempora vel aspernatur illum!</p>
      </div>
    </div>






    <form action="<?= ROOT_URL?>admin/edit_profile_logic.php" enctype="multipart/form-data" method="post">
      <div class="post_field">

      <input type="hidden" name="id" value="<?= $tribesmen['id'] ?>">
      <input type="hidden" name="previous_avatar" value="<?= $tribesmen['avatar'] ?>">

        <input type="text" name="username" value="<?= $tribesmen['username'] ?>" placeholder="Username" autofocus>

        <input type="tel" name="telephone" value="<?= $tribesmen['telephone'] ?>" placeholder="Telephone">

        <select name="gender">
          <option value="" disabled <?= $gender == '' ? 'selected' : '' ?>>Gender</option>
          <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
          <option value="Non-binary" <?= $gender == 'Non-binary' ? 'selected' : '' ?>>Non-binary</option>
          <option value="Prefer not to say" <?= $gender == 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
        </select>

        <input type="email" name="email"  value="<?= $tribesmen['email'] ?>" placeholder="Email">


        <textarea name="about" placeholder="About me."><?= htmlspecialchars($about) ?></textarea>
        
        <label for="avatar">
          <i class="fa-solid fa-image"></i> </label>
        <input type="file" id="avatar" name="avatar" multiple style="display: none;" />

        <input type="text" name="confirm_human" value="<?= $confirm_human ?>" placeholder="confirm_human">

      </div>

      <input type="submit" name="submit" value="Update">
    </form>



  </div>
</main>









<?php
include '../partials/footer.php';
?>