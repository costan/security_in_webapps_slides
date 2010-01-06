<?php

require_once("db.php");
require_once("login.php");
require_once("auth.php");
require_once("navigation.php");

// Allow users to use the back button without reposting data
header ("Cache-Control: private");

// Init global variables
$user = new User($db);

// Disable deprecated magic_quotes_gpc
if (get_magic_quotes_gpc()) {
    function mq_stripslashes(&$value, $key) { $value = stripslashes($value); }
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'mq_stripslashes');
}

// Check for logout and maybe display login page
if($_GET['action'] == 'logout') { 
  $user->_logout();
  display_login();
  exit();
}

// Validate user and maybe display login page
if(!validate_user($user)) {
  display_login();
  exit();
}

?>
