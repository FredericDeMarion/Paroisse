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
	//mysql_select_db( $sqlbase, $db );
	mysqli_query($db, "SET NAMES 'ISO-8859-1'");
	mysqli_query($db, 'SET NAMES latin1');
	
	$requete_Lieux = 'SELECT * FROM Lieux WHERE IsParoisse = -1';
	$result_Lieux = mysqli_query($db, $requete_Lieux);
	while($row_lieu = mysqli_fetch_assoc($result_Lieux)){
		$Paroisse_name = $row_lieu['Lieu'];
	}


	echo '<TABLE align="center">';
	echo '<TR><TD><IMG SRC="/logo.jpg" HEIGHT=150></TD>';
	echo '<TD>';

	echo '<TABLE width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">';
	echo '<TR><FORM name="form1" method="post" action="checklogin.php">';
	echo '<TD>';
	echo '<TABLE width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">';
	echo '<TR><TD colspan="3" align="center" ><FONT face=verdana size=2><STRONG>Connection base de données '.$Paroisse_name.'<BR>&nbsp</FONT></STRONG></TD></TR>';
	
	// identifiant
	echo '<TR><TD width="300"><FONT face=verdana size=2>Adresse mail</FONT></TD><TD width="6">:</TD>';
	echo '<TD width="400"><INPUT name="myusername" type="text" id="myusername"></TD></TR>';

	// date de naissance
	echo '<TR><TD width="300"><FONT face=verdana size=2>Date de naissance</FONT></TD><TD width="6">:</TD>';
	echo '<TD width="400"><INPUT name="mynaissance" placeholder="JJ/MM/AAAA" type="text" id="mynaissance"></TD></TR>';

	// mot de passe
	echo '<TR><TD><FONT face=verdana size=2>Mot de passe</FONT></TD><TD>:</TD>';
	echo '<TD><INPUT name="mypassword" type="password" id="mypassword"></TD></TR>';
	
	// Autre ID paroissien
	echo '<TR><TD><FONT face=verdana size=2>Paroissien ID</FONT></TD><TD>:</TD>';
	echo '<TD><INPUT name="myid" type="text" id="myid"></TD></TR>';
	
	echo '<TR><TD> </TD><TD> </TD><TD><INPUT type="submit" name="Submit" value="Login"></TD></TR>';
	echo '</TABLE></TD></FORM>';
	echo '</TR>';
	echo '</TABLE>';
	
	echo '</TD></TABLE>';
?>