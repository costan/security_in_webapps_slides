#!/usr/bin/php-cgi
<?php 
  require_once("includes/common.php"); 
  nav_start_outer("Users");
  nav_start_inner();
?>
 <form name="profileform" method="GET"
  action="<?php echo $_SERVER['PHP_SELF']?>">
 <nobr>User:
 <input type="text" name="user" value="<?php 
   echo $_GET['user']; 
 ?>" size=10></nobr><br>
 <input type="submit" value="View">
</form>
<div id="profileheader"><!-- user data appears here --></div>
<?php 
  $selecteduser = $_GET['user']; 
  $sql = "SELECT Profile, Username, Zoobars FROM Person " . 
         "WHERE Username='" . addslashes($selecteduser) . "'";
  $rs = $db->executeQuery($sql);
  if ( $rs->next() ) { // Sanitize and display profile
    list($profile, $username, $zoobars) = $rs->getCurrentValues();
    echo "<div class=profilecontainer><b>Profile</b>";
    $allowed_tags = 
      '<a><br><b><h1><h2><h3><h4><i><img><li><ol><p><strong><table>' .
      '<tr><td><th><u><ul><em><span>';
    $profile = strip_tags($profile, $allowed_tags);
    $disallowed = 
      'javascript:|window|eval|setTimeout|setInterval|target|'.
      'onAbort|onBlur|onChange|onClick|onDblClick|'.
      'onDragDrop|onError|onFocus|onKeyDown|onKeyPress|'.
      'onKeyUp|onLoad|onMouseDown|onMouseMove|onMouseOut|'.
      'onMouseOver|onMouseUp|onMove|onReset|onResize|'.
      'onSelect|onSubmit|onUnload';
    $profile = preg_replace("/$disallowed/i", " ", $profile);
    echo "<p id=profile>$profile</p></div>";
  } else if($selecteduser) {  // user parameter present but user not found
    echo '<p class="warning" id="baduser">Cannot find that user.</p>';
  }
  $zoobars = ($zoobars > 0) ? $zoobars : 0;
  echo "<span id='zoobars' class='$zoobars'/>";	
?><script type="text/javascript">
  var total = eval(document.getElementById('zoobars').className);
  function showZoobars(zoobars) {
    document.getElementById("profileheader").innerHTML =
      "<?php echo $selecteduser ?>'s zoobars:" + zoobars;
    if (zoobars < total) {
      setTimeout("showZoobars(" + (zoobars + 1) + ")", 100);
    }
  }
  if (total > 0) showZoobars(0);  // count up to total
</script>
<?php 
  nav_end_inner();

  if ($selecteduser) {
    $sql = "SELECT Sender, Recipient, Amount, Time FROM Transfers " .
           "WHERE Sender='" . addslashes($selecteduser) . "' OR " .
                 "Recipient='" . addslashes($selecteduser) . "' " .
           "ORDER BY TransferID";
    $rs = $db->executeQuery($sql);
    $firsttime = 1;
    while ($rs->next()) {
      if ($firsttime) {
        $firsttime = 0;
	?>
	<table class="log" align=center>
	<tr><th>Time</th>
	    <th>Sender</th>
	    <th>Recipient</th>
	    <th>Amount</th></tr>
	<?php
      }

      list($sender, $recipient, $zoobars, $time) = $rs->getCurrentValues();
      echo "<tr><td align=center>$time</td>\n" .
	       "<td align=center>$sender</td>\n" .
	       "<td align=center>$recipient</td>\n" .
	       "<td align=center>$zoobars</td></tr>\n";
    }

    if (!$firsttime) {
      echo "</table>\n";
    }
  }

  nav_end_outer(); 
?>
