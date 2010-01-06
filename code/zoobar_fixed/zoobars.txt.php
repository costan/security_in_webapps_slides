#!/usr/bin/php-cgi
<?php

    require_once("login.php");
    require_once("includes/db.php");
    require_once("includes/unixclient.php");
    require_once("includes/auth.php");
    require_once("includes/navigation.php");
    
    // Init global variables
    $user = new User($db);
    
    if(validate_user($user)) {
      $sql = "SELECT Zoobars FROM Bank WHERE Username='" .
             addslashes($user->username) . "'";
      $rs = $db->executeQuery($sql);
      $balance = $rs->getValueByNr(0,0);
      echo $balance > 0 ? $balance : 0;
    }
?>
