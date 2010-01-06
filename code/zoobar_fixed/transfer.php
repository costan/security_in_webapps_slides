#!/usr/bin/php-cgi
<?php 
  require_once("includes/common.php"); 
  nav_start_outer("Transfer");
  nav_start_inner();
  if($_POST['submission']) {
  	check_csrf_token();
    $recipient = $_POST['recipient'];
    $zoobars = (int) $_POST['zoobars'];
    
    # Perform the transfer via the bank service.
    $c = new UnixClient("../banksvc/sock");
    $result = $c->call(urlencode($recipient) . "&$zoobars&" .
                       urlencode($user->username) . "&" .
                       urlencode($user->token) . "\n");
  }
?>
<p><b>Balance:</b>
<span id="myZoobars"></span> zoobars</p>
<form method=POST name=transferform
  action="<?php echo $_SERVER['PHP_SELF']?>">
<?php csrf_form_field(); ?>  
<p>Send <input name=zoobars type=text value="<?php 
  echo htmlentities($_POST['zoobars']);
?>" size=5> zoobars</p>
<p>to <input name=recipient type=text value="<?php 
  echo htmlentities($_POST['recipient']); 
?>" size=10></p>
<input type=submit name=submission value="Send">
</form>
<span class=warning><?php 
  echo $result; 
?></span>
<?php 
  nav_end_inner();
?>
<script type="text/javascript" src="zoobars.js"></script>
<?php
  nav_end_outer(); 
?>
