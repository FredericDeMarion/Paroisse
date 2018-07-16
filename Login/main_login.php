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
// 21/05/2018 : Responsive //==================================================================================================

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
	
	echo '<div class="row justify-content-around">';
	echo '<div class="col-lg-4 col-xs-10 col-sm-10 col-md-10">';
	
	echo '<FORM name="form1" method="post" action="checklogin.php">';

	echo '<div class="form-group">';
	echo '<div align="center">';
	echo '<IMG SRC="/logo.jpg" HEIGHT=150>';
	echo '</div>';
	echo '</div>';
	
	echo '<div class="form-group">';
	echo '<div class="col" class="form-control" align="center">';
	echo '<FONT face=verdana size=4><STRONG>Connection base de données<BR>'.$Paroisse_name.'<BR>&nbsp</FONT></STRONG>';
	echo '</div>';
	echo '</div>';
	
	// identifiant
	echo '<div class="form-group">';
	echo '<label for="e_mail">Email</label>';
	echo '<INPUT name="myusername" type="email" class="form-control" id="e_mail">';
	echo '</div>';
	
	// mot de passe
	echo '<div class="form-group">';
	echo '<label for="mypassword">Mot de Passe</label>';
	echo '<INPUT name="mypassword" type="password" class="form-control" id="mypassword">';
	echo '</div>';
	
	// Autre ID paroissien
	echo '<div class="form-group">';
	echo '<label for="myid">Paroissien ID</label>';
	echo '<INPUT name="myid" type="text" class="form-control" id="myid">';
	echo '</div>';
	
	// Niveau souhaité
	echo '<input type=hidden name="mylevelrequested" value=100 >';

	echo '<div class="form-group">';
	echo '<INPUT type="submit" class="btn btn-secondary btn-sm" name="Submit" value="Login">';
	echo '</div>';
	
	echo '</FORM>';
	
	echo '</div>';
	echo '</div>';	
	

?>