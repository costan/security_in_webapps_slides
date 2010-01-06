<?php
if (empty($_COOKIE['csrf_base']) || !isset($_COOKIE['csrf_base'])) {
	$csrf_base = sha1("csrf" . mt_rand() . "_" . getmypid() . "_" .
	                  microtime(true));
	setcookie('csrf_base', $csrf_base);
}
else {
  $csrf_base = $_COOKIE['csrf_base'];
}
$csrf_token = sha1($_COOKIE['csrf_base'] .
                   "hduM3POw/NCTmMfy7vKZxdDjupKnuK6r9+2t9b7Y9LS3uKr2fw");

function check_csrf_token() {
	global $csrf_token;	
	if ($_POST['_csrf_token'] != $csrf_token) {
		die();
	}
}
function csrf_form_field() {
	global $csrf_token;
	echo '<input type="hidden" name="_csrf_token" value="' . $csrf_token . '" />';
}
?>
