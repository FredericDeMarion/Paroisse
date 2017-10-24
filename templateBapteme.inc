<?php

//==================================================================================================
//    Nom du module : templateBapteme.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
//24/10/2017 : remplacement de stripAccents par fCOM_stripAccents

$Liste_Confessions = array("Confession ?", "Sans", "Catéchumène", "Catholique", "Orthodoxe", "Protestant", "Musulman", "Juif", "Bouddhiste", "Autre");
$Liste_Genre = array(" ", "F", "M");

function debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php echo $ch; ?>')
	</SCRIPT><?php

}

// Base QuiQuoi.Activite
// 1 - Pretre
// 2 - Accompagnateur Fiances preparation mariage
// 4 - Diacre
// 8 - Accompagnateur Bapteme


 
//header for all pages
function address_top () 
{

if(empty($_SESSION["Session"])) {
	//Re-initialiser la valeur de $Session en revenant a l'accueil
	echo '<META http-equiv="refresh" content="1; URL=/index.php">';
	exit;
}

global $SessionEnCours;
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<TITLE> Database Baptême </TITLE>
<meta name="generator" content="WYSIWYG Web Builder - http://www.wysiwygwebbuilder.com">
<script language="JavaScript" type="text/javascript">
<!--
function popupwnd(url, toolbar, menubar, locationbar, resize, scrollbars, statusbar, left, top, width, height)
{
   var popupwindow = this.open(url, '', 'toolbar=' + toolbar + ',menubar=' + menubar + ',location=' + locationbar + ',scrollbars=' + scrollbars + ',resizable=' + resize + ',status=' + statusbar + ',left=' + left + ',top=' + top + ',width=' + width + ',height=' + height);
}
//-->
</script>
<link rel="stylesheet" href="css/style_titre.css" type="text/css" media="screen" />
<link rel="icon" type="image/png" href="logo.png" />
</HEAD>
<BODY BGCOLOR="#FFFFFF" link="blue" vlink="blue" alink="blue">
<CENTER>
<!-- Provided by: Ross Poulton -->
<SCRIPT LANGUAGE="JavaScript">
	<!-- Begin
	function showProfile(id) {
		var windowprops = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=400,height=350";

		OpenWindow = window.open("<?php echo $_SERVER['PHP_SELF']; ?>?action=profile&id=" + id, "profile", windowprops); 
		
}
	//  End --> 
</script>

<?php
Global $eCOM_db;
$debug = false;
//debug("Session(template)=".$Session . "<BR>\n");
//debug("SessionEnCours(template)=".$SessionEnCours . "<BR>\n");
//$Activite= 4; //Preparation Bapteme
$levelAutorisation = fCOM_Get_Autorization($_SESSION["Activite_id"]);

// compter le nombre de personne connecté
$sql3='SELECT * FROM Admin_user_online';
$result3=mysqli_query( $eCOM_db, $sql3);
$count_user_online=mysqli_num_rows($result3);
?>
<style type="text/css">
#Titre { width: 100%; height: 90px; display:inline; background-color:white; color: white; position: fixed; z-index: 2; top: 0px; left: 0px; right: 0px; }
</style>
<style type="text/css">
#Corps { width: 100%; height: 190px; display:inline; color: white; position: relative; top: 90px; left: 0px; right: 0px; }
</style>

<div id="Titre">
<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000">
	<TR>
		<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
			<TR BGCOLOR="#F7F7F7">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
				<?php
				$result = mysqli_query( $eCOM_db, "SELECT Nom FROM Activites WHERE id=".$_SESSION["Activite_id"]." ");
				$row = mysqli_fetch_assoc( $result );
				$Titre_Page = $row['Nom'];
				if ($_SESSION["Session"]=="All") {
					echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Préparation '.$Titre_Page.' All</B>';
				} else {
					echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Préparation '.$Titre_Page.' '.($_SESSION["Session"] - 1).' - '.$_SESSION["Session"].'</B>';
				}
				$phpmyvisites_Title = $Titre_Page;
				//include 'phpmyvisites.php';

				echo '<BR>';
				echo '<FONT FACE="Verdana" SIZE="1">';
		
				require("Login/sqlconf.php");
				if ($_SESSION["Session"]=="All") {
					$result = mysqli_query( $eCOM_db, "SELECT * FROM Bapteme WHERE Activite_id=".$_SESSION["Activite_id"]." ");
				} else {
					$result = mysqli_query( $eCOM_db, "SELECT * FROM Bapteme WHERE Session = ".$_SESSION["Session"]." AND Activite_id=".$_SESSION["Activite_id"]." ");
				}
				$num_total = mysqli_num_rows($result);
				echo("Il y a " . $num_total . " fiches baptême en cours.</FONT></TD>\n"); 
				echo '<TD ALIGN="right">';
				$result = Afficher_compteur_de_login();
				?>
				</TR></TABLE></TD>
			</TR>
			<TR BGCOLOR="#DDDDDD">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR><TD>
				<ul id="menu">
				<?php
				if ($_SERVER['USER'] <= 3 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) {
				?>
					<li class="menu_right"><a href="#" class="drop">Baptisés &nbsp &nbsp</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'">Liste des baptisés</a></li>';
									if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
										echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=edit&id=0">Ajouter un baptisé</a></li>';
										echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=edit_Individu&id=0">Ajouter un paroissien</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Accompagnateurs</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=list_accomp">Liste</a></li>';
									echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=list_accompagnateur_sssession">Liste par sous session</a></li>';
									if (fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=Configuration_Accompagnateur&id=0&Activite=3">Ajouter et retirer</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Rencontres</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=rencontres">Liste</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					if ($_SESSION["Activite_id"]==3) { // Baptême bébé
					?>
					<li class="menu_right"><a href="#" class="drop">Divers</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Reference/Bapteme/0_La preparation bapteme en 6 etapes.pdf">0-Prépa Baptême en 6 rencontres</a></li>';
									echo '<li><a href="Reference/Bapteme/0_Qui_fait_quoi_repartition_des_taches.pdf">0-Répartition des tâches</a></li>';
									echo '<li><a href="Reference/Bapteme/1_Introduction_1ere soiree_topo JeCrois.pdf">1- Introduction 1ère rencontre</a></li>';
									echo '<li><a href="Reference/Bapteme/1_Questionnaire pour la seconde soiree.pdf">1- Questionnaire pour les couples</a></li>';
									echo '<li><a href="Reference/Bapteme/1_Role_Parrain_Marraine.pdf">1-Rôle des parrains marraines</a></li>';
									echo '<li><a href="http://public.ndsagesse.com/fr/training/2014-09-14_Bapteme-a-Sophia-Antipolis/">3-Video Signes</a></li>';
									echo '<li><a href="Reference/Bapteme/3_Attestation Bapteme.pdf">4-Attestation Prépa Baptême</a></li>';
									echo '<li><a href="http://ndbapteme.azurewebsites.net/#">5-Prépa célébration Baptême</a></li>';
									echo '<li><a href="Reference/Bapteme/Images_du_kerycube.docm">6-Images du Kérycube</a></li>';									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					}
					if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
					?>
					<li class="menu_right"><a href="#" class="drop">Comptabilité</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="Bapteme.php?Session='.$_SESSION["Session"].'&action=vue_financiere">Etat participation financière</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					}
				}
				?>
					<li class="menu_right"><a href="#" class="drop">Accueil</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<li><a href="index.php">Retour menu principal</a></li>
								</ul>
							</div>
						</div>
					</li>
				</ul>
				</TD>
				<TD ALIGN="right" width="300px" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : <?php echo '<A HREF="/Login/logout.php">'.$_SERVER['PHP_AUTH_USER'].'</A><BR>Utilisateur(s) connecté(s) : '.$count_user_online.''; ?>
				</TR></TABLE></TD>
			</TR>
		</TABLE></TD>
	</TR>
</TABLE>
</div>
<P>
<div id="Corps">
<TABLE WIDTH="99%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000"><TR><TD>	
	

<?php
}

//footer all pages
function address_bottom () {

	echo '<TR BGCOLOR="#DDDDDD">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#DDDDDD">';
	echo '<TR><TD HEIGHT="20" ALIGN="right" VALIGN="middle" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].' &nbsp</TD></TR>';
	echo '</DIV></TABLE>';
	echo '</TR>';
	
	echo '</TABLE>';
	
	echo '</TD></TR>';
	echo '</TABLE>';

	echo '</BODY>';
	echo '</HTML>';

}
