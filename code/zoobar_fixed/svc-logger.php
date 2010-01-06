#!/usr/bin/php
<?php
  # To find any necessary require'd files
  chdir("/zoobar-ps");

  require_once("includes/db.php");
  require_once("includes/unixclient.php");

  $req = trim(fgets(STDIN));
  list($url_sender, $url_recipient, $zoobars) = explode('&', $req);
  $sender = urldecode($url_sender);
  $recipient = urldecode($url_recipient);
  
  $transfer_ts = date(DATE_RFC822);
  $sql = "INSERT INTO Transfers (Sender, Recipient, Amount, Time) " .
         "VALUES ('" . addslashes($sender) . "', " .
         "'" . addslashes($recipient) . "', " .
         "$zoobars, '$transfer_ts')";
  $db->executeQuery($sql);  
  echo "OK\n";
?>
