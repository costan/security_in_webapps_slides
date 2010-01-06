<?php

// Cookie-based authentication logic

class User {
  var $db = null;
  var $username = null;
  var $cookieName = "ZoobarLogin";

  // This function is called from other code
  function User(&$db) {
    $this->db = $db;
# START:plain_cookie_check
    if ( isset($_COOKIE[$this->cookieName]) ) {
	    $username = $_COOKIE[$this->cookieName];
	    $sql = "SELECT * FROM Person WHERE " .
      	     "(Username = '" . addslashes($username) . "') ";
	    $rs = $this->db->executeQuery($sql);
	    if ( $rs->next() ) {
# END:plain_cookie_check
	      $this->username = $rs->getCurrentValueByName("Username");
	    }
    }
  } 

  // This function is called from other code
  function _checkLogin($username, $password) {
    $sql = "SELECT Salt FROM Person WHERE Username = '" .
	   addslashes($username) . "'";
    $rs = $this->db->executeQuery($sql);
    $salt = $rs->getValueByNr(0,0);
    $hashedpassword = md5($password.$salt);
    $sql = "SELECT * FROM Person WHERE " .
           "Username = '" . addslashes($username) . "' AND " .
           "Password = '$hashedpassword'";
# START:plain_cookie_set
    $result = $this->db->executeQuery($sql);
    if ( $result->next() ) {
    	$this->username = $username;
      setcookie($this->cookieName, $this->username, time() + 31104000);
      return true;
# END:plain_cookie_set
    } else {
      return false;
    }
  } 
	
  // This function is called from other code
  function _addRegistration($username, $password) {
    $sql = "SELECT Username FROM Person WHERE Username='" .
	   addslashes($username) . "'";
    $rs = $this->db->executeQuery($sql);
    if( $rs->next() ) return false;  // User already exists
    $salt = substr(md5(rand()), 0, 4);
    $hashedpassword = md5($password.$salt);
    $sql = "INSERT INTO Person (Username, Password, Salt) " .
           "VALUES ('" . addslashes($username) . "', " .
	   "'$hashedpassword', '$salt')";
    $this->db->executeQuery($sql);
    return $this->_checkLogin($username, $password);
  }
	
  // This function is called from other code
  function _logout() {
    if(isset($_COOKIE[$this->cookieName])) setcookie($this->cookieName);
    $this->username = null;
  }	
}
?>
