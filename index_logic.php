<?php 
require 'config/database.php';


// if submit is clicked 
if (isset($_POST['submit'])) {
    // sanitize user input
    $telephone_or_username = filter_var($_POST['telephone_or_username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // validate user input 
    if (!$telephone_or_username) {
        $_SESSION['signin'] = 'Enter Username or Telephone!';
    } elseif (!$password) {
        $_SESSION['signin'] = 'Enter Password!';
    }elseif (!empty($confirm_human)) {
        $_SESSION['signup'] = 'Somethings Are Made For Humans Only!';
    } else {
        // fetch user from db
    }


} else {
    header('location: ' . ROOT_URL);
    die();
}