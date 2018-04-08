<?php

//==================================================================================================
//    Nom du module : user_online.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================


$session = session_id();
if(empty($session)) session_start();

$time=time();
$time_check=$time-3600; //SET TIME 30 Minutes

// Connect to server and select databse
require("sqlconf.php"); 
$db = mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die("cannot connect user_online.php module");

// vérifier que la session est comptabilisée
$sql='SELECT * FROM Admin_user_online WHERE session="'.session_id().'" ';
$result=mysqli_query($db, $sql);
$count=mysqli_num_rows($result);

if ( $count == "0" AND $_SESSION['USER_ID'] != 0 ) {
	// si session n'existe pas, l'ajouter à la liste en cours car c'est une nouvelle connexion
	$sql1='INSERT INTO Admin_user_online (session, time, id) VALUES ("'.session_id().'", "'.$time.'", '.$_SESSION['USER_ID'].')';
	$result1=mysqli_query($db, $sql1);

	// Augmenter le compteur de connexion de 1
	$result1=mysqli_query($db, 'SELECT counter FROM Admin_counter');
	$row1 = mysqli_fetch_array($db, $result1);
	$counter= (int)$row1['counter'] + 1;
	$result2=mysqli_query($db, 'UPDATE Admin_counter SET counter='.$counter.'') or die("user_online: Erreur incrémentation compteur de connexion");

} elseif ( $count != "0" AND $_SESSION['USER_ID'] != 0 ) {
	// la session existe déjà, vérifier que le paroissien a utiliser le site depuis moins de 30 min
	$sql4='SELECT * FROM Admin_user_online WHERE time<'.$time_check.' AND session="'.session_id().'"';
	$result4=mysqli_query($db, $sql4);
	$count=mysqli_num_rows($result4);
	if ( $count != "0" ) {
		// L'utilisateur n' a pas utilisé le site depuis plus de 30 min, on force un relogin
		$_SESSION['USER_ID']=0;
		error_log("user_online : Logout 01");
		echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
		//exit;

	} else {
		// L'utilisateur continu d'utiliser le site normalement, on met à jour l'heure de la dernière action
		$sql2='UPDATE Admin_user_online SET time="'.$time.'" WHERE session = "'.session_id().'"';
		$result2=mysqli_query($db, $sql2);
		$result=mysqli_query($db, 'UPDATE Admin_membres SET membre_derniere_visite=NOW() WHERE username="'.$_SESSION['myusername'].'"') or die("user_online:Erreur de sauvegarde date dernière visite");
	}
} elseif ( $_SESSION['USER_ID'] == 0 ) {
	error_log("user_online : Logout 02");
	echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
	//exit;
}

// if over 30 minute, delete session
$sql4='DELETE FROM Admin_user_online WHERE time<'.$time_check.'';
$result4=mysqli_query($db, 'DELETE FROM Admin_user_online WHERE time<'.$time_check.'');


// Close connection
mysqli_close($db);

?>