#!/usr/bin/php-cgi
<?php

    require_once("login.php");
    require_once("includes/db.php");
    require_once("includes/auth.php");
    require_once("includes/navigation.php");
    
    // Init global variables
    $user = new User($db);
    
    if(validate_user($user)) {
?>
    
    var myZoobars = <?php 
          $sql = "SELECT Zoobars FROM Person WHERE Username='" .
                 addslashes($user->username) . "'";
          $rs = $db->executeQuery($sql);
          $balance = $rs->getValueByNr(0,0);
          echo $balance > 0 ? $balance : 0;
        ?>;
    var div = document.getElementById("myZoobars");
    
    if (div != null)
    {
        div.innerHTML = myZoobars;
    }
<?php
    }
?>
