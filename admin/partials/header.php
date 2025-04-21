<?php
require 'config/database.php';


header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0'); // Proxies

if(!isset($_SESSION['user_id'])) {
    header('location: ' . ROOT_URL);
    die(); 
}


// fetch user details 
if (isset($_SESSION['user_id'])) {
    $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT username, avatar FROM tribesmen WHERE id=$id";
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

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <!-- styles -->
    <link rel="stylesheet" href="../css/styles.css" />

    <!-- js -->
    <script src="../js/main.js" defer></script>
    <script src="../js/user_profile.js" defer></script>
    <script src="../js/index.js" defer></script>
</head>









<body>
    <nav>
        <div class="user_details">
            <a href="user_profile.php#my_posts">
                <div class="user_profile_pic">
                <img src="../images/<?= htmlspecialchars($user_detail['avatar']) ?>" alt="User's profile picture" />

                </div>

                <div class="user_name">
                    <h4><?= $user_detail['username'] ?></h4>
                </div>
            </a>
        </div>


        
        <div class="nav_logo">
            <a href="<?= ROOT_URL ?>admin#open_scrolls_contents">
                <h4>elite<span>Tribes</span></h4>
            </a>
        </div>





        <div class="nav_items">
            <ul>
                <li>
                    <a href="<?= ROOT_URL ?>admin">Home</a>
                </li>

                <li>
                    <a href="<?= ROOT_URL ?>/contact.php">Contact Us</a>
                </li>
            </ul>

            <div class="nav_access">
                <ul>
                    <li id="logout">
                        <a href="../logout_logic.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>