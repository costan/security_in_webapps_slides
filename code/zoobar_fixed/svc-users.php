#!/usr/bin/php
<?php
  # To find any necessary require'd files
  chdir("/zoobar-ps");

  require_once("includes/db.php");
  require_once("includes/unixclient.php");

  $req = trim(fgets(STDIN));
  list($verb, $url_username, $url_password) = explode('&', $req);
  $username = urldecode($url_username);
  $password = urldecode($url_password);
  
  if ($verb == "create") {
  	# New user.
    $sql = "SELECT Username FROM Users WHERE Username='" .
           addslashes($username) . "'";
    $rs = $db->executeQuery($sql);
    if( $rs->next() ) {
    	echo "\n";  // User already exists
    }
    else {
# START:salting_create    	
	    $salt = substr(md5(rand()), 0, 4);
	    $hashedpassword = md5($password.$salt);
	    $sql = "INSERT INTO Users (Username, Password, Salt) " .
	           "VALUES ('" . addslashes($username) . "', " .
	           "'$hashedpassword', '$salt')";
	    $db->executeQuery($sql);
# END:salting_create
	    
	    # Create the bank account.
	    $c = new UnixClient("../banksvc/sock");
	    $c->call(urlencode($username) . "&10&&\n");
	    
	    echo urlencode("$username") . "\n";
    }
  }
  else if ($verb == "token") {
  	# Check token.
  	$token = $password;
  	
# START:token_check  	
    $sql = "SELECT * FROM Users WHERE " .
           "(Username = '" . addslashes($username) . "') " .
           "AND (Token = '" . addslashes($token) . "')";
    $rs = $db->executeQuery($sql);
    if ( $rs->next() ) {
# END:token_check
      echo urlencode($rs->getCurrentValueByName("Username")) . "\n";
    }
  	else {
  		echo "\n";
  	}
  }
  else if ($verb == "login") {
  	# User+password -> token.
  	
# START:salting_check 	
    $sql = "SELECT Salt FROM Users WHERE Username = '" .
           addslashes($username) . "'";
    $rs = $db->executeQuery($sql);
    $salt = $rs->getValueByNr(0,0);
    $hashedpassword = md5($password.$salt);
    $sql = "SELECT * FROM Users WHERE " .
           "Username = '" . addslashes($username) . "' AND " .
           "Password = '$hashedpassword'";
# END:salting_check
    $result = $db->executeQuery($sql);
    if ( $result->next() ) {
# START:token_create
      $token = md5($result->getCurrentValueByName("Password").mt_rand());
      
      $sql = "UPDATE Users SET Token = '$token' " .
             "WHERE Username='" . addslashes($username) . "'";
      $db->executeQuery($sql);
# END:token_create
      echo urlencode($token) . "\n";
    } else {
      echo "\n";
    }
  }
  else {
  	echo "\n";
  }
?>
