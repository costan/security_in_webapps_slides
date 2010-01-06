#!/usr/bin/php
<?php
  # To find any necessary require'd files
  chdir("/zoobar-ps");

  require_once("includes/auth.php");
  require_once("includes/db.php");
  require_once("includes/unixclient.php");

  $req = trim(fgets(STDIN));
  list($url_recipient, $url_amount, $url_sender, $url_token) =
      explode('&', $req);  
  $sender = urldecode($url_sender);
  $recipient = urldecode($url_recipient);
  $token = urldecode($url_token);
  $zoobars = (int)$url_amount;

  if ($sender == '') {
  	# Create account.
    $sql = "INSERT INTO Bank (Username, Zoobars) " .
           "VALUES ('" . addslashes($recipient) . "', " . addslashes($zoobars) .
           ")";
    $db->executeQuery($sql);
  }
  else {
  	# Transfer cash.
  	
    $sql = "SELECT Zoobars FROM Bank WHERE Username='" .
           addslashes($sender) . "'";
    $rs = $db->executeQuery($sql);
    $sender_balance = $rs->getValueByNr(0,0) - $zoobars;

    $sql = "SELECT Username, Zoobars FROM Bank WHERE Username='" .
     addslashes($recipient) . "'";
    $rs = $db->executeQuery($sql);
    $recipient_exists = $rs->getValueByNr(0,0);
    $recipient_balance = $rs->getValueByNr(0,1) + $zoobars;

    $user = new User($db);
    if ($user->_checkToken($sender, $token) && $sender_balance >= 0
        && $recipient_exists && $sender != $recipient && $zoobars > 0) {
      $sql = "UPDATE Bank SET Zoobars = $sender_balance " .
             "WHERE Username='" . addslashes($sender) . "'";
      $db->executeQuery($sql);
      $sql = "UPDATE Bank SET Zoobars = $recipient_balance " .
             "WHERE Username='" . addslashes($recipient) . "'";
      $db->executeQuery($sql);
            
      # Log the transfer via the logger service.
      $c = new UnixClient("../loggersvc/sock");
      $r = $c->call(urlencode($sender) . "&" . urlencode($recipient) .
                    "&$zoobars\n");
      
      echo "Sent $zoobars zoobars\n";            
    }
    else if ($sender_balance < 0) {
      echo "You only have " . ($sender_balance + $zoobars) . 
           " zoobars. You can't transfer $zoobars zoobars.\n";      
    }
    else if ($zoobars <= 0) {
      echo "You can't transfer " . $zoobars . " zoobars. Please try to " .
           "transfer at least 1 zoobar next time.\n";
    }    
    else if ($sender == $recipient) {
      echo "There's no point in transferring zoobars to yourself.\n";
    }
    else {
      echo "Transfer to $recipient failed.\n";
    }
  }
?>
