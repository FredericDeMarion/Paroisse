<?php
//======================================================

//global $phpmyvisites_Title;
if ($_SESSION['Activite_id'] == 2) { // Mariage
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/Mariage/'.$_SERVER["REMOTE_ADDR"].'';
} else if ($_SESSION['Activite_id'] == 3) { // Baptême
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/Bapteme/'.$_SERVER["REMOTE_ADDR"].'';
} else if ($_SESSION['Activite_id'] == 4) { // Parcours Alpha
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/Parcours_Alpha/'.$_SERVER["REMOTE_ADDR"].'';
} else if ($_SESSION['Activite_id'] == 12) { // Cathéchèse
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/Cathechese/'.$_SERVER["REMOTE_ADDR"].'';
} else if ($_SESSION['Activite_id'] == 22) { // Emmaüs
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/Emmaus/'.$_SERVER["REMOTE_ADDR"].'';
} else if ($_SESSION['Activite_id'] == 26) { // Aumonerie
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/Aumonerie/'.$_SERVER["REMOTE_ADDR"].'';
} else {
	$phpmyvisites_Title = $_SERVER["PHP_AUTH_USER"].'/SuiviParoissien/'.$_SERVER["REMOTE_ADDR"].'';
}

?>
<!-- phpmyvisites -->

<script type="text/javascript">
<!--
var a_vars = Array();
var pagename='<?php echo $phpmyvisites_Title; ?>' ;

var phpmyvisitesSite = 175673;
var phpmyvisitesURL = "http://st.free.fr/phpmyvisites.php";
//-->
</script>
<script language="javascript" src="http://st.free.fr/phpmyvisites.js" type="text/javascript"></script>
<object><noscript><p>phpMyVisites | Open source web analytics
<img src="http://st.free.fr/phpmyvisites.php" alt="Statistics" style="border:0" />
</p></noscript></object></a>
<!-- /phpmyvisites --> 
<?php
//======================================================
