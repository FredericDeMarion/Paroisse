<?php
session_start();

$_SERVER['USER_ID']=0;
$_SESSION['USER_LEVEL_REQUESTED']=0;
$_SERVER['PHP_AUTH_USER'] ="";
$_SESSION["Session"] = 0;
session_cache_expire(0);
session_destroy();
echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
//session_destroy();
exit;
?>