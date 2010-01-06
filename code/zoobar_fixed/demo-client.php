#!/usr/bin/php
<?php 
  require_once("includes/unixclient.php");

  $c = new UnixClient("../echosvc/sock");
  $r = $c->call("hello\n");
  echo "Response: ", $r, "\n";
?>
