<?php
require 'config/constants.php';

//destroy all sessions and redirect to logout
session_unset();
session_destroy();

header('Location: ' . ROOT_URL . 'logout.php');
die();  
