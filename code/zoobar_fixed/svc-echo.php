#!/usr/bin/php
<?php
  # To find any necessary require'd files
  chdir("/zoobar");

  require_once("includes/db.php");
  require_once("includes/unixclient.php");

  $req = trim(fgets(STDIN));
  echo "You said $req\n";
?>
