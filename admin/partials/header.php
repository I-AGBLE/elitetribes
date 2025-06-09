<?php
require 'config/database.php';


header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0'); // Proxies

if (!isset($_SESSION['user_id'])) {
  header('location: ' . ROOT_URL);
  die();
}


// fetch user details 
if (isset($_SESSION['user_id'])) {
  $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
  $query = "SELECT username, avatar, followers FROM tribesmen WHERE id=$id";
  $result = mysqli_query($connection, $query);
  $user_detail = mysqli_fetch_assoc($result);
}

?>




<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

  <!-- status bar color style -->
  <meta name="theme-color" content="#111111">

  <!-- apple status bar color style -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <!-- browser title -->
  <title>From Void To Signal ...</title>

  <!-- styles -->
  <link rel="stylesheet" href="../css/styles.css" />

  <!-- font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />



  <!-- js -->
  <script src="../js/main.js" defer></script>
  <script src="../js/user_profile.js" defer></script>
  <script src="../js/index.js" defer></script>
  <script src="../js/image_grab_n_scroll.js" defer></script>
  <script src="../js/header_menu.js" defer></script>

</head>









<body>
  <nav>
    <div class="user_details" style="background-color: transparent;">
      <a href="user_profile.php#my_posts">
        <div class="user_profile_pic">
          <img src="../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />

        </div>

        <div class="user_name">
          <h4><?= $user_detail['username'] ?></h4>
        </div>


        <?php
        include './page_essentials/followers_count.php';
        ?>



      </a>
    </div>


    <div class="nav_logo" id="main_nav_logo">
      <div class="logo_wrapper">
        <div class="logo">
          <div class="logo_name">
            <h4>elite<span>Tribes</span></h4>
          </div>
          <div class="menu_icon">
            <i class="fa-solid fa-bars"></i>
          </div>
        </div>

        <div class="header_menus">
          <div class="header_menu_items">
            <ul>
              <li><a href="<?= ROOT_URL ?>admin#open_scrolls_contents">Home</a></li>
              <li><a href="user_profile.php#my_posts">My Profile</a></li>
              <li><a href="#">About Us</a></li>
              <li><a href="../logout_logic.php" id="logout">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>




  </nav>