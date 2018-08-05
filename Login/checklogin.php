<?php
$a = session_id();
if(empty($a)) session_start();
header( 'content-type: text/html; charset=iso-8859-1' );

//==================================================================================================
//    Nom du module : checklogin.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 10/05/2017 : Suppression de USER_LEVEL_REQUESTED
// 05/08/2018 : Configuration auto chaque début aout des services et ressourcements de l'année suivante
//==================================================================================================



function debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php print $ch; ?>')
	</SCRIPT><?php
}

	$_SESSION['USER_ID']=0;
	$username=""; // Mysql username
	$password=""; // Mysql password
	$time_check=time()-3600; //SET TIME 30 Minutes
	//$tbl_name="members"; // Table name
	
	// Connect to server and select database.
	require('sqlconf.php');
	$db= mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die("Cannot connect MySql : " . mysqli_error());
	//mysql_select_db( $sqlbase, $db );
	mysqli_query($db, "SET NAMES 'ISO-8859-1'");
	mysqli_query($db, 'SET NAMES latin1');
	mysqli_query($db, 'TRUNCATE Debug') or die (mysqli_error($db));

	
	// Récupération du UserName et du Mot de Passe depuis la saisie du module "main_login.php"
	$myusername=$_POST['myusername'];
	$mypassword=$_POST['mypassword'];
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	
	$LDAP_username="cn=".$myusername.",ou=users,dc=ndsagesse,dc=com";
	//error_log("LDAP parametre = '".$LDAP_username."' password= '".$mypassword."'");
	$LDAP_Pointeur = ldap_connect('localhost',389) or die("Connection LDAP impossible");
	ldap_set_option($LDAP_Pointeur, LDAP_OPT_PROTOCOL_VERSION, 3);
	$ldapbind = ldap_bind($LDAP_Pointeur, $LDAP_username, $mypassword);
	
	if ( $ldapbind) {
		$_SESSION["LDAP_Actif"] = True;
		error_log("LDAP actif Cas 2");
	} else {
		$ldapbind = ldap_bind($LDAP_Pointeur, $LDAP_username, "freddoetbenoittravaillentleLDAP06");
		if ( $ldapbind) {
			$_SESSION["LDAP_Actif"] = True;
			error_log("LDAP actif Cas 1.1");
			// initier le mot de passe dans LDAP
			if (ldap_mod_replace($LDAP_Pointeur, $LDAP_username, array("userPassword"=>$mypassword))) {
				error_log("LDAP Mot de passe modifié avec succes");
			} else {
				error_log("LDAP Mot de passe modifié avec échec");
			}
		} else {		
			$mypassword = sha1(sha1($mypassword)."f8S61HFds1");		
			$_SESSION["LDAP_Actif"] = False;
			error_log("LDAP inactif Cas 1.2");
		}
	}
	
	if (isset($_POST['mynaissance'])) {
		$mynaissance=$_POST['mynaissance'];
		list($day, $month, $year) = preg_split('[/]', $mynaissance);
		$mynaissance=$year."-".$month."-".$day;	
		error_log("Cas 3");
	} else {
		$mynaissance="";
		error_log("Cas 4");
	}
	
	if ($_SESSION["LDAP_Actif"] == False) {
		$sql4='DELETE FROM Admin_user_online WHERE time<'.$time_check.'';
		$result4=mysqli_query($db, 'DELETE FROM Admin_user_online WHERE time<'.$time_check.'');
	
		// -------------------------------------------------------------
		// vérifier si le user est attendu en login
		// -------------------------------------------------------------
		$requete='SELECT * FROM Admin_membres WHERE username="'.$myusername.'" and password="'.$mypassword.'" AND droit_acces = 1';
		$result=mysqli_query($db, $requete);
		$count=mysqli_num_rows($result);
		error_log("Cas 5");
	} else {
		if ($ldapbind) {
			$count = 1;
			error_log("Cas 6");
		} else {
			$count = 0;
			error_log("Cas 7");
		}
	}
	
	// If result matched $myusername, $mynaissance and $mypassword, table row must be 1 row
	if ( $count >= 1 && $_POST['mypassword'] != "" ){
		error_log("Cas 8");
		// -------------------------------------------------------------
		// Le user et mdp existant - on va chercher l'ID de la personne
		// sauvegarde de l'adresse IP et de l'horodate de connexion
		// -------------------------------------------------------------

		if ($ldapbind) {
			$requete='SELECT * FROM Admin_membres WHERE username="'.$myusername.'"';
		} else {
			$requete='SELECT * FROM Admin_membres WHERE username="'.$myusername.'" and password="'.$mypassword.'" ';
		}
		$result=mysqli_query($db, $requete);
		while($row = mysqli_fetch_assoc($result)){
			if( isset( $_POST['myid'] ) ) {
				$_SESSION['USER_ID']= (int)$_POST['myid'];
			} else {
				$_SESSION['USER_ID']= $row['Individu_id'];
			}
			mysqli_query($db, 'UPDATE Admin_membres SET membre_adresse_ip="'.$_SERVER["REMOTE_ADDR"].'", membre_derniere_visite=NOW(), membre_counter=('.$row['membre_counter'].'+1) WHERE Individu_id='.$_SESSION['USER_ID']) or die("Erreur de sauvegarde adresse ip");
		}
		error_log("Checklogin : Login 01 de ".$_SESSION['USER_ID']." -> ".$myusername);
	
		require('counter.php');
		//error_log("Checklogin : counter.php passed");
		//echo '<META http-equiv="refresh" content="0; URL=/index.php">';
		//header("location:/index.php");
		// vérifier s'il ne faut pas faire une sauvegarde des services et Ressourcements pour l'année passée (passage au mois d'Aout)
		header("location:/Admin_Yearly.php?action=SauvegarderServicesYearly");
		exit;

	} elseif ($_POST['mypassword'] != "" AND $_SESSION["LDAP_Actif"] == False) {
		error_log("Cas 9");
		// valable avec ou sans LDAP et mot de passe non défini
		// -------------------------------------------------------------
		// 2ème cas de figure, c'est une première connexion, le mot de passe n'a jamais été saisie
		// -------------------------------------------------------------

		$requete='SELECT * FROM Admin_membres WHERE username="'.$myusername.'" and password="" AND droit_acces = 1';
		$result=mysqli_query($db, $requete);
		$count=mysqli_num_rows($result);
	
		if ( $count >= 1) {
			error_log("Cas 10");
			// sauvegarder le nouveau mot de passe, de l'adresse IP et de l'horodate de connexion
			$result=mysqli_query($db, 'UPDATE Admin_membres SET password="'.$mypassword.'", membre_adresse_ip="'.$_SERVER["REMOTE_ADDR"].'", membre_derniere_visite=NOW() WHERE username="'.$myusername.'" ')or die("Login : Erreur de sauvegarde du password");

			// sauvegarde en session de l'individu id
			$requete='SELECT * FROM Admin_membres WHERE username="'.$myusername.'" and password="'.$mypassword.'" AND droit_acces = 1';
			$result=mysqli_query($db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				$_SESSION['USER_ID']= $row['Individu_id'];
			}
			error_log("Checklogin : 1er Login 00 de ".$_SESSION['USER_ID']." -> ".$myusername);
			//echo '<META http-equiv="refresh" content="0; URL=/index.php">';
			header("location:/index.php");
			exit;

		} else {
			error_log("Cas 11");
			error_log("Checklogin : Echec 1er Login de ".$_SESSION['USER_ID']." -> ".$myusername);
			echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
			exit;
		}
	} else {
		error_log("Cas 12");
		error_log("Checklogin : Echec Login de ".$_SESSION['USER_ID']." -> ".$myusername);
		echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
		exit;
	}

?>