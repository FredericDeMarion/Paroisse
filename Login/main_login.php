<?php
$a = session_id();
if(empty($a)) session_start();
//header( 'content-type: text/html; charset=latin1' );

//==================================================================================================
//    Nom du module : main_login.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 10/05/2017 : Suppression de USER_LEVEL_REQUESTED
//==================================================================================================

	$Paroisse_name = "Notre Dame de la Sagesse"; // Sophia-Antipolis
	$Paroisse_name = "St Paul des 4 vents"; // Lyon

	require("sqlconf.php");
	$db= mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die("Cannot connect Database : " . mysql_error());
	
		//Bootstrap 4.0.0 beta 2
	//---------------
	echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">';
	echo '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>';
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>';
	

	//mysql_select_db( $sqlbase, $db );
	mysqli_query($db, "SET NAMES 'ISO-8859-1'");
	mysqli_query($db, 'SET NAMES latin1');
	
	$requete_Lieux = 'SELECT * FROM Lieux WHERE IsParoisse = -1';
	$result_Lieux = mysqli_query($db, $requete_Lieux);
	while($row_lieu = mysqli_fetch_assoc($result_Lieux)){
		$Paroisse_name = $row_lieu['Lieu'];
	}

	header( 'content-type: text/html; charset=UTF-8' );
	echo '<!DOCTYPE HTML>';
	echo '<HTML><HEAD>';
	echo '<TITLE>Database '.$Paroisse_name.'</TITLE>';
	//echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';
	echo '</HEAD>';
	setlocale (LC_TIME, 'fr_FR','fra');	
	mb_internal_encoding('UTF-8');
	

	echo '<FORM name="form1" method="post" action="checklogin.php">';
	echo '<TABLE width="420" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">';
	
	echo '<TR><TD colspan="3" align="center"><IMG SRC="/logo.jpg" HEIGHT=150></TD></TR>';
	
	echo '<TR><TD colspan="3" align="center" ><FONT face=verdana size=2><STRONG>Connection base de données '.$Paroisse_name.'<BR>&nbsp</FONT></STRONG></TD></TR>';
	
	// identifiant
	echo '<TR><TD width="300">';
	echo '<div class="col"><label for="e_mail">Email</label><INPUT name="myusername" type="email" class="form-control form-control-sm" id="e_mail">';
	echo '</div></TD></TR>';

	// date de naissance
	//echo '<TR><TD width="300"><FONT face=verdana size=2>Date de naissance</FONT></TD><TD width="6">:</TD>';
	//echo '<TD width="400"><INPUT name="mynaissance" placeholder="JJ/MM/AAAA" type="text" id="mynaissance"></TD></TR>';

	// mot de passe
	echo '<TR><TD>';
	echo '<div class="col"><label for="mypassword">Mot de Passe</label>';
	echo '<INPUT name="mypassword" type="password" class="form-control form-control-sm" id="mypassword">';
	echo '</div></TD></TR>';
	
	// Autre ID paroissien
	echo '<TR><TD>';
	echo '<div class="col"><label for="myid">Paroissien ID</label>';
	echo '<INPUT name="myid" type="text" class="form-control form-control-sm" id="myid">';
	echo '</div></TD></TR>';
	
	echo '<TR><TD>';
	echo '<div class="col"><INPUT type="submit" class="btn btn-secondary btn-sm" name="Submit" value="Login">';
	echo '</div></TD></TR>';
	echo '</TABLE></FORM>';


?>