#!/usr/bin/php-cgi
<?php 
  require_once("includes/common.php"); 
  nav_start_outer("Transfer");
  nav_start_inner();
  if($_POST['submission']) {
    $recipient = $_POST['recipient'];
#START:flawed_logic
    $zoobars = (int) $_POST['zoobars'];
    $sql = "SELECT Zoobars FROM Person WHERE Username='" .
           addslashes($user->username) . "'";
    $rs = $db->executeQuery($sql);
    $sender_balance = $rs->getValueByNr(0,0) - $zoobars;

    $sql = "SELECT Username, Zoobars FROM Person WHERE Username='" .
	   addslashes($recipient) . "'";
    $rs = $db->executeQuery($sql);
    $recipient_exists = $rs->getValueByNr(0,0);
    $recipient_balance = $rs->getValueByNr(0,1) + $zoobars;

    if($sender_balance >= 0 && $recipient_balance >= 0 && $recipient_exists) {
    	$sql = "UPDATE Person SET Zoobars = $sender_balance " .
             "WHERE Username='" . addslashes($user->username) . "'";
#END:flawed_logic
    	$db->executeQuery($sql);
      $sql = "UPDATE Person SET Zoobars = $recipient_balance " .
             "WHERE Username='" . addslashes($recipient) . "'";
      $db->executeQuery($sql);
      $transfer_ts = date(DATE_RFC822);
      $sql = "INSERT INTO Transfers (Sender, Recipient, Amount, Time) " .
	     "VALUES ('" . addslashes($user->username) . "', " .
		     "'" . addslashes($recipient) . "', " .
		     "$zoobars, '$transfer_ts')";
      $db->executeQuery($sql);
      $result = "Sent $zoobars zoobars";
    }
    else $result = "Transfer to $recipient failed.";
  }
?>
<p><b>Balance:</b>
<span id="myZoobars"></span> zoobars</p>
<!-- START:transfer_form -->
<form method=POST name=transferform
  action="<?php echo $_SERVER['PHP_SELF']?>">
<p>Send <input name=zoobars type=text value="<?php 
  echo $_POST['zoobars']; 
?>" size=5> zoobars</p>
<p>to <input name=recipient type=text value="<?php 
  echo $_POST['recipient']; 
?>" size=10></p>
<input type=submit name=submission value="Send">
</form>
<!-- END:transfer_form -->
<span class=warning><?php 
  echo $result; 
?></span>
<?php 
  nav_end_inner();
?>
<script type="text/javascript" src="zoobars.js.php"></script>
<?php
  nav_end_outer(); 
?>
