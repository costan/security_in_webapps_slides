<?php

// Cookie-based authentication logic

class User {
  var $db = null;
  var $username = null;
  var $cookieName = "ZoobarLogin";

  // This function is called from other code
  function User(&$db) {
    $this->db = $db;
#START:token_cookie    
    if ( isset($_COOKIE[$this->cookieName]) ) {
	    $arr = unserialize(base64_decode($_COOKIE[$this->cookieName]));
	    list($username, $token) = $arr;
	    if (!$username or !$token) {
	      return;
	    }
      return $this->_checkToken($username, $token);
    }
#END:token_cookie
  }

  // This function is called from other code.
  function _checkToken($username, $token) {
    # Check the user's token.
    $c = new UnixClient("../usersvc/sock");
    $r = $c->call("token&" . urlencode($username) . "&" . urlencode($token) .
                  "\n");
    if ($r == urlencode($username)) {
      $this->username = $username;
      $this->token = $token;
      return true;
    }
  	return false;
  }

  // This function is called from other code
  function _checkLogin($username, $password) {
    # Check the user's token.
    $c = new UnixClient("../usersvc/sock");
    $r = $c->call("login&" . urlencode($username) . "&" . urlencode($password) .
                  "\n");
    if ($r != "") {
      $this->username = $username;
      $this->token = $token = urldecode($r);

#START:token_set_cookie
      $arr = array($username, $token);
      $cookieData = base64_encode(serialize($arr));
      setcookie($this->cookieName, $cookieData, time() + 31104000);
#END:token_set_cookie
      return true;
    }
    else {
    	return false;
    }
  } 
	
  // This function is called from other code
  function _addRegistration($username, $password) {
    # Check the user's token.
    $c = new UnixClient("../usersvc/sock");
    $r = $c->call("create&" . urlencode($username) . "&" .
                  urlencode($password) . "\n");
    if ($r != urlencode($username)) {
    	return false; // User already exists
    }
    
    $sql = "INSERT INTO Person (Username) " .
           "VALUES ('" . addslashes($username) . "')";
    $this->db->executeQuery($sql);
    
    return $this->_checkLogin($username, $password);
  }
	
  // This function is called from other code
  function _logout() {
    if(isset($_COOKIE[$this->cookieName]))
      setcookie($this->cookieName);
    $this->username = null;
  }
}
?>
